<?php
    session_start();
    if($_SERVER['SERVER_NAME'] == "127.0.0.1"){
        $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');
    }elseif($_SERVER['SERVER_NAME'] == "10.206.237.9"){
        $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root', 'wisetree');
    }elseif($_SERVER['SERVER_NAME'] == "zonca.alwaysdata.net"){
        $bdd = new PDO('mysql:host=mysql-zonca.alwaysdata.net;dbname=zonca_wisebankdb;charset=utf8', 'zonca_adminbank', 'wisetreebanque');
    }
    $sql = "SELECT * FROM users WHERE userid = ?";
    $request = $bdd->prepare($sql);
    $request->execute(array($_SESSION['userid']));
    $user = $request->fetch();
    
    if($user['permissions'] < 3){
        header('Location: accueil');
    }
    if(!isset($_SESSION['userid'])){
        header("Location: connexion");
    }
    
    if($_SERVER['SERVER_NAME'] != "10.206.237.9"){
        header('Location: contact');
    }/*else{
    require_once('/var/www/html/PHPMailer-master/src/PHPMailerAutoload.php');     
        try{
            require 'D:/wamp/www/PHPMailer/src/PHPMailerAutoload.php';
        }catch (exception $e) {
            die('Erreur mail: ' . $e->getMessage());
        }
    }*/

    //$mail = new PHPMailer();
    $mail->setFrom($_POST['from'], 'Expéditeur');
    $mail->addAddress($_POST['conseiller'], 'Destinataire');
    $mail->Subject = $_POST['objet'];
    $mail->Body = $_POST['body'];
    $mail->isSMTP();
    $mail->Host = '10.206.237.113';
    $mail->Port = 25;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    

    function checkconseillers($bdd)
    {
        $requetedata = "SELECT * FROM users WHERE permissions > 2";
        $requetedata = $bdd->prepare($requetedata);
        $requetedata->execute();

        echo "<select name='conseiller' class='form-control' required>";
        echo "<option value = '' selected></option>";
        while ($data = $requetedata->fetch()) {
            echo "<option value = " . $data['mail'] . "\">" . $data['nom'] . " " . $data['prenom'] . "</option>";
        }
        echo "</select><br>";
    }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/jpg" href="logo.jpg" />
        <title>Wise Tree Banque - Support</title>
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
                <a href="contact" class="btn btn-secondary">Retour</button>
            </form>
        </div>
        <div class="container">
            <h1>WiseTreeBank - Support</h1>
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
            <form method="POST" action="contact-mail">
                <div class="form-group">
                    <label for='usermessage'>Votre adresse mail: </label><br>
                    <input type="text" name="nom" class="form-control" placeholder="Votre adresse mail"><br>
                    <label for='rdvtime'>Objet du mail: </label><br>
                    <input type="text" name="objet" class="form-control" placeholder="Objet du mail"><br>
                    <label for='body'>Corps du mail: </label><br>
                    <input type="text" name="body" class="form-control" placeholder="Corps du mail"><br>
                    <?php checkconseillers($bdd); ?>
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