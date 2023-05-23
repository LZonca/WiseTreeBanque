<?php
session_start();

if($_SERVER['SERVER_NAME'] == "127.0.0.1"){
    $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');
}elseif($_SERVER['SERVER_NAME'] == "10.206.237.111" || $_SERVER['SERVER_NAME'] == "10.206.237.112" || $_SERVER['SERVER_NAME'] == "www.wisetreebanque.sio"){
    $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root', 'wisetree');
}elseif($_SERVER['SERVER_NAME'] == "zonca.alwaysdata.net"){
    $bdd = new PDO('mysql:host=mysql-zonca.alwaysdata.net;dbname=zonca_wisebankdb;charset=utf8', 'zonca_adminbank', 'wisetreebanque');
}
// var_dump($_SESSION); // A enlever si nécéssaire

try{
    $bdd;

    }catch(exception $e){
        die('Erreur: '. $e->getMessage());
    }

if(!isset($_SESSION['userid'])){
        header('Location: connexion');
    }

if(!isset($_SESSION['compteactuel']) && !isset($_SESSION['compteactuelnom'])){
    header('Location: connexion');
}

if(isset($_POST['comptes'])){
    header('Location: accueil');
}

if(isset($_POST['lescomptes'])){
    unset($_SESSION['compteactuel']);
    header('Location: accueil');
}

if(isset($_POST['Deco'])){
    header('Location: logout');
}

if(isset($_POST['virement'])){
    header('Location: votre-historique');
}

if(isset($_POST['credits'])){
    header('Location: vos-credits');
}

unset($_SESSION['usermessage']);

    function nomrequest($bdd)
    {
        $user = $_SESSION['userid'];
        $requete = "SELECT nom, prenom FROM users WHERE userid = ?;";
        $requete = $bdd->prepare($requete); 
        $requete->execute(array($user));
        $data = $requete->fetch();
        echo "<h1>Bienvenue " . htmlspecialchars(strtoupper($data['prenom'])) . " " . htmlspecialchars(strtoupper($data['nom'])) . " !</h1>";
    }

    

    function checkcomptes($bdd){
        $compte = $_SESSION['compteactuel'];
        $comptenom = $_SESSION['compteactuelnom'];
        $requetedata = "SELECT * FROM comptes WHERE RIB = ?";
        $requetedata = $bdd->prepare($requetedata); 
        $requetedata->execute(array($compte));
        $data = $requetedata->fetch();
        echo "<h2>Compte " . $data['comptenom'] . "</h2>";
        echo "<h5>Votre solde: <u>" . $data['solde'] . "€</u></h5>";
        echo "<h3>Découvert autorisé : " . htmlspecialchars(strtoupper($data['decouvert_autorise'])) . " €</u></h3>";
        echo "<h2>RIB: </h2>";
        echo "<h2>IBAN: " . htmlspecialchars(strtoupper($data['RIB'])) . "</h2>";
        echo "<h3>BIC: " . htmlspecialchars(strtoupper($data['BIC'])) . "</h3>";
        echo "<form action='votre-compte' method='POST'>";
        echo "<button name='virement' class='btn btn-primary'>Virement</button> 
        <button name='credits' class='btn btn-primary'>Vos crédits</button>";
        echo "</form>";
        echo "</div>";
        }
    //var_dump($_SESSION);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mon Compte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="icon" type="image/jpg" href="img/logo.jpg" />
    <style>
        h1, h2{
        text-align: center;
    }
    </style>   
</head>

<body>
    <div class="navbar-nav">
        <form method="POST" action="votre-compte">
        <button name="comptes" class="btn btn-primary">Retour</button>
            <button name="lescomptes" class="btn btn-secondary">Vos comptes</button>
            <button name="Deco" class="btn btn-secondary">Deconnexion</button>
        </form>
    </div>
    <div class="container">
    <?php
    // $bdd = new PDO('mysql:host=10.206.237.9;dbname=wisebankdb;charset=utf8', 'phpmyadmin', 'carriat');
    
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
                    //checkcomptes($bdd);

                ?>

            </p>
        </div>
    </div>
</body>
</html>