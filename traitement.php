<?php
session_start();
//$bdd = new PDO('mysql:host=10.206.237.9;dbname=wisebankdb;charset=utf8', 'phpmyadmin', 'carriat'); // Reseau local VM
$bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');

try{
    $bdd;

}catch(exception $e){
    die('Erreur connexion: '. $e->getMessage());
}

unset($_SESSION['usermessage']);

function RIBrequest($bdd)
    {
        $user = $_SESSION['userid'];
        $requetesolde = "SELECT * FROM comptes WHERE userid = ?";
        $requetesolde = $bdd->prepare($requetesolde); 
        $requetesolde->execute(array($user));
        $solde = $requetesolde->fetch();
        return $solde['RIB'];
    }

function transfertrequete($bdd){
    date_default_timezone_set('Europe/Paris');
    $date = date('d-m-y h:i:s');
    $envoyeur = RIBrequest($bdd);
    $destinataire = $_POST['destinataire'];
    $valeur = $_POST['virement'];

    $destinatairesolde = "SELECT * FROM comptes WHERE RIB = ?;";
    $destinatairesolde = $bdd->prepare($destinatairesolde); 
    $destinatairesolde->execute(array($destinataire));
    $soldedest = $destinatairesolde->fetch();

    $usersolde = "SELECT * FROM comptes WHERE RIB = ?;";
    $usersolde = $bdd->prepare($usersolde); 
    $usersolde->execute(array(RIBrequest($bdd)));
    $soldeexpe = $usersolde->fetch();
    $requetedata = 'INSERT INTO virements VALUES (NULL, ?, ?, ?, ?)';
    $requetedata = $bdd->prepare($requetedata); 
    $requetedata->execute(array($destinataire, $envoyeur, $valeur, $date));
    
    $destinatairerequete = "UPDATE comptes SET solde = ? WHERE RIB = ?;";
    $destinatairerequete  = $bdd->prepare($destinatairerequete); 
    $destinatairerequete ->execute(array($soldedest['solde'] + $valeur, $destinataire));
    
    $userrequete = "UPDATE comptes SET solde = ? WHERE RIB = ?;";
    $userrequete  = $bdd->prepare($userrequete); 
    $userrequete ->execute(array($soldeexpe['solde'] - $valeur, RIBrequest($bdd)));

}

function verifsolde($bdd){
    $valeur = $_POST['virement'];
    $err = 0;
    $usersolde = "SELECT * FROM comptes WHERE RIB = ?;";
    $usersolde = $bdd->prepare($usersolde); 
    $usersolde->execute(array(RIBrequest($bdd)));
    $soldeexpe = $usersolde->fetch();
    if(($soldeexpe['solde'] - $valeur) > (0 + $soldeexpe['decouvert_autorise']))
    {
        return true; // Effectuer transfert
    }else{
        return false; // Solde negatif
    }
}

function verifdest($bdd, $dest){

    $destrequete = "SELECT COUNT(*) FROM comptes WHERE RIB = ?";
    $destrequete= $bdd->prepare($destrequete);
    $destrequete->execute(array($dest));
    $countdest = $destrequete->fetchColumn();
    if($countdest == 1)
    {
        return true;
    }else{
        return false;
    }
}

function checkvirement($bdd)
{
    $err = 0;
    if (isset($_POST['send'])) {
        if (isset($_POST['virement']) && $_POST['virement'] != '' && $_POST['virement'] >= 0 && is_numeric($_POST['virement'])) {
            if (isset($_POST['destinataire']) && $_POST['destinataire'] != '' && strlen($_POST['destinataire']) >= 24 ) {
                if($_POST['destinataire'] != RIBrequest($bdd))
                {
                    if(verifdest($bdd, $_POST['destinataire']))
                    {
                        if(verifsolde($bdd)){
                            transfertrequete($bdd);
                            $err = 0; // Effectuer transfert
                            sleep(0.03);
                            header('Location: depenses.php');
                        }
                    }else{
                        $err = 4; // Destinataire inconnu
                    }
                }else{
                    $err = 3; // Destinataire = emetteur
                }
            } else {
                $err = 1; // Destinataire incorrect
            }
        }else
        {
            $err = 2; // Mauvaise valeur
        }
        return $err;
    }  
}

function addcredit($bdd){

    $crediteur = $_SESSION['compteactuel'];

    date_default_timezone_set('Europe/Paris');
    $date = date('d-m-y');

    $creditrequete = "INSERT INTO credits (compteid, soldepret, echeance, date, interet, conseillerid, typeprelevement, raison) VALUES (?,?,?,?,?,?,?,?)";
    $creditrequete= $bdd->prepare($creditrequete);
    $creditrequete->execute(array($crediteur, $_POST['valeur'], $_POST['echeance'], $date, $_POST['interet'], $_SESSION['userid'],$_POST['prelevement'], $_POST['raisonpret']));
    
    $logrequete = "INSERT INTO actionlogs (typaction, actionuser) VALUES (?,?)";
    $logrequete= $bdd->prepare($logrequete);
    $logrequete->execute(array(1, $_SESSION['userid']));

    $usersolde = "SELECT * FROM comptes WHERE RIB = ?;";
    $usersolde = $bdd->prepare($usersolde); 
    $usersolde->execute(array($_SESSION['compteactuel']));
    $soldecrediteur = $usersolde->fetch();

    $destinatairerequete = "UPDATE comptes SET solde = ? WHERE RIB = ?;";
    $destinatairerequete  = $bdd->prepare($destinatairerequete); 
    $destinatairerequete ->execute(array($_POST['valeur'] + $soldecrediteur['solde'], $_SESSION['compteactuel']));

    $_SESSION['usermessage'] = "<p class='alert alert-success'>Le crédit a été effectué avec succès!<p>";
}

if(isset($_POST['createpret'])){
    addcredit($bdd);
    header('Location: creationcredit');
}

?>