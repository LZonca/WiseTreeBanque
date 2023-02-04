<?php
session_start();
// var_dump($_SESSION['userid']); // A enlever si nécéssaire
if(isset($_POST['Deco']))
    {
        unset($_SESSION['userid']);
    }
function checklogin()
{
    $err = 0;
    if (isset($_POST['login'])) {
        if (isset($_POST['password']) && $_POST['password'] != '' && strlen($_POST['password']) >= 6) {
            if (isset($_POST['userid']) && $_POST['userid'] != '' && strlen($_POST['userid']) == 11) {
                $err = 0;
                $_SESSION['userid'] = $_POST['userid'];
                header('Location: lescomptes.php');
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
checklogin();
?>

<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/jpg" href="logo.jpg" />
        <title>Connexion</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>

    <body>
    <div>
        <h1>
            WiseTreeBank
        </h1>
        <h2 id="connexion_title">
            Connectez-vous:
        </h2>
        <form action="index.php" method="post">
            <label for="accountnumber">Numéro de compte*:</label><br>
            <input type="text" name="userid" placeholder="04123456789" pattern="[0-9]{11}" required><br>
            <small>Format: 04123456789</small><br>

            <label for="password">Code Personnel*:</label><br>
            <input type="password" id="pwd" name="password" placeholder="123456" pattern="[0-9]{6}" required><br><br>
            
            <button name="login">Se connecter</button>
        </form>
        <p id="obligatory">
            * : Champ obligatoire
        </p>
        <div class = "error_box">
            <?php
                if(isset($_POST['login']))
                {
                    if(checklogin() == 0)
                    {
                        echo "Pas erreur";
                    }
                    elseif(checklogin() == 1)
                    {
                        echo '<h2 class ="error">L\'identifiant ne respecte pas les conditions !</h2>';
                    }
                    elseif(checklogin() == 2)
                    {
                        echo '<h2 class ="error">Le mot de passe ne respecte pas les conditions ! </h2>';
                    }
                }
            ?>
        </div>
    </div>
    </body>
</html>
