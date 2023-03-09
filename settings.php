<?php
session_start();
if(!isset($_SESSION))
{
    header('Location: index.php');
}

if(isset($_POST['Deco'])){
    header('Location: logout.php');
}

if(isset($_POST['parametres'])){
    header('Location: settings.php');
}


function updatepass($bdd){
    try{
    $bdd;

    }catch(exception $e){
        die('Erreur: '. $e->getMessage());
    }
    $user = $_SESSION['userid'];
    $pass = $_POST['mdp'];
    $requetemdp = "SELECT * FROM users WHERE userid = ?;";
    $requetemdp = $bdd->prepare($requetemdp); 
    $requetemdp->execute(array($user));
    $data = $requetemdp->fetch();
    
    if(($_POST['mdpchange'] == $_POST['mdprepeat']) && strlen($_POST['mdpchange']) == 6)
    {
        if(password_verify($pass, $data['password'])){
            $newpassword = password_hash($_POST['mdpchange'], PASSWORD_DEFAULT);
            $requete = "UPDATE users SET password = ? WHERE userid = ?;";
            $requete = $bdd->prepare($requete); 
            $requete->execute(array($newpassword, $user));
            echo "<p class='confirm'> Mot de passe changé !<p>";
        }else{
            echo "<div class = 'error_box'>";
            echo "<p class='error'>Mauvais mot de passe<p>";
            echo "</div>";
        }
    }
    else{
        echo "<div class = 'error_box'>";
        echo "<p class='error'>Les mots de passe ne correspondent pas ou n'a pas 6 caractères<p>";
        echo "</div>";
    }
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
                <div class="navbar-nav">
                    <form method="POST" action="lescomptes.php">
                        <button name="Deco" class="btn btn-secondary">Deconnexion</button>
                        <button name="lescomptes" class="btn btn-secondary">Retour</button>
                        <?php 
                        $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root',''); // Localhost
                        //$bdd = new PDO('mysql:host=10.206.237.9;dbname=wisebankdb;charset=utf8', 'phpmyadmin', 'carriat'); // Reseau local VM
                        ?>
                    </form>
                </div>
            <div class="container">
                <h2><u>Vos paramètres:</u></h2>
                    <form method='POST' action='settings.php'>
                        <label for="mdp">Mot de passe actuel</label><br><br>
                        <input type="password" name="mdp" placeholder="Mot de passe actuel" required><br><br>
                        <label for="mdpchange">Nouveau mot de passe</label><br><br>
                        <input type="password" name="mdpchange" placeholder="Nouveau mot de passe" required><br><br>
                        <label for="mdprepeat">Répéter le nouveau mot de passe</label><br><br>
                        <input type="password" name="mdprepeat" placeholder="Répéter le nouveau mot de passe" required><br><br>
                        <button name='changemdp' class="btn btn-primary">Changer de mot de passe</button>
                    </form>
                    <?php
                        if(isset($_POST['changemdp']))
                        {
                            updatepass($bdd);
                        }
                    ?>
                </div>
            </div>
        </div> 
    </body>
</html>