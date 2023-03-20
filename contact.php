<?php
    session_start();
    
    //$bdd = new PDO('mysql:host=10.206.237.9;dbname=wisebankdb;charset=utf8', 'phpmyadmin', 'carriat'); // Reseau local VM
    $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');  //Localhost 

    try{
        $bdd;

    }catch(exception $e){
        die('Erreur messages: '. $e->getMessage());
    }



    if(isset($_POST['retour'])){
        header('Location: lescomptes.php');
    }
    
    if(isset($_POST['Deco'])){
        header('Location: logout.php');
    }

function messagerequest($bdd){

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

    echo "<p class='confirm'>Message envoyé avec succès.<p>";
}

function displaymessage($bdd){
    $user = $_SESSION['userid'];
    $requetedata = "SELECT * FROM chat WHERE destinataireid = ?";
    $requetedata = $bdd->prepare($requetedata); 
    $requetedata->execute(array($user));

    $requete = "SELECT * FROM users WHERE userid IN (SELECT envoyeurid FROM chat WHERE destinataireid = ?);";
    $requete = $bdd->prepare($requete); 
    $requete->execute(array($user));
    $data = $requete->fetch();
    
    while($datamsg = $requetedata->fetch())
{
    echo "<h2>" . $data['prenom'] . " " . $data['nom'] . " souhaite planifier un rendez-vous à " . $datamsg['daterdv'] . " | Message: " . $datamsg['chat'] . 
    "<small> - Envoyé à " . $datamsg['time'] . "</small>";
        
    if ($datamsg['requeststatus'] == 0) {
        echo"<form method='post' action='contact.php'>
        <button type='submit' name='accept' value='" . $datamsg['idmsg'] . "' class='btn btn-success btn-sm'>Accepter le rendez-vous.<br></button>
        <button type='submit' name='deny' value='" . $datamsg['idmsg'] . "' class='btn btn-danger btn-sm'>Refuser le rendez-vous.</button>
        <button type='submit' name='cancel' value='" . $datamsg['idmsg'] . "' class='btn btn-warning btn-sm'>Annuler</button>
        </form>";
    }
         
    echo"</h2><br>";
}


    // Vérifie si le bouton accepter a été cliqué
    if (isset($_POST['accept'])) {
        $id = $_POST['accept'];
        $update = "UPDATE chat SET requeststatus = 1 WHERE idmsg = ?";
        $update = $bdd->prepare($update);
        $update->execute(array($id));
        //Afficher le bouton Annuler
        echo "<form method='post' action='contact.php'>
        <button type='submit' name='cancel' value='" . $id . "' class='btn btn-warning btn-sm'>Annuler le rendez-vous.</button>
        </form>";
    }

    // Vérifie si le bouton refuser a été cliqué
    if (isset($_POST['deny'])) {
        $id = $_POST['deny'];
        $update = "UPDATE chat SET requeststatus = 2 WHERE idmsg = ?";
        $update = $bdd->prepare($update);
        $update->execute(array($id));
    }

    // Vérifie si le bouton annuler a été cliqué
    if (isset($_POST['cancel'])) {
        $id = $_POST['cancel'];
        $update = "UPDATE chat SET requeststatus = 3 WHERE idmsg = ?";
        $update = $bdd->prepare($update);
        $update->execute(array($id));
        // Affiche un message de confirmation de l'annulation du rendez-vous
        echo "<div class='alert alert-warning' role='alert'>Le rendez-vous a été annulé avec succès.</div>";

        function afficherdv(){
            global $bdd;
            $user = $_SESSION['userid'];
            $requete = "SELECT * FROM users, chat WHERE chat.requeststatus = 1 AND users.userid IN (SELECT envoyeurid FROM chat WHERE destinataireid = ?);";
            $requete = $bdd->prepare($requete); 
            $requete->execute(array($user));
            $data = $requete->fetch();

            echo "<h3>Vos randez-vous</h3>";

            echo "<table>";
            echo "<th>Raison RDV</th>";
            echo "<th>Date RDV</th>";
            echo "<th>Client</th>";
            while($datardv = $requete->fetch())
            {      
                echo "<tr>";
                echo "<td>" . $datardv['chat'] . "</td>";
                echo "<td>" . $datardv['date'] . "</td>";
                echo "<td>" . $data['prenom'] . " " . $data['nom'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
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
        <title>Connexion</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <style>
        </style>
    </head>

    <body>
    <div class="navbar-nav">
                <form method="POST" action="lescomptes.php">
                    <button name="Deco" class="btn btn-secondary">Deconnexion</button>
                    <button name="retour" class="btn btn-secondary">Retour</button>
                </form>
            </div>
        <div class="container">
                <h1>
                    WiseTreeBank - Contact
                </h1>
                <div class='chat-box'>
                    <?php 
                    
                    if(isset($_POST['submit'])){
                        messagerequest($bdd);
                    }
                    ?>
                </div>
                <?php
                echo '<form method="POST" action="contact.php" >';
                echo '<div class="form-group">';
                echo '<input type="text" name="usermessage" class="form-control" placeholder="Votre message"><br>';
                echo '<input type="datetime-local" name="rdvtime" class="form-control" min="' . date('y-m-d h:i') . '" > ';
                echo '<button name="submit" class="btn btn-primary">Envoyer le message</button>';
                echo '</div>';
                echo '</form>';
                displaymessage($bdd);
                ?>
            </div>
        </div>
    </body>
</html>
