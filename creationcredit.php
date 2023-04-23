<?php
session_start();
try{
    //$bdd = new PDO('mysql:host=10.206.237.9;dbname=wisebankdb;charset=utf8', 'phpmyadmin', 'carriat'); // Reseau local VM
    $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');  //Localhost 

}catch(exception $e){
    die('Erreur: '. $e->getMessage());
}

if(!isset($_SESSION['userid'])){
    header('Location: index.php');
}
if(isset($_POST['compteactuel'])){
    $_SESSION['compteactuel'] = $_POST['compteactuel'];
}

/*if(!isset($_POST['nompret']))
{
    if(!isset($_POST['prenompret']))
    {
        header('Location: controlpannel.php');
    }
}*/


?>

<!DOCTYPE HTML>
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
    <header>
    <div class="navbar-nav">
        <form method="POST" action="controlpannel.php">
            <button name="comptes" class="btn btn-primary">Retour</button>
        </form>
    </div>
    </header>

    <div class="container">
        <h1>Création de crédit</h1>
        <?php 
            echo '<h2>Compte " '.  $_SESSION['compteactuel'] .'" de ' . $_SESSION['prenompret'] . " " . $_SESSION['nompret'] . "</h2>";
            echo '<br/>';
            if(isset($_SESSION['usermessage'])){
                echo $_SESSION['usermessage'];
            }
            
        ?>
        <form action='traitement.php' method='POST'>
            <label for="raisonpret">Raison du crédit</label><br>
            <input type='text' name='raisonpret' class='form-control'><br>
            <label for="raisonpret">Valeur du crédit</label><br>
            <input type='text' name='valeur' pattern="[0-9]+" class='form-control' required><br>
            <label for="raisonpret">Taux d'interêt</label><br>
            <input type='text' name='interet' pattern="[0-9,.]+" class='form-control' required><br>
            <label for="raisonpret">Échéance</label><br>
            <input type='date' name='echeance' class='form-control' required><br>
            <label for="prelevement">Periodicité de prelevement</label><br>
            <select name='prelevement'>
                <option value='Journalier'>Journalier</option>
                <option value='Hebdomadaire'>Hebdomadaire</option>
                <option value='Mensuel'>Mensuel</option>
                <option value='Trimestriel'>Trimestriel</option>
                <option value='Annuem'>Annuel</option>
            </select><br>
            <button name="createpret" class='btn btn-primary'>Créer le crédit</button>
        </form>
        
    </div>
</body>