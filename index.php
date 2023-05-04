<?php
session_start();
// var_dump($_SESSION['userid']); // A enlever si nécéssaire


function loginrequest()
{
    try{
    //$bdd = new PDO('mysql:host=10.206.237.9;dbname=wisebankdb;charset=utf8', 'phpmyadmin', 'carriat');
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
            if(password_verify($pass, $data['password']))
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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    
        <link rel="stylesheet" type="text/css" href="css/relook.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IM+Fell+Double+Pica&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@800&display=swap" rel="stylesheet">


        <style>
        </style>
    </head>

    <body>
        <div class="">
            <div class="row">
            <div class="col" border: solid>
    <div class="frame-1">
      <h1 class="surname">WISE TREE BANK</h1>
    <form action="index.php" method="post" id="formulaire">
      <div class="numero-de-compte">NUMERO DE COMPTE*:</div>
      <input type="text" name="userid" placeholder=" Exemple: 04123456789" pattern="[0-9]{11}" class="form-control" required >
      <div class="overlap-groupoverlap">
        <div class="exempleinter-medium-black-24px"></div>
      </div>
      <div class="code-personnel">CODE PERSONNEL*:</div>
    <input type="password" id="pwd" name="password" placeholder=" Exemple: 123456" pattern="[0-9]{6}" class="form-control" required>
      <div class="overlap-group1overlap">
        <div class="exempleinter-medium-black-24px"></div>
      </div>
      <div class="conne-container">
        <span class="inter-normal-white-15px"></span>
      </div>
           <p align="center"><button name="login" class="se-connecter">Se connecter</button>
            <p class="champ-obligatoire">
                        * : Champ obligatoire
                    </p>
                </p>
                   
    </div>
                       
                   
                    </form>
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
                        ?></div>
            </div>
        </div>
    </body>
</html>
