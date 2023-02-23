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
                $nbcompte = 1;
                $solde = 0;
                echo "<a href='compte.php'>Compte $nbcompte</a>";
                echo "<p>Solde: $solde €</p>";
            ?>
            </div>
        </div>
        

    </body>

</html>