<?php
session_start();

if(!isset($_SESSION))
    {
        header('Location: connexion');
    }

//$bdd = new PDO('mysql:host=10.206.237.9;dbname=wisebankdb;charset=utf8', 'phpmyadmin', 'carriat'); // Reseau local VM
$bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','');  //Localhost 
//$bdd = new PDO('mysql:host=localhost;dbname=wisebankdb;charset=utf8', 'root','wisetree');

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

    $envoyeur = RIBrequest($bdd);
    $destinataire = $_POST['destinataire'];
    $valeur = $_POST['virement'];
    $raison = $_POST['raison'];

    $destinatairesolde = "SELECT * FROM comptes WHERE RIB = ?;";
    $destinatairesolde = $bdd->prepare($destinatairesolde); 
    $destinatairesolde->execute(array($destinataire));
    $soldedest = $destinatairesolde->fetch();

    $usersolde = "SELECT * FROM comptes WHERE RIB = ?;";
    $usersolde = $bdd->prepare($usersolde); 
    $usersolde->execute(array(RIBrequest($bdd)));
    $soldeexpe = $usersolde->fetch();
    $requetedata = 'INSERT INTO virements (id_destinataire, id_envoyeur, valeur, raison) VALUES (?, ?, ?, ?)';
    $requetedata = $bdd->prepare($requetedata); 
    $requetedata->execute(array($destinataire, $envoyeur, $valeur));
    
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

function checkvirement($bdd) {
    if (isset($_POST['send'])) {
        if (isset($_POST['virement']) && $_POST['virement'] != '' && $_POST['virement'] > 0 && is_numeric($_POST['virement'])) {
            if (isset($_POST['destinataire'])) {
                if ($_POST['destinataire'] != '' && strlen($_POST['destinataire']) >= 24) {
                    if ($_POST['destinataire'] != RIBrequest($bdd)) {
                        if (verifdest($bdd, $_POST['destinataire'])) {
                            if (verifsolde($bdd)) {
                                transfertrequete($bdd);
                                $_SESSION['usermessage'] = "<p class='alert alert-success'>Le virement a été effectué avec succès!<p>"; // Effectuer transfert
                            } else {
                                $_SESSION['usermessage'] = '<p class="alert alert-danger"><b>Pas assez d\'argent sur le compte.</b></p>';
                            }
                        }else{
                                $_SESSION['usermessage'] = '<p class="alert alert-danger"><b>Compte inconnu</b></p>';
                            }
                        } else {
                            $_SESSION['usermessage'] = '<p class="alert alert-danger"><b>Vous ne pouvez pas envoyer de l\'argent vers le compte d\'origine</b></p>';
                        }
                     } else{
                            $_SESSION['usermessage'] = '<p class="alert alert-danger"><b>Compte inconnu</b></p>';
                } }else {
                    $_SESSION['usermessage'] = '<p class="alert alert-danger"><b>Veuillez entrer l\'IBAN du destinataire.</b></p>'; // Destinataire incorrect
                }
            } else {
                $_SESSION['usermessage'] = '<p class="alert alert-danger"><b>Veuillez entrer une somme valide à transférer.<br> (La somme ne peut pas etre négative !)</b></p>';
            }
        }
    }

function addcredit($bdd){

    $crediteur = $_SESSION['compteactuel'];

    date_default_timezone_set('Europe/Paris');
    $date = date('d-m-y');

    $creditrequete = "INSERT INTO credits (compteid, soldepret, remboursement , echeance, date, interet, valeur_remboursment , conseillerid, typeprelevement, raison) VALUES (?,?,?,?,?,?,?,?,?,?)";
    $creditrequete= $bdd->prepare($creditrequete);
    $creditrequete->execute(array($crediteur, $_POST['valeur'], $_POST['valeurremboursment'],$_POST['valeur'] * (1 + $_POST['valeur']/100), $_POST['echeance'], $date, $_POST['interet'], $_SESSION['userid'],$_POST['prelevement'], $_POST['raisonpret']));
    
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
    header('Location: nouveau-credit');
}

if(isset($_POST['send']))
{
    checkvirement($bdd);
    header('Location: votre-historique');
}
?>