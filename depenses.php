<?php
session_start();

//$bdd = new PDO('mysql:host=10.206.237.9;dbname=wisebankdb;charset=utf8', 'phpmyadmin', 'carriat'); // Reseau local VM
$bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','wisetree');

try{
    $bdd;

}catch(exception $e){
    die('Erreur connexion: '. $e->getMessage());
}

if(!isset($_SESSION['userid']))
    {
        header('Location: index.php');
    }

if(!isset($_SESSION['compteactuel'])){
    header('Location: lescomptes.php');
}

if(isset($_POST['comptes'])){
    header('Location: compte.php');
}

if(isset($_POST['lescomptes'])){
    unset($_SESSION['compteactuel']);
    unset($_SESSION['compteactuelnom']);
    header('Location: lescomptes.php');
}

if(isset($_POST['Deco'])){
    header('Location: logout.php');
}


function checkcomptes($bdd){

    $compte = $_SESSION['compteactuelnom'];
    $requetedata = "SELECT * FROM comptes WHERE comptenom = ?";
    $requetedata = $bdd->prepare($requetedata); 
    $requetedata->execute(array($compte));
    $data = $requetedata->fetch();
    echo "<h3>Votre solde: <u>" . $data['solde'] . "€</u></h4>";
    echo "<h3>IBAN: " . htmlspecialchars(strtoupper($data['RIB'])) . "</h3>";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="icon" type="image/jpg" href="logo.jpg" />
        <title>Ma banque</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <style>
            h1, h2{
            text-align: center;
            }
            .confirm{
                background-color: green;
                color: black;
            }

            .erreur{
                color: red;
            }

            .nav_bar{
                display: flex;
            }
            
            /* Popup container - can be anything you want */
            .popup {
            position: relative;
            display: inline-block;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            }

            /* The actual popup */
            .popup .popuptext {
            visibility: hidden;
            width: 160px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px 0;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            transform : translateY(200%);
            left: 50%;
            margin-left: -80px;
            }


            /* Toggle this class - hide and show the popup */
            .popup .show {
            visibility: visible;
            -webkit-animation: fadeIn 1s;
            animation: fadeIn 1s;
            }

            /* Add animation (fade in the popup) */
            @-webkit-keyframes fadeIn {
            from {opacity: 0;} 
            to {opacity: 1;}
            }

            @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity:1 ;}
            }
        </style>   

        <link rel="stylesheet" type="text/css" href="css/style.css">    
    </head>
    <body>
        <div class="navbar-nav">
            <form method="POST" action="depenses.php">
                <button name="comptes" class="btn btn-secondary">Retour</button>
                <button name="lescomptes" class="btn btn-secondary">Vos comptes</button>
                <button name="Deco" class="btn btn-secondary">Deconnexion</button>
            </form>
        </div>
        <div class='container'>
        <?php if(isset($_SESSION['usermessage'])){
                echo $_SESSION['usermessage'];
            }?>
            <div class='form-container'>
                <h1><b>Mes dépenses</b></h1>
                <h2><?php 
                checkcomptes($bdd);
                ?></h2>
                <h3>Effectuer un virement</h3>
                <form action="traitement.php" method="post">
                    <input type="text" id="virement" name="destinataire" placeholder="RIB du destinataire" class="form-control" required><br><br>
                    <input type="text" id="virement" name="virement" placeholder="Somme" class="form-control" required><br><br>
                    <button name="send" class="btn btn-primary">Envoyer</button>
                </form>
            </div>
        </div>
    </body>
</html>
    