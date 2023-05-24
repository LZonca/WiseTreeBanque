<?php

session_start();

if ($_SERVER['SERVER_NAME'] == "127.0.0.1") {
    $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root', '');
} elseif ($_SERVER['SERVER_NAME'] == "10.206.237.111" || $_SERVER['SERVER_NAME'] == "10.206.237.112" || $_SERVER['SERVER_NAME'] == "www.wisetreebanque.sio") {
    $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root', 'wisetree');
} elseif ($_SERVER['SERVER_NAME'] == "zonca.alwaysdata.net") {
    $bdd = new PDO('mysql:host=mysql-zonca.alwaysdata.net;dbname=zonca_wisebankdb;charset=utf8', 'zonca_adminbank', 'wisetreebanque');
}


try {
    $bdd;
} catch (exception $e) {
    die('Erreur messages: ' . $e->getMessage());
}

if (!isset($_SESSION['userid'])) {
    header('Location: connexion');
}

if (isset($_POST['retour'])) {
    header('Location: accueil');
}

if (isset($_POST['Deco'])) {
    header('Location: logout');
}

function messagerequest($bdd)
{

    $message = $_POST['usermessage'];
    date_default_timezone_set('Europe/Paris');
    $date = date('y-m-d h:i');
    $user = $_SESSION['userid'];
    $daterdv = $_POST['rdvtime'];

    $requete = "SELECT idconseiller FROM users WHERE userid = ?";
    $requete = $bdd->prepare($requete);
    $requete->execute(array($user));
    $dataconseiller = $requete->fetch();

    $requete = "INSERT INTO chat (envoyeurid, chat, destinataireid, time, daterdv) VALUES (?, ?, ?, ?, ?);";
    $requete = $bdd->prepare($requete);
    $requete->execute(array($user, $message, $dataconseiller['idconseiller'], $date, $daterdv));

    $_SESSION['usermessage'] = "<p class='alert alert-success'>Message envoyé avec succès.<p>";
}


// Vérifie si le bouton accepter a été cliqué
if (isset($_POST['accept'])) {
    $id = $_POST['accept'];
    $update = "UPDATE chat SET requeststatus = 1 WHERE idmsg = ?";
    $update = $bdd->prepare($update);
    $update->execute(array($id));
}

// Vérifie si le bouton refuser a été cliqué
if (isset($_POST['deny'])) {
    $id = $_POST['deny'];
    $update = "UPDATE chat SET requeststatus = 2 WHERE idmsg = ?";
    $update = $bdd->prepare($update);
    $update->execute(array($id));
    // Afficher le bouton Annuler
    echo "<form method='post' action='Messagerie'>
    <button type='submit' name='cancel' value='" . $id . "' class='btn btn-warning btn-sm'>Annuler le rendez-vous.</button>
    </form>";
}

// Vérifie si le bouton annuler a été cliqué
if (isset($_POST['cancel'])) {
    $id = $_POST['cancel'];
    $update = "UPDATE chat SET requeststatus = 3 WHERE idmsg = ?";
    $update = $bdd->prepare($update);
    $update->execute(array($id));
    // Affiche un message de confirmation de l'annulation du rendez-vous
    $_SESSION['usermessage'] = "<p class='alert alert-warning' role='alert'>Le rendez-vous a été annulé avec succès.</p>";
}
function displaymessage()
{
    global $bdd;
    $user = $_SESSION['userid'];
    

    $requetecompteur = "SELECT COUNT(*) FROM chat WHERE destinataireid = ?";
    $requetecompteur = $bdd->prepare($requetecompteur);
    $requetecompteur->execute(array($user));
    $compteur = $requetecompteur->fetch();

    $request = "SELECT permissions FROM users WHERE userid = ?";
    $request = $bdd->prepare($request);
    $request->execute(array($user));
    $dataperms = $request->fetch();

    $requete = "SELECT * FROM users WHERE userid IN (SELECT envoyeurid FROM chat WHERE destinataireid = ?);";
    $requete = $bdd->prepare($requete);
    $requete->execute(array($user));
    $data = $requete->fetch();

    if (($compteur == 0)) {
        echo "Aucun message à afficher.";
    } else {
            $requetedata = "SELECT * FROM chat WHERE destinataireid = ?";
            $requetedata = $bdd->prepare($requetedata);
            $requetedata->execute(array($user));
            $datamsg = $requetedata->fetch();
            if($dataperms['permissions'] > 1){
            echo "<table style='width: 100%;'><tr><th></th><th style='width: 5vw'></th><th>Message du client</th><th></th><th>Actions</th></tr>";
            }else{
                echo "<table style='width: 100%;'><tr><th></th><th></th><th>Message</th><th></th><th></th></tr>";
            }
        
        while ($datamsg = $requetedata->fetch()) {
            echo "<tr>
            <td><b><h2>" . $data['prenom'] . " " . $data['nom'] . "</b> souhaite planifier un rendez-vous à </h2></td>".
            "<td><h2>". $datamsg['daterdv'] ."</h2></td>".
            "<td><h2>". $datamsg['chat'] ."</td></h2></p>".
            "<td><small> Envoyé à " . $datamsg['time'] . "</td></small>
            <td>";

                if ($datamsg['requeststatus'] == 0) {
                    echo "<form method='post' action='messagerie'>
                    <button type='submit' name='accept' value='" . $datamsg['idmsg'] . "' class='btn btn-success btn-sm'>Accepter le rendez-vous.<br></button>
                    <button type='submit' name='deny' value='" . $datamsg['idmsg'] . "' class='btn btn-danger btn-sm'>Refuser le rendez-vous.</button>".
                    //"<button type='submit' name='cancel' value='" . $datamsg['idmsg'] . "' class='btn btn-warning btn-sm'>Annuler</button>". // Ce bouton est il nécéssaire
                    "</form>";
                }
                //Afficher le bouton Annuler (Il sera affiché dans la fonction suivante)

            }
            echo "</td></tr></table>";
            echo "</h2><br>";
        }
    }

function afficherdv()
{
    global $bdd;
    $user = $_SESSION['userid'];
    $requete = "SELECT * FROM users, chat WHERE chat.requeststatus = 1 AND users.userid IN (SELECT envoyeurid FROM chat WHERE destinataireid = ?);";
    $requete = $bdd->prepare($requete);
    $requete->execute(array($user));
    $data = $requete->fetch();

    $request = "SELECT permissions FROM users WHERE userid = ?";
    $request = $bdd->prepare($request);
    $request->execute(array($user));
    $dataperms = $request->fetch();

    $countcredits = "SELECT COUNT(*) FROM chat WHERE destinataireid = ? AND requeststatus = 1";
    $countcredits = $bdd->prepare($countcredits);
    $countcredits->execute(array($user));
    $compteur =  $countcredits->fetchColumn();
    if($dataperms['permissions'] > 1){
        if ($compteur == 0) {
            echo "<h2>Aucun RDV planifiés</h2>";
        } else {
?>

            <h3>Vos rendez-vous</h3>

            <table>
                <tr>
                    <th>Raison RDV</th>
                    <th>Date RDV</th>
                <?php  
                    
                        if($data['permissions'] > 1){
                            echo '<th>Client</th>';
                        }
                    ?>
                    <th>Action</th>
                </tr>
    <?php
            while ($datardv = $requete->fetch()) {
                echo "<tr>";
                echo "<td>" . $datardv['chat'] . "</td>";
                echo "<td>" . $datardv['date'] . "</td>";
                if($data['permissions'] > 1){
                echo "<td>" . $data['prenom'] . " " . $data['nom'] . "</td>";
                echo "<button type='submit' name='cancel' value='" . $datardv['idmsg'] . "' class='btn btn-warning btn-sm'>Annuler</button>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }
    }
}
if (isset($_POST['submit'])) {
    messagerequest($bdd);
}


function checkconseillers($bdd)
{

    $requetedata = "SELECT idconseiller FROM users WHERE userid = ?";
    $requetedata = $bdd->prepare($requetedata);
    $requetedata->execute(array($_SESSION["userid"]));
    $data = $requetedata->fetch();

    $info = "SELECT * FROM users WHERE userid = ?";
    $info = $bdd->prepare($info);
    $info->execute(array($data['idconseiller']));
    $infodata = $info->fetch();
    
    if($infodata){
        return "<p>Votre conseiller: " . $infodata['prenom'] . " " . $infodata['nom'] . "</p>";
    }else{
        return "Vous n'avez pas de conseiller.";
    }
    
}



/*elseif($datarank['permissions'] > 1){
        $requetedata = "SELECT * FROM chat WHERE destinataireid = ?";
        $requetedata = $bdd->prepare($requetedata); 
        $requetedata->execute(array($user));
        while($data = $requetedata->fetch())
        {
            echo $data['chat'] . " - " . $dataconseiller['prenom'] . " " . $dataconseiller['nom'] . "<button name='accept' class='btn btn-secondary'>Accepter le randez-vous.</button><br>";
        }
    }*/


    ?>

    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/jpg" href="logo.jpg" />
        <title>Wise Tree Banque - Contact</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <style>
            label {
                color: white;
                
            }
        </style>
    </head>

    <body>
        <div class="navbar-nav">
            <form method="POST" action="accueil">
                <button name="Deco" class="btn btn-secondary">Deconnexion</button>
                <button name="retour" class="btn btn-secondary">Retour</button>
                <?php 
                    if($_SERVER['SERVER_NAME'] == "10.206.237.9"){
                        echo "<a href='contact-mail' class='btn btn-secondary'>Contacter un collaborateur de la banque.</a>";
                    }
                ?>
                
            </form>
        </div>
        <div class="container">
            <h1>WiseTreeBank - Contact</h1>
            <?php
                echo checkconseillers($bdd); 
            ?>
                <p>Vos messages:</p>
                <?php 
                    if (isset($_SESSION['usermessage'])) {
                        echo $_SESSION['usermessage'];
                        unset($_SESSION['usermessage']);
                    }
                    displaymessage();
                ?>
        <br>

        <div class='form-container'>
        <?php 
            if (isset($_SESSION['usermessage'])) {
                    echo $_SESSION['usermessage'];
                    unset($_SESSION['usermessage']);
            } ?>
            <form method="POST" action="messagerie">
                <div class="form-group">
                    <label for='usermessage'>Raison du rendez-vous: </label><br>
                    <input type="text" name="usermessage" class="form-control" placeholder="Votre message"><br>
                    <label for='rdvtime'>Heure du rendez-vous: </label><br>
                    <input type="datetime-local" name="rdvtime" class="form-control" min=" <?= date('y-m-d h:i') ?>"><br>
                    <button name="submit" class="btn btn-primary">Envoyer le message</button>
                </div>
            </form>
        </div>
            <div class='form-container' style="margin-top: 2%; border-style: none;">
                <?php
                    afficherdv();
                ?>
            </div>
        </div>
        <script type="text/javascript" src='loading.js'></script>
    </body>

    </html>