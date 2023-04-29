<?php
session_start();
// var_dump($_SESSION['userid']); // A enlever si nécéssaire
$bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root', '');
try {
    //$bdd = new PDO('mysql:host=10.206.237.9;dbname=wisebankdb;charset=utf8', 'phpmyadmin', 'carriat');
    $bdd;
} catch (exception $e) {
    die('Erreur: ' . $e->getMessage());
}

function checklogin($bdd)
{
    if (isset($_POST['login'])) {
        if (isset($_POST['password']) && $_POST['password'] != '' && strlen($_POST['password']) >= 6) {
            if (isset($_POST['userid']) && $_POST['userid'] != '' && strlen($_POST['userid']) == 11) {
                $user = $_POST['userid'];
                $pass = $_POST['password'];
                $requete = "SELECT * FROM users WHERE userid = ?;";
                $requete = $bdd->prepare($requete);
                $requete->execute(array($user));
                $data = $requete->fetch();
                if (isset($_POST['login'])) {
                    if ($data){
                        if (password_verify($pass, $data['password'])) {
                            $_SESSION['userid'] = $_POST['userid'];
                            header('Location: Accueil');
                        } else {
                            $_SESSION['usermessage'] = "<p class='alert alert-danger'>Mot de passe incorrect</p>";
                        }
                    } else {
                        $_SESSION['usermessage'] = "<p class='alert alert-danger'>Numero d'utilisateur à 11 chiffres mal renseigné.</p>";
                    }
                } else {
                    $_SESSION['usermessage'] = "<p class='alert alert-danger'>Code à 6 chiffres mal renseigné.</p>";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
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
    <div class="container">
        <h1>
            WiseTreeBank
        </h1>
        <?php if (isset($_SESSION['usermessage'])) {
            echo $_SESSION['usermessage'];
        } ?>
        <div class="row">
            <div class="col"></div>
            <div class="col" border: solid>
                <h2 id="connexion_title">
                    Connectez-vous:
                </h2>
                <form action="Connexion" method="post">
                    <label for="userid">Numéro de compte*:</label><br>
                    <input type="text" name="userid" placeholder="04123456789" pattern="[0-9]{11}" class="form-control" required><br>
                    <small>Format: 04123456789</small><br>

                    <label for="password">Code Personnel*:</label><br>
                    <input type="password" id="pwd" name="password" placeholder="123456" pattern="[0-9]{6}" class="form-control" required><br>
                    <small>Format: 123456</small><br><br>

                    <button name="login" class="btn btn-primary">Se connecter</button>
                </form>
                <p id="obligatory">
                    * : Champ obligatoire
                </p>
                <?php
                if (isset($_POST['login'])) {
                    checklogin($bdd);
                }
                ?>
            </div>
            <div class="col"></div>
        </div>
    </div>
</body>

</html>