<?php
session_start();
// var_dump($_SESSION['userid']); // A enlever si nécéssaire
if(isset($_POST['Deco']))
    {
        unset($_SESSION['userid']);
    }

if(!isset($_SESSION['userid']))
    {
        header('Location: index.php');
    }

    function nomrequest()
    {
        try{
        $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');

        }catch(exception $e){
            die('Erreur: '. $e->getMessage());
        }
        $user = $_SESSION['userid'];
        $requete = "SELECT nom, prenom FROM users WHERE id = ?;";
        $requete = $bdd->prepare($requete); 
        $requete->execute(array($user));
        $data = $requete->fetch();
        echo "<h1>Bienvenue " . htmlspecialchars(strtoupper($data['prenom'])) . " " . htmlspecialchars(strtoupper($data['nom'])) . " !</h1>";
    }

    function ribrequest()
    {
        try{
        $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');

        }catch(exception $e){
            die('Erreur: '. $e->getMessage());
        }
        $user = $_SESSION['userid'];
        $requete = "SELECT RIB FROM comptes WHERE userid = ?;";
        $requete = $bdd->prepare($requete); 
        $requete->execute(array($user));
        $data = $requete->fetch();
        echo "<h3>RIB: " . htmlspecialchars(strtoupper($data['RIB'])) . "</h3>";
    }

    function decouvertrequest()
    {
        try{
        $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');

        }catch(exception $e){
            die('Erreur: '. $e->getMessage());
        }
        $user = $_SESSION['userid'];
        $requete = "SELECT decouvert_autorise FROM comptes WHERE userid = ?;";
        $requete = $bdd->prepare($requete); 
        $requete->execute(array($user));
        $data = $requete->fetch();
        echo "<h3>Découvert autorisé : " . htmlspecialchars(strtoupper($data['decouvert_autorise'])) . " €</u></h3>";
    }
    function solderequest()
    {
        try{
            $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');

        }catch(exception $e){
            die('Erreur solde: '. $e->getMessage());
        }
        $user = $_SESSION['userid'];
        $requetesolde = "SELECT solde FROM comptes WHERE userid = ?";
        $requetesolde = $bdd->prepare($requetesolde); 
        $requetesolde->execute(array($user));
        $solde = $requetesolde->fetch();
        echo "<h5>Votre solde: <u>" . $solde['solde'] . " €</u></h5>";
    }
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
            <form method="POST" action="index.php">
                <button name="Deco">Deconnexion</button>
            </form>
        </div>
        
    </header>
    <?php
    nomrequest();
    echo "<h2>Compte n° " . htmlspecialchars($_SESSION['userid']). "</h2>"; ?>
    <h2><u>Bienvenue sur la Wise Tree Bank</u></h2>
    <div class="data-container">
        <h3><u>Votre compte</u></h3>
        <p>
            <a href="dépenses.php"><?php solderequest();?></a>    
            <?php decouvertrequest();?>
        </p>
        <?php
            ribrequest();
        ?>
    </div>

</body>
</html>