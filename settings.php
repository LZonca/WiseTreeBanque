<?php
session_start();
if(!isset($_SESSION))
{
    header('Location: index.php');
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Wise Tree Banque</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="icon" type="image/jpg" href="logo.jpg" />
        <style>
        </style>
    </head>
    <body>
        <header>
            <div class="nav_bar">
                <form method="POST" action="logout.php">
                    <button name="Deco">Deconnexion</button>
                </form>
                <form method="POST" action="lescomptes.php">
                    <button name="lescomptes">Retour</button>
                </form>
                <form method="POST" action="lescomptes.php">
                    <?php 
                    $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root',''); // Localhost
                    //$bdd = new PDO('mysql:host=;dbname=wisebankdb;charset=utf8', 'phpmyadmin', 'carriat'); // Reseau local VM
                    ?>
                </form>
            </div>
           
        </header>
        <h2><u>Vos paramètres:</u></h2>
        <form>
            <label for="mdp">Mot de passe actuel</label><br><br>
            <input type="password" name="mdp" placeholder="Mot de passe actuel" required><br><br>
            <label for="mdpchange">Nouveau mot de passe</label><br><br>
            <input type="password" name="mdpchange" placeholder="Nouveau mot de passe" required><br><br>
            <label for="mdprepeat">Répéter le nouveau mot de passe</label><br><br>
            <input type="password" name="mdprepeat" placeholder="Répéter le nouveau mot de passe" required><br><br>
            <button name='changemdp' class="btn btn-primary">Changer de mot de passe</button>
        </form>
        </div>
</div> 

    </body>

</html>