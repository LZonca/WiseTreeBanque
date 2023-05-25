<?php
session_start();
if ($_SERVER['SERVER_NAME'] == "127.0.0.1") {
    $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root', '');
} elseif ($_SERVER['SERVER_NAME'] == "10.206.237.111" || $_SERVER['SERVER_NAME'] == "10.206.237.112" || $_SERVER['SERVER_NAME'] == "www.wisetreebanque.sio") {
    $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root', 'wisetree');
} elseif ($_SERVER['SERVER_NAME'] == "zonca.alwaysdata.net") {
    $bdd = new PDO('mysql:host=mysql-zonca.alwaysdata.net;dbname=zonca_wisebankdb;charset=utf8', 'zonca_adminbank', 'wisetreebanque');
}

if (!isset($_SESSION)) {
    header('Location: connexion');
}

if (isset($_POST['Deco'])) {
    header('Location: deconnexion');
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

    if ($_POST['mdpchange'] == $_POST['mdprepeat']){
        if(strlen($_POST['mdpchange']) == 6) {
            if ($data) {
                if(!password_verify($_POST['mdpchange'], $data['password'])){
                    if (password_verify($pass, $data['password'])) {
                        $newpassword = password_hash($_POST['mdpchange'], PASSWORD_DEFAULT);
                        $requete = "UPDATE users SET password = ? WHERE userid = ?;";
                        $requete = $bdd->prepare($requete);
                        $requete->execute(array($newpassword, $user));
                        $_SESSION['usermessage'] = "<p class='alert alert-success'>Mot de passe changé !</p>";
                    } else {
                        $_SESSION['usermessage'] = "<p class='alert alert-danger'>Mauvais mot de passe</p>";
                    }
                }else{
                    $_SESSION['usermessage'] = "<p class='alert alert-danger'>Le nouveau mot de passe ne peut pas être le même que l'ancien...</p>";
                }
            } 
        }else {
                $_SESSION['usermessage'] = "<p class='alert alert-warning'>Le mot de passe n'a pas 6 caractères.</p>";
            }
        }else {
            $_SESSION['usermessage'] = "<p class='alert alert-warning'>Les mots de passe ne correspondent pas.</p>";
        }
    }

if(isset($_POST['changemdp']))
    {
        updatepass($bdd);
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
                <?php if (isset($_SESSION['usermessage'])) {
                    echo $_SESSION['usermessage'];
                    unset($_SESSION['usermessage']);
                } ?>
                    <form method='POST' action='parametres'>
                        <label for="mdp">Mot de passe actuel</label><br><br>
                        <input type="password" name="mdp" placeholder="Mot de passe actuel" class="form-control" required><br><br>
                        <label for="mdpchange">Nouveau mot de passe</label><br><br>
                        <input type="password" name="mdpchange" placeholder="Nouveau mot de passe" class="form-control" required><br><br>
                        <label for="mdprepeat">Répéter le nouveau mot de passe</label><br><br>
                        <input type="password" name="mdprepeat" placeholder="Répéter le nouveau mot de passe" class="form-control" required><br><br>
                        <button name='changemdp' class="btn btn-primary">Changer de mot de passe</button>
                    </form>
                </div>
            </div>
        </div> 
        <script type="text/javascript" src='loading.js'></script>
    </body>
</html>