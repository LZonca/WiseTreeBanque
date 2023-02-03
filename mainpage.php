<?php
session_start();
var_dump($_SESSION);
if(isset($_POST['Deco']))
    {
        unset($_SESSION['usernumber']);
    }

if(!isset($_SESSION['usernumber']))
    {
        header('Location: index.php');
    }
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ma banque</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        h1, h2{
        text-align: center;
    }
    </style>   
</head>

<body>
    <header>
        <div class="nav_bar">
            <form method="POST" action="lescomptes.php">
                <button name="lescomptes">Vos comptes</button>
            </form>
            <form method="POST" action="index.php">
                <button name="Deco">Deconnexion</button>
            </form>
        </div>
        
    </header>
    <?php
    echo "<h1>Bonjour \"Nom\"</h1>";
    echo "<h2>Compte n° " . htmlspecialchars($_SESSION['usernumber']). "</h1>"; ?>
    <h2>Bienvenue sur la Wise Tree Bank</h2>
    <h3><u>Vôtre compte</u></h3>
    <p>
        <ul>
            <li><a href="dépenses.php">Solde : ___ €</a></li>
            <li>Découvert autorisé : ___ €</li>
        </ul>
    </p>
    <h3>RIB</h3>
    <?php
        $ribuser = "FR1312739000706433417217M62";
        echo "<p><u>$ribuser</u></p>";
    ?>
    

</body>
</html>