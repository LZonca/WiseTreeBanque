<?php
session_start();

//$bdd = new PDO('mysql:host=10.206.237.9;dbname=wisebankdb;charset=utf8', 'phpmyadmin', 'carriat'); // Reseau local VM
$bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');

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
}

if(isset($_POST['Deco'])){
    header('Location: logout.php');
}

function verifdest($bdd, $dest){

    $destrequete = "SELECT COUNT(*) FROM comptes WHERE RIB = ?";
    $destrequete= $bdd->prepare($destrequete);
    $destrequete->execute(array($dest));
    $countdest = $destrequete->fetchColumn();
    if($countdest == 1)
    {
        return true;
    }else{
        return false;
    }
}

function checkcomptes($bdd){

    $compte = $_SESSION['compteactuel'];
    $requetedata = "SELECT * FROM comptes WHERE comptenom = ?";
    $requetedata = $bdd->prepare($requetedata); 
    $requetedata->execute(array($compte));
    $data = $requetedata->fetch();
    echo "<h3>Votre solde: <u>" . $data['solde'] . "€</u></h4>";
    echo "<h3>RIB: " . htmlspecialchars(strtoupper($data['RIB'])) . "</h3>";
    }

    function RIBrequest($bdd)
    {
        $user = $_SESSION['userid'];
        $requetesolde = "SELECT * FROM comptes WHERE userid = ?";
        $requetesolde = $bdd->prepare($requetesolde); 
        $requetesolde->execute(array($user));
        $solde = $requetesolde->fetch();
        return $solde['RIB'];
    }

    function transfertrequete($bdd){
        date_default_timezone_set('Europe/Paris');
        $date = date('d-m-y h:i:s');
        $envoyeur = RIBrequest($bdd);
        $destinataire = $_POST['destinataire'];
        $valeur = $_POST['virement'];
        
        $requetedata = 'INSERT INTO virements VALUES (NULL, ?, ?, ?, ?)';
        $requetedata = $bdd->prepare($requetedata); 
        $requetedata->execute(array($destinataire, $envoyeur, $valeur, $date));
        echo "Effectué ! <br>";

        $destinatairesolde = "SELECT solde FROM comptes WHERE RIB = ?;";
        $destinatairesolde = $bdd->prepare($destinatairesolde); 
        $destinatairesolde->execute(array($destinataire));
        $soldedest = $destinatairesolde->fetch();

        $usersolde = "SELECT solde FROM comptes WHERE RIB = ?;";
        $usersolde = $bdd->prepare($usersolde); 
        $usersolde->execute(array(RIBrequest($bdd)));
        $soldeexpe = $usersolde->fetch();

        $destinatairerequete = "UPDATE comptes SET solde = ? WHERE RIB = ?;";
        $destinatairerequete  = $bdd->prepare($destinatairerequete); 
        $destinatairerequete ->execute(array($soldedest + $valeur, $destinataire));
        
        $userrequete = "UPDATE comptes SET solde = ? WHERE RIB = ?;";
        $userrequete  = $bdd->prepare($userrequete); 
        $userrequete ->execute(array($soldeexpe['solde'] - $valeur, RIBrequest($bdd)));
        //echo "Données transmises: " . $destinataire . ", " . $envoyeur . ", " . $valeur . ", " . $date;

    }
    function checkvirement($bdd)
            {
                if (isset($_POST['send'])) {
                    if (isset($_POST['virement']) && $_POST['virement'] != '' && $_POST['virement'] >= 0 && is_numeric($_POST['virement'])) {
                        if (isset($_POST['destinataire']) && $_POST['destinataire'] != '' && strlen($_POST['destinataire']) >= 24 ) {
                            if($_POST['destinataire'] != RIBrequest($bdd))
                            {
                                if(verifdest($bdd, $_POST['destinataire']))
                                {
                                    $err = 0;
                                }else{
                                    $err = 4;
                                }
                                
                            }else{
                                $err = 3;
                            }
                            
                        } else {
                            $err = 1;
                        }
                    }else
                    {
                        $err = 2;
                    }
                    return $err;
                }  
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
            <div class='form-container'>
                <h1><b>Mes dépenses</b></h1>
                <h2><?php 
                checkcomptes($bdd);
                ?></h2>
                <h3>Effectuer un virement</h3>
                <form action="depenses.php" method="post">
                    <input type="text" id="virement" name="destinataire" placeholder="RIB du destinataire" class="form-control" required><br><br>
                    <input type="text" id="virement" name="virement" placeholder="Somme" class="form-control" required><br><br>
                    <button name="send" class="btn btn-primary">Envoyer</button>
                </form>
            
                <?php
                $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');
                
                ?>
                <?php
                    $confirm = "Virement effectué";
                    if(isset($_POST['send']))
                    {
                        switch(checkvirement($bdd))
                        {
                            case 0:
                            {
                                    echo "<div class='confirm'>";
                                    transfertrequete($bdd);
                                    echo '<script> myFunction(); <script>';
                                    echo "<p class='error'><b>Virement effectué!</b><p>";
                                echo '</div>';
                            }
                            case 1:
                            {
                                echo '<div class="error_box">';
                                echo '<p class ="error"><b>Veuillez entrer le RIB du destinataire.</b></p>';
                                echo '</div>';
                            }
                            case 2:
                            {
                                echo '<div class="error_box">';
                                echo '<p class ="error"><b>Veuillez entrer une somme valide à transférer.<br> (La somme ne peut pas etre négative !)</b></p>';
                                echo '</div>';
                            }
                            case 3:
                            {
                                echo '<div class="error_box">';
                                echo '<p class ="error"><b>Vous ne pouvez pas envoyer de l\'argent vers le compte d\'origine</b></p>';
                                echo '</div>';
                            }
                            case 4:
                            {
                                echo '<div class="error_box">';
                                echo '<p class ="error"><b>Compte inconnu</b></p>';
                                echo '</div>';
                            }
                        }
                    }
                ?>
            </div>
        </div>
    </body>
</html>
    