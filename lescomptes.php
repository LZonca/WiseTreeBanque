<?php
    session_start();
    
    if(!isset($_SESSION['userid']))
    {
        header('Location: index.php');
    }

    if(isset($_POST['lescomptes'])){
        unset($_SESSION['compteactuel']);
    }


    if(isset($_POST['compteactuel'])){
        $_SESSION['compteactuel'] = $_POST['compteactuel'];
        header('Location: compte.php');
    }
    
    function rankrequest($bdd)
    {
        try{
        $bdd;

        }catch(exception $e){
            die('Erreur rang: '. $e->getMessage());
        }
        $user = $_SESSION['userid'];
        $requete = "SELECT permissions FROM users WHERE userid = ?;";
        $requete = $bdd->prepare($requete); 
        $requete->execute(array($user));
        $data = $requete->fetch();
        if ($data['permissions'] > 1) {
            // Afficher le bouton d'administration
            echo "<a href='panneladmin.php'>Accéder au panneau d'administration</a>";
        }
    }
    

    function nomrequest($bdd)
    {
        try{
        $bdd;

        }catch(exception $e){
            die('Erreur: '. $e->getMessage());
        }
        $user = $_SESSION['userid'];
        $requete = "SELECT nom, prenom FROM users WHERE userid = ?;";
        $requete = $bdd->prepare($requete); 
        $requete->execute(array($user));
        $data = $requete->fetch();
        echo "<h1>Bienvenue " . htmlspecialchars(strtoupper($data['prenom'])) . " " . htmlspecialchars(strtoupper($data['nom'])) . " !</h1>";
    }

    /*function solderequest()
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
    }*/

    function checkcomptes($bdd){
        try{
            $bdd;

        }catch(exception $e){
            die('Erreur nom compte: '. $e->getMessage());
        }
        $user = $_SESSION['userid'];
        $requetedata = "SELECT * FROM comptes WHERE userid = (SELECT id FROM users WHERE userid = ?) ";
        $requetedata = $bdd->prepare($requetedata); 
        $requetedata->execute(array($user));
        while($data = $requetedata->fetch())
        {
            echo "<div class='compte'>";
            echo "<form method='POST' action='lescomptes.php'>";
            echo "<input type='submit' name='compteactuel' value='" . $data['comptenom'] . "'>"; 
            echo"</form>";
            echo "<h4>Compte " . $data['comptenom'] . "</h4>";
            echo "<h5>Votre solde: <u>" . $data['solde'] . "€</u></h5>";
            echo "</div>";
        }
    }

// var_dump($_SESSION['userid']); // A enlever si nécéssaire
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Wise Tree Banque</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
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
                <form method="POST" action="settings.php">
                <button name="parametres">Paramètres</button>
            </form>
                <form method="POST" action="lescomptes.php">
                    <?php 
                    //$bdd = new PDO('mysql:host=;dbname=wisebankdb;charset=utf8', 'phpmyadmin', 'carriat'); // Reseau local VM
                    $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root',''); // Localhost
                    rankrequest($bdd);
                    ?>
                </form>
            </div>
           
        </header>
        <?php nomrequest($bdd);?>
        <h2><u>Bienvenue sur la Wise Tree Bank</u></h2>
        <?php echo "<h2>Compte utilisateur n° " . htmlspecialchars($_SESSION['userid']). "</h2>"; ?>
        <h1>Vos comptes</h1>
        <div class="comptes_container">
            
            <?php
                checkcomptes($bdd);
            ?>
            </div>
        </div>
</div> 

    </body>

</html>