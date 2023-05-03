<?php
session_start();
//$bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');  //Localhost 
$bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','wisetree');

//$bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root', ''); // Localhost


if (!isset($_SESSION)) {
    header('Location: connexion');
}

if (isset($_POST['Deco'])) {
    header('Location: logout');
}

if (isset($_POST['parametres'])) {
    header('Location: parametres');
}
if(isset($_POST['lescomptes'])){
    header('Location: accueil');
}

try {
    $bdd;
} catch (exception $e) {
    die('Erreur: ' . $e->getMessage());
}
function updatepass($bdd)
{
    $user = $_SESSION['userid'];
    $pass = $_POST['mdp'];
    $requetemdp = "SELECT * FROM users WHERE userid = ?;";
    $requetemdp = $bdd->prepare($requetemdp);
    $requetemdp->execute(array($user));
    $data = $requetemdp->fetch();

    if (($_POST['mdpchange'] == $_POST['mdprepeat']) && strlen($_POST['mdpchange']) == 6) {
        if ($data) {
            if (password_verify($pass, $data['password'])) {
                $newpassword = password_hash($_POST['mdpchange'], PASSWORD_DEFAULT);
                $requete = "UPDATE users SET password = ? WHERE userid = ?;";
                $requete = $bdd->prepare($requete);
                $requete->execute(array($newpassword, $user));
                $_SESSION['usermessage'] = "<p class='alert alert-success'>Mot de passe changé !</p>";
            } else {
                $_SESSION['usermessage'] = "<p class='alert alert-danger'>>Mauvais mot de passe</p>";
            }
        } else {
            $_SESSION['usermessage'] = "<p class='alert alert-danger'>Les mots de passe ne correspondent pas ou n'a pas 6 caractères</p>";
        }
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Wise Tree Banque - Paramètres</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="icon" type="image/jpg" href="logo.jpg" />
        <style>
        </style>
    </head>
        <body>
                <div class="navbar-nav">
                    <form method="POST" action="accueil">
                        <button name="Deco" class="btn btn-secondary">Deconnexion</button>
                        <button name="lescomptes" class="btn btn-secondary">Retour</button>
                    </form>
                </div>
            <div class="container">
                <h2><u>Vos paramètres:</u></h2>
                    <form method='POST' action='parametres'>
                        <label for="mdp">Mot de passe actuel</label><br><br>
                        <input type="password" name="mdp" placeholder="Mot de passe actuel" class="form-control" required><br><br>
                        <label for="mdpchange">Nouveau mot de passe</label><br><br>
                        <input type="password" name="mdpchange" placeholder="Nouveau mot de passe" class="form-control" required><br><br>
                        <label for="mdprepeat">Répéter le nouveau mot de passe</label><br><br>
                        <input type="password" name="mdprepeat" placeholder="Répéter le nouveau mot de passe" class="form-control" required><br><br>
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