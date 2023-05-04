<?php
session_start();
// var_dump($_SESSION['userid']); // A enlever si nécéssaire

if($_SERVER['SERVER_NAME'] == "127.0.0.1"){
    $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');
}elseif($_SERVER['SERVER_NAME'] == "10.206.237.111" || $_SERVER['SERVER_NAME'] == "10.206.237.112" || $_SERVER['SERVER_NAME'] == "www.wisetreebanque.sio"){
    $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root', 'wisetree');
}elseif($_SERVER['SERVER_NAME'] == "zonca.alwaysdata.net"){
    $bdd = new PDO('mysql:host=mysql-zonca.alwaysdata.net;dbname=zonca_wisebankdb;charset=utf8', 'zonca_adminbank', 'wisetreebanque');
}
function loginrequest($bdd)
{
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
                header('Location: accueil');
            }else{
                $err = 1;
            }
        }else{
            $err = 2;
        }
        return $err;
    }
}

function checklogin($bdd)
{
    $err = 0;
    if (isset($_POST['login'])) {
        if (isset($_POST['password']) && $_POST['password'] != '' && strlen($_POST['password']) >= 6) {
            if (isset($_POST['userid']) && $_POST['userid'] != '' && strlen($_POST['userid']) == 11) {
                loginrequest($bdd);
            } else {
                $_SESSION['usermessage'] = "<p class='alert alert-danger'>Utilisateur incorrect</p>";
            }
        }else{
            $_SESSION['usermessage'] = "<p class='alert alert-danger'>Mauvais mot de passe</p>";
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
    <link rel="icon" type="image/jpg" href="img/logo.jpg" />
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/relook.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IM+Fell+Double+Pica&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@800&display=swap" rel="stylesheet">
</head>

<body>


        <div class="">
            <div class="row">
            <div class="col" border: solid>
    <div class="frame-1">
      <h1 class="surname">WISE TREE BANK</h1>
      <?php if (isset($_SESSION['usermessage'])) {
            echo $_SESSION['usermessage'];
        } ?>
    <form action="connexion" method="post" id="formulaire">
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
                if (isset($_POST['login'])) {
                    checklogin($bdd);
                }
                ?>
                </div>
           </div>
        </div>
    </div>
</body>

</html>