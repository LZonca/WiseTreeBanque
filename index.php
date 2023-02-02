<?php
    session_start();
function checklogin()
{
    $err = 0;
    if (isset($_POST['login'])) {
        if (isset($_POST['usernumber']) && $_POST['usernumber'] != '' && strlen($_POST['usernumber']) == 11) {
            if (isset($_POST['password']) && $_POST['password'] != '' && strlen($_POST['password'])) {
                $err = 0;
                $_SESSION['usernumber'] = $_POST['usernumber'];
                header('Location: lescomptes.php');
            } else {
                return $err = 1;
            }
        } else {
            $err = 2;
        }
    }
    var_dump($_SESSION);
}
?>

<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <input type="text" id="accountnumber" name="usernumber" placeholder="04123456789" pattern="[0-9]{11}" required><br>
            <small>Format: 04123456789</small><br>

            <label for="password">Code Personnel*:</label><br>
            <input type="password" id="pwd" name="password" placeholder="123456" pattern="[0-9]{6}" required><br><br>
            
            <button name="login">Se connecter</button>
        </form>
        <p id="obligatory">
            * : Champ obligatoire
        </p>
        <?php
        if(isset($_POST['login']))
        {
            if(checklogin() == 0)
            {
                echo "Entrez données";
                if (checklogin() == 1)
                {
                    echo '<div class = "error_box">';
                        echo '<h2 class ="error">Identifiant manquant</h2>';
                    echo '</div>';
                    if (checklogin() == 2)
                    {
                        
                        echo '<div class = "error_box">';
                            echo '<h2 class ="error">Mot de passe manquant</h2>';
                        echo '</div>';
                    }
                }
            }
            
        }
?>
    </div>
    </body>
</html>
