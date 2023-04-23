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

function checkcredits($bdd){
    $user = $_SESSION['compteactuel'];

    $requetedata = "SELECT * FROM credits WHERE compteid = ?";
    $requetedata = $bdd->prepare($requetedata); 
    $requetedata->execute(array($user));
    
    


    echo "<table>";
    
    echo "<th>N° du crédit</th>";
    echo "<th>Montant du prêt</th>";
    echo "<th>Taux d'interet</th>";
    echo "<th>Montant a rembourser</th>";
    echo "<th>Intitulé du prêt</th>";
    echo "<th>Échéance du prêt</th>";
    echo "<th>Periodicité des prélèvements</th>";
    echo "<th>Date du crédit</th>";

    
    while($data = $requetedata->fetch())
    {   
        $class = date('d-m-y') < $data['echeance'] ? 'alert' : 'normal';
        echo "<tr class='" . $class . "'>";
        echo "<td>". $data['creditid'] . "</td>";
        echo "<td>". $data['soldepret'] . "</td>";
        echo "<td>". $data['interet'] . "</td>";
        echo "<td>". $data['soldepret'] * $data['interet'] . "</td>";
        echo "<td>". $data['raison'] . "</td>";
        echo "<td>". $data['echeance'] . "</td>";
        echo "<td>". $data['typeprelevement'] . "</td>";
        echo "<td>". $data['date'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="logo.jpg" />
    <title>Vos crédits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        body{
            background-image: url("background.png");
            height: 100%;
            background-position-y: 20%;
            background-position-x: 50%;
            color: white;
        }
        th{
            padding: 5px;
        }

        .normal{
            background-color: transparent;
        }

        .alert{
            background-color: red;
        }

        
    </style>
</head>
  <body>

  <header>
    <div class="navbar-nav">
        <form method="POST" action="lescomptes.php">
            <button name="comptes" class="btn btn-primary">Retour</button>
            <button name="lescomptes" class="btn btn-secondary">Vos comptes</button>
            <button name="Deco" class="btn btn-secondary">Deconnexion</button>
        </form>
    </div>
  </header>
    <h1>Vos crédits en cours: </h1>
    <?php
        checkcredits($bdd);
    ?>
  </body>
</html>