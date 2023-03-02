<?php
session_start();
// var_dump($_SESSION['userid']); // A enlever si nécéssaire


function loginrequest()
{
    try{
    $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');

    }catch(exception $e){
        die('Erreur: '. $e->getMessage());
    }
    $user = $_POST['userid'];
    $pass = $_POST['password'];
    $requete = "SELECT * FROM users WHERE userid = ?;";
    $requete = $bdd->prepare($requete); 
    $requete->execute(array($user));
    $data = $requete->fetch();
    $err = 0;
    if(isset($_POST['login']))
    {
        if($data)
        {   
            if($data['password'] == $_POST['password'])
            {
                $_SESSION['userid'] = $_POST['userid'];
                header('Location: lescomptes.php');
            }else{
                $err = 1;
            }
        }else{
            $err = 2;
        }
        return $err;
    }
}

function checklogin()
{
    $err = 0;
    if (isset($_POST['login'])) {
        if (isset($_POST['password']) && $_POST['password'] != '' && strlen($_POST['password']) >= 6) {
            if (isset($_POST['userid']) && $_POST['userid'] != '' && strlen($_POST['userid']) == 11) {
                loginrequest();
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
        <style>

            body{
                background-image: url("background.png");
                height: 100%;
                background-position-y: 40%;
                background-position-x: 50%;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="login-container">
                <h1>
                    WiseTreeBank
                </h1>
                
                <div class="login-form-container">
                    <h2 id="connexion_title">
                        Connectez-vous:
                    </h2>
                    <form action="index.php" method="post">
                        <label for="userid">Numéro de compte*:</label><br>
                        <input type="text" name="userid" placeholder="04123456789" pattern="[0-9]{11}" required><br>
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
                                echo "<div class = 'error_box'>";
                                switch(checklogin())
                                {
                                    case 1:
                                    {
                                        
                                        echo '<h2 class ="error">L\'identifiant ne respecte pas les conditions !</h2>';
                                        
                                        break;
                                    }
                                    case 2:
                                    {
                                        echo "<div class = 'error_box'>";
                                        echo '<h2 class ="error">Le mot de passe ne respecte pas les conditions ! </h2>';
                                        echo "</div>";
                                        break;
                                    }
                                }
                                switch(loginrequest())
                                {
                                    case 1:
                                    {
                                        echo "<div class = 'error_box'>";
                                        echo '<h2 class ="error">Le mot de passe incorrect ! </h2>';
                                        echo "</div>";
                                        break;
                                    }
                                    case 2:
                                    {
                                        echo "<div class = 'error_box'>";
                                        echo '<h2 class ="error">Utilisateur inconnu ! </h2>';
                                        echo "</div>";
                                        break;
                                    }

                                }
                                echo "</div>";
                            }
                        ?>
                </div>
            </div>
        </div>
    </body>
</html>
