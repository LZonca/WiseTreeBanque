<?php
session_start();
if(isset($_POST['submit'])){
    $_SESSION['popup'] = true;
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}



function checkcomptes($bdd){
    try{
        $bdd;

    }catch(exception $e){
        die('Erreur nom compte: '. $e->getMessage());
    }
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
        try{
            $bdd;

        }catch(exception $e){
            die('Erreur solde: '. $e->getMessage());
        }
        $user = $_SESSION['userid'];
        $requetesolde = "SELECT * FROM comptes WHERE userid = ?";
        $requetesolde = $bdd->prepare($requetesolde); 
        $requetesolde->execute(array($user));
        $solde = $requetesolde->fetch();
        return $solde['RIB'];
    }

    function transfertrequete($bdd){
        try{
            $bdd;
    
        }catch(exception $e){
            die('Erreur transaction: '. $e->getMessage());
        }
        date_default_timezone_set('Europe/Paris');
        $date = date('d-m-y h:i:s');
        $envoyeur = RIBrequest($bdd);
        $destinataire = $_POST['destinataire'];
        $valeur = $_POST['virement'];
        $requetedata = 'INSERT INTO virements VALUES (NULL, ?, ?, ?, ?)';
        $requetedata = $bdd->prepare($requetedata); 
        $requetedata->execute(array($destinataire, $envoyeur, $valeur, $date));
        echo "Effectué ! <br>";
        //echo "Données transmises: " . $destinataire . ", " . $envoyeur . ", " . $valeur . ", " . $date;
        

    }

    function checkvirement($bdd)
            {
                if (isset($_POST['send'])) {
                    if (isset($_POST['virement']) && $_POST['virement'] != '' && $_POST['virement'] >= 0 && is_numeric($_POST['virement'])) {
                        if (isset($_POST['destinataire']) && $_POST['destinataire'] != '' && strlen($_POST['destinataire']) >= 27) {
                            $err = 0;
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
    <style>
        h1, h2{
        text-align: center;
        }
        .confirm{
            color: green;
        }

        .erreur{
            color: red;
        }

        .nav_bar{
            display: flex;
        }

        .confirm{
            color: green;
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
        <header>
            <div class="nav_bar">
                <form method="POST" action="compte.php">
                    <button name="lescomptes">Retour</button>
                </form>
                <form method="POST" action="lescomptes.php">
                    <button name="lescomptes">Vos comptes</button>
                </form>
                
                <form method="POST" action="logout.php">
                    <button name="Deco">Deconnexion</button>
                </form>
            </div>
            
        </header>
        <div class='form-container'>
            <h1><b>Mes dépenses</b></h1>
            <h2><?php 
            $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');
            checkcomptes($bdd);
            ?></h2>
            <h3>Effectuer un virement</h3>
            <form action="depenses.php" method="post">
                <input type="text" id="virement" name="destinataire" placeholder="RIB du destinataire"><br><br>
                <input type="text" id="virement" name="virement" placeholder="Somme"><br><br>
                <button name="send">Envoyer</button>
            </form>
        

    </form>
    <?php
    

    function checkvirement($bdd)
    {
        $confirm = "Virement effectué";
        if (isset($_POST['send'])) {
            if (isset($_POST['virement']) && $_POST['virement'] != '' && strlen($_POST['virement']) >= 0) {
                if (isset($_POST['destinataire']) && $_POST['destinataire'] != '' && strlen($_POST['destinataire']) == 27) {
                    $err = 0;
                    echo '<script> myFunction(); <script>';
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
    <?php
        $confirm = "Virement effectué";
        if(isset($_POST['send']))
        {
            if(checkvirement() == 0)
            {
                echo "<p class='confirm'> $confirm <p>";
            }
            elseif(checkvirement() == 1)
            {
                echo '<div class="error_box">';
                    echo '<p class ="error">Veuillez entrer le RIB du destinataire.</p>';
                echo '</div>';
            }
            elseif(checkvirement() == 2)
            {
                echo '<div class="error_box">';
                    echo '<p class ="error">Veuillez entrer une somme à transférer.</p>';
                echo '</div>';
            }
        }
    ?>

</body>
</html>
    