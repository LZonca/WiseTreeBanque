<?php
    session_start();
    //$bdd = new PDO('mysql:host=10.206.237.9;dbname=wisebankdb;charset=utf8', 'phpmyadmin', 'carriat'); // Reseau local VM
    $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root',''); // Localhost
    if(!isset($_SESSION['userid']))
    {
        header('Location: index.php');
    }

    if(isset($_POST['Deco'])){
        header('Location: logout.php');
    }
    
    if(isset($_POST['parametres'])){
        header('Location: settings.php');
    }

    if(isset($_POST['contact'])){
        header('Location: contact.php');
    }

    if(isset($_POST['compteactuelnom'])){
        $_SESSION['compteactuel'] = $_POST['compteactuel'];
        $_SESSION['compteactuelnom'] = $_POST['compteactuelnom'];
        header('Location: compte.php');
    }

    if(isset($_POST['admin'])){
        header('Location: panneladmin.php');
    }

    if(isset($_POST['conseil'])){
        header('Location: pannelconseiller.php');
    }

    if(isset($_POST['banquier'])){
        header('Location: pannelbanquier.php');
    }

    if(isset($_POST['contact'])){
        header('Location: contact.php');
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
        if ($data['permissions'] == 4) {
            // Afficher le bouton d'administration
            echo "<button name='admin' class='btn btn-info'>Accéder au panneau d'administration !</button>";
        }
        elseif($data['permissions'] == 2){
            echo "<button name='conseil' class='btn btn-info'>Accéder aux outils conseiller !</button>";
        }
        elseif($data['permissions'] == 3){
            echo "<button name='banquier' class='btn btn-info'>Accéder aux outils banquier !</button>";
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
        $requetedata = "SELECT * FROM comptes WHERE userid = ? ";
        $requetedata = $bdd->prepare($requetedata); 
        $requetedata->execute(array($user));
        while($data = $requetedata->fetch())
        {
            
            echo "<div class='compte'>";
            echo "<h2><b>Compte " . $data['comptenom'] . "</b></h2>";
            echo "<form method='POST' action='lescomptes.php'>";
            echo "<input type='submit' name='compteactuelnom' value='" . $data['comptenom'] . "'>"; 
            echo "<input type='text' hidden name='compteactuel' value='" . $data['RIB']. " '>"; 
            echo"</form>";
            echo "<h5>Votre solde: <u>" . $data['solde'] . "€</u></h5>";
            echo "</div>";
        }
    }

// var_dump($_SESSION['userid']); // A enlever si nécéssaire
function messagecount($bdd){
    $user = $_SESSION['userid'];
    $idrequete = "SELECT COUNT(*) FROM chat WHERE destinataireid = ? AND requeststatus = 0";
    $idrequete= $bdd->prepare($idrequete);
    $idrequete->execute(array($user));
    $countid = $idrequete->fetchColumn();
    if($countid > 0)
    {
        echo $countid;
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Wise Tree Banque</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="icon" type="image/jpg" href="logo.jpg" />
    </head>
    <body>
            <div class="navbar-nav">
                <form method="POST" action="lescomptes.php">
                    <button name="Deco" class="btn btn-secondary">Deconnexion</button>
                    <button name="parametres" class="btn btn-secondary">Paramètres</button>
                    <button name='contact' class='btn btn-primary'>Messagerie<span class="badge bg-danger ms-2"><?php messagecount($bdd) ?></span></button>  
                    <?php 

                    rankrequest($bdd);
                    ?>
                </form>
            </div>
        <div class="container">
                <?php nomrequest($bdd);?>
                <h1><u>Bienvenue sur la Wise Tree Bank</u></h1>
                <?php echo "<h2>Compte utilisateur n° " . htmlspecialchars($_SESSION['userid']). "</h2>"; ?>
                <h2>Vos comptes</h2>
                <div class="comptes_container">
                    <?php
                        checkcomptes($bdd);
                    ?>
                </div>
            </div>
        </div>
    </div> 
    </body>

</html>