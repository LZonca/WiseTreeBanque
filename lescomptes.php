<?php
    session_start();
    if(isset($_POST['Deco']))
        {
            unset($_SESSION['userid']);
        }
    
    if(!isset($_SESSION))
    {
        header('Location: index.php');
    }


    function nomrequest()
    {
        try{
        $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');

        }catch(exception $e){
            die('Erreur: '. $e->getMessage());
        }
        $user = $_SESSION['userid'];
        $requete = "SELECT nom, prenom FROM users WHERE id = ?;";
        $requete = $bdd->prepare($requete); 
        $requete->execute(array($user));
        $data = $requete->fetch();
        echo "<h1>Bienvenue " . htmlspecialchars(strtoupper($data['prenom'])) . " " . htmlspecialchars(strtoupper($data['nom'])) . " !</h1>";
    }

    function solderequest()
    {
        try{
            $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');

        }catch(exception $e){
            die('Erreur solde: '. $e->getMessage());
        }
        $user = $_SESSION['userid'];
        $requetesolde = "SELECT solde FROM comptes WHERE userid = ?";
        $requetesolde = $bdd->prepare($requetesolde); 
        $requetesolde->execute(array($user));
        $solde = $requetesolde->fetch();
        echo "<h5>Votre solde: <u>" . $solde['solde'] . " €</u></h5>";
    }

    function comptenomrequest()
    {
        try{
            $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');

        }catch(exception $e){
            die('Erreur nom compte: '. $e->getMessage());
        }
        $user = $_SESSION['userid'];
        $requetedata = "SELECT comptenom FROM comptes WHERE userid = ?";
        $requetedata = $bdd->prepare($requetedata); 
        $requetedata->execute(array($user));
        $data = $requetedata->fetch();
        echo "<h4>Compte " . $data['comptenom'] . "</h4>";
    }
// var_dump($_SESSION['userid']); // A enlever si nécéssaire
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Wise Tree Banque</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="icon" type="image/jpg" href="logo.jpg" />
        <style>
        </style>
    </head>
    <body>
        <header>
            <div class="nav_bar">
                <form method="POST" action="logout.php">
                    <button name="Deco">Deconnexion</button>
                </form>
            </div>
           
        </header>
        <?php nomrequest();?>
        <h2><u>Bienvenue sur la Wise Tree Bank</u></h2>
        <h1>Vos comptes<h1>
        <div class="comptes_container">
            <div class='compte'>
            <?php
                comptenomrequest();
                echo "<a href='compte.php'> </a>";
                solderequest();
            ?>
            </div>
        </div>
        

    </body>

</html>