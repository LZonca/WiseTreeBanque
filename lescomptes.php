<?php
    session_start();
    if($_SERVER['SERVER_NAME'] == "127.0.0.1"){
        $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');
    }elseif($_SERVER['SERVER_NAME'] == "10.206.237.111"){
        $bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root', 'wisetree');
    }elseif($_SERVER['SERVER_NAME'] == "zonca.alwaysdata.net"){
        $bdd = new PDO('mysql:host=mysql-zonca.alwaysdata.net;dbname=zonca_wisebankdb;charset=utf8', 'zonca_adminbank', 'wisetreebanque');
    }
    if(!isset($_SESSION['userid']))
    {
        header('Location: connexion');
    }

    if(isset($_POST['Deco'])){
        header('Location: logout');
    }
    
    if(isset($_POST['parametres'])){
        header('Location: parametres');
    }

    if(isset($_POST['compteactuelnom'])){
        $_SESSION['compteactuel'] = $_POST['compteactuel'];
        $_SESSION['compteactuelnom'] = $_POST['compteactuelnom'];
        header('Location: votre-compte');
    }

    if(isset($_POST['control'])){
        header('Location: administration');
    }

    if(isset($_POST['contact'])){
        header('Location: messagerie');
    }
    
    unset($_SESSION['usermessage']);
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
            echo "<button name='control' class='btn btn-info'>Accéder au panneau d'administration !</button>";
        }
        elseif($data['permissions'] == 2){
            echo "<button name='control' class='btn btn-info'>Accéder aux outils conseiller !</button>";
        }
        elseif($data['permissions'] == 3){
            echo "<button name='control' class='btn btn-info'>Accéder aux outils banquier !</button>";
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
        echo "<p>Bienvenue " . htmlspecialchars(strtoupper($data['prenom'])) . " " . htmlspecialchars(strtoupper($data['nom'])) . " !</p>";
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

        $countcomptes = "SELECT COUNT(*) FROM comptes WHERE userid = ?";
        $countcomptes = $bdd->prepare($countcomptes); 
        $countcomptes->execute(array($user));
        $compteur =  $countcomptes->fetchColumn();

        if($compteur == 0){
            echo "<h2 style='color: black; padding-top: 5px'>Vous n'avez aucun compte, contactez votre conseiller pour en ouvrir un !</h2>";
        }else{

        $requetedata = "SELECT * FROM comptes WHERE userid = ? ";
        $requetedata = $bdd->prepare($requetedata); 
        $requetedata->execute(array($user));
        while($data = $requetedata->fetch())
        {
            
            echo "<div class='compte'>";
            echo "<h2><b>Compte " . $data['comptenom'] . "</b></h2>";
            echo "<form method='POST' action='accueil'>";
            echo "<input type='text' hidden name='compteactuel' value='" . $data['RIB']. " '>";
            echo "<input class='btn btn-primary' type='submit' name='compteactuelnom' value='" . $data['comptenom'] . "'>"; 
            
            echo"</form>";
            echo "<h5>Votre solde: <u>" . $data['solde'] . "€</u></h5>";
            echo "</div>";
        }
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
        <link rel="icon" type="image/jpg" href="img/logo.jpg" />
    </head>
    <body>
            <div class="navbar-nav">
                <form method="POST" action="accueil">
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
                <p><u>Bienvenue sur la Wise Tree Bank</u></p>
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