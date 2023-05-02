<?php
session_start();

//$bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');  //Localhost 
$bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','wisetree');

try{
    $bdd;

}catch(exception $e){
    die('Erreur connexion: '. $e->getMessage());
}

if(!isset($_SESSION['userid']))
    {
        header('Location: Connexion');
    }

if(!isset($_SESSION['compteactuel'])){
    header('Location: Accueil');
}

if(isset($_POST['comptes'])){
    header('Location: VotreCompte');
}

if(isset($_POST['lescomptes'])){
    unset($_SESSION['compteactuel']);
    unset($_SESSION['compteactuelnom']);
    header('Location: Accueil');
}

if(isset($_POST['Deco'])){
    header('Location: logout.php');
}

function historique($bdd){
    $requetedata = "SELECT * FROM comptes WHERE userid = ?";
    $requetedata = $bdd->prepare($requetedata); 
    $requetedata->execute(array($_SESSION['userid']));
    $data = $requetedata->fetch();

    $countcomptes = "SELECT COUNT(id_envoyeur) FROM virements WHERE id_envoyeur = ?";
    $countcomptes = $bdd->prepare($countcomptes); 
    $countcomptes->execute(array($data['RIB']));
    $compteur =  $countcomptes->fetchColumn();

    if($compteur == 0){

        echo "<h2>Vous n'avez effectué aucun virement.</h2>";

    }else{
        
        $sql = "SELECT * FROM virements WHERE id_envoyeur = ? ORDER BY date DESC;";
        $request = $bdd->prepare($sql);
        $request->execute(array($data['RIB']));
        

        echo '<table>';
        echo '<tr>
            <th style="padding: 10px">Destinataire</th>
            <th style="padding: 10px">Somme transferrée</th>
            <th style="padding: 10px;">Date du transfert</th>
            <th style="padding: 10px">Raison du transfert</th>

        </tr>';

        while($data = $request->fetch()){
            echo '<tr>';
            echo '<td>' . $data['id_destinataire'] . '</td>';
            echo '<td>' . $data['valeur'] . '</td>';
            echo '<td>' . $data['date'] . '</td>';
            if($data['raison'] == ''){
                echo '<td>N/A</td>';
            }else{
                echo '<td>' . $data['raison'] . '</td>';
            }
            
            echo '</tr>';
        }
        echo'</table>';
    }
    
}

function checkcomptes($bdd){

    $compte = $_SESSION['compteactuelnom'];
    $requetedata = "SELECT * FROM comptes WHERE comptenom = ?";
    $requetedata = $bdd->prepare($requetedata); 
    $requetedata->execute(array($compte));
    $data = $requetedata->fetch();
    echo "<h3>Votre solde: <u>" . $data['solde'] . "€</u></h4>";
    echo "<h3>IBAN: " . htmlspecialchars(strtoupper($data['RIB'])) . "</h3>";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="icon" type="image/jpg" href="logo.jpg" />
        <title>Ma banque</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <style>
            h1, h2{
            text-align: center;
            }
            .confirm{
                background-color: green;
                color: black;
            }

            .erreur{
                color: red;
            }

            .nav_bar{
                display: flex;
            }
            
            /* Popup container - can be anything you want */
            .popup {
            position: relative;
            display: inline-block;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            }

            /* The actual popup */
            .popup .popuptext {
            visibility: hidden;
            width: 160px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px 0;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            transform : translateY(200%);
            left: 50%;
            margin-left: -80px;
            }


            /* Toggle this class - hide and show the popup */
            .popup .show {
            visibility: visible;
            -webkit-animation: fadeIn 1s;
            animation: fadeIn 1s;
            }

            /* Add animation (fade in the popup) */
            @-webkit-keyframes fadeIn {
            from {opacity: 0;} 
            to {opacity: 1;}
            }

            @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity:1 ;}
            }
        </style>   

        <link rel="stylesheet" type="text/css" href="css/style.css">    
    </head>
    <body>
        <div class="navbar-nav">
            <form method="POST" action="VotreHistorique">
                <button name="comptes" class="btn btn-secondary">Retour</button>
                <button name="lescomptes" class="btn btn-secondary">Vos comptes</button>
                <button name="Deco" class="btn btn-secondary">Deconnexion</button>
            </form>
        </div>
        <div class='container'>
        <?php if(isset($_SESSION['usermessage'])){
                echo $_SESSION['usermessage'];
            }?>
            <div class='form-container'>
                <h1><b>Mes dépenses</b></h1>
                <h2><?php 
                checkcomptes($bdd);
                ?></h2>
                <h3>Effectuer un virement</h3>
                <form action="traitement" method="post">
                    <label for="destinataire">RIB du destinataire:</label><br>
                    <input type="text" id="virement" name="destinataire" placeholder="RIB du destinataire" class="form-control" required><br>
                    <label for="virement">Somme à transferrer:</label><br>
                    <input type="text" id="virement" name="virement" placeholder="Somme" class="form-control" required><br>
                    <label for="raison">Raison du virement:</label><br>
                    <input type="text" id="virement" name="raison" placeholder="Raison du virement (100 caractères maximum)" class="form-control"><br>
                    <button name="send" class="btn btn-primary">Envoyer</button>
                </form>

                <h2>
                    Historique:
                </h2>

                <?php   
                    historique($bdd);
                ?>
            </div>
        </div>
    </body>
</html>
    