<?php
session_start();
// var_dump($_SESSION['userid']); // A enlever si nécéssaire

if(!isset($_SESSION['userid']))
    {
        header('Location: index.php');
    }

if(!isset($_SESSION['compteactuel'])){
    header('Location: index.php');
}

    function nomrequest($bdd)
    {
        try{
        $bdd;

        }catch(exception $e){
            die('Erreur: '. $e->getMessage());
        }
        $user = $_SESSION['userid'];
        $requete = "SELECT nom, prenom FROM users WHERE userid = ?;";
        $requete = $bdd->prepare($requete); 
        $requete->execute(array($user));
        $data = $requete->fetch();
        echo "<h1>Bienvenue " . htmlspecialchars(strtoupper($data['prenom'])) . " " . htmlspecialchars(strtoupper($data['nom'])) . " !</h1>";
    }

    

    function checkcomptes(){
        try{
            //$bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root',''); // Localhost
            $bdd = new PDO('mysql:host=;dbname=wisebankdb;charset=utf8', 'phpmyadmin', 'carriat'); // Reseau local VM
        }catch(exception $e){
            die('Erreur nom compte: '. $e->getMessage());
        }
        $compte = $_SESSION['compteactuel'];
        $requetedata = "SELECT * FROM comptes WHERE comptenom = ?";
        $requetedata = $bdd->prepare($requetedata); 
        $requetedata->execute(array($compte));
        $data = $requetedata->fetch();
        echo "<h3>Compte " . $data['comptenom'] . "</h3>";
        echo "<h5><a href='depenses.php'>Votre solde: <u>" . $data['solde'] . "€</a></u></h5>";
        echo "<h3>Découvert autorisé : " . htmlspecialchars(strtoupper($data['decouvert_autorise'])) . " €</u></h3>";
        echo "<h3>RIB: " . htmlspecialchars(strtoupper($data['RIB'])) . "</h3>";
        echo "</div>";
        }

    function decouvertrequest()
    {
        try{
        $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');

        }catch(exception $e){
            die('Erreur: '. $e->getMessage());
        }
        $user = $_SESSION['userid'];
        $requete = "SELECT * FROM comptes WHERE userid = (SELECT id FROM users WHERE userid = ?);";
        $requete = $bdd->prepare($requete); 
        $requete->execute(array($user));
        $data = $requete->fetch();
        echo "<h3>Découvert autorisé : " . htmlspecialchars(strtoupper($data['decouvert_autorise'])) . " €</u></h3>";
    }
    function solderequest($bdd)
    {
        try{
            $bdd;

        }catch(exception $e){
            die('Erreur solde: '. $e->getMessage());
        }
        $user = $_SESSION['userid'];
        $requetesolde = "SELECT solde FROM comptes WHERE userid = ?";
        $requetesolde = $bdd->prepare($requetesolde); 
        $requetesolde->execute(array($user));
        $solde = $requetesolde->fetch();
        echo "<h4>Votre solde: <u>" . $solde['solde'] . " €</u></h4>";
    }
    //var_dump($_SESSION);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ma banque</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="icon" type="image/jpg" href="logo.jpg" />
    <style>
        h1, h2{
        text-align: center;
    }
    </style>   
</head>

<body>
    <header>
        <div class="nav_bar">
            <form method="POST" action="lescomptes.php">
                <button name="lescomptes">Vos comptes</button>
            </form>
            <form method="POST" action="logout.php">
                <button name="Deco">Deconnexion</button>
            </form>
        </div>
        
    </header>
    <?php
    $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');
    nomrequest($bdd);
    ?>
    <h2><u>Bienvenue sur la Wise Tree Bank</u></h2>
    <div class="data-container">
        <h3><u>Votre compte</u></h3>
        <p>
            <?php
            //$bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');
            if(isset($_POST['"'. checkcomptes($bdd) . '"']))
                checkcomptes($bdd);
            ?>
        </p>
    </div>

</body>
</html>