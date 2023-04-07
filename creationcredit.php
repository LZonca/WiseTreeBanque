<?php
session_start();
if(!isset($_SESSION['userid'])){
    header('Location: index.php');
}
/*if(!isset($_POST['nompret']))
{
    if(!isset($_POST['prenompret']))
    {
        header('Location: controlpannel.php');
    }
}*/
function addcredit($bdd){
    $nom = $_POST['nompret'];
    $prenom = $_POST['prenompret'];

    $nomrequete = "SELECT COUNT(*) FROM users WHERE nom = ?";
    $nomrequete= $bdd->prepare($nomrequete);
    $nomrequete->execute(array($nom));
    $countnom = $nomrequete->fetchColumn();

    $prenomrequete = "SELECT COUNT(*) FROM users WHERE prenom = ?";
    $prenomrequete = $bdd->prepare($prenomrequete);
    $prenomrequete->execute(array($prenom));
    $countpren = $prenomrequete->fetchColumn();

    if ($countnom == 0 && $countpren == 0){
        // Afficher un message d'erreur si l'utilisateur n'existe pas.
        echo '<div class="error_box">';
        echo "<p class='error'>Pas d'utilisateur à ce nom!<p>";
        echo '</div>';
    } else {
        checkcomptes($bdd);
    }

}

?>

<!DOCTYPE HTML>
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
    <header>

    </header>

    <div class="container">
        <?php 
            echo 'Compte " ' .  $_POST['compteactuel'] . '" de ' . $_POST['prenompret'] . " " . $_POST['nompret'] . ".";
            echo '<br/>';

            
        ?>
        <form action='creationcredit.php' method='POST'>
            <input type='text' name='raisonpret' class='form-control'>
            <input type='text' name='valeur' pattern="[0-9]" class='form-control' required>
            <input type='text' name='interet' pattern="[0-9]" class='form-control' required>
            <input type='date' name='echeance' required>

        </form>
    </div>
</body>