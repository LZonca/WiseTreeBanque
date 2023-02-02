<?php
session_start();
var_dump($_SESSION);
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ma banque</title>
    <link rel="stylesheet" type="text/css" href="main.css">
    <style>
        h1, h2{
        text-align: center;
    }
    </style>   
</head>

<body>
    <h1>Bonjour...</h1>
    <h2>Bienvenue sur la Wise Tree Bank</h2>
    <h3><u>Vôtre compte</u></h3>
    <p>
        <ul>
            <li><a href="dépenses.html">Solde : ___ €</a></li>
            <li>Découvert autorisé : ___ €</li>
        </ul>
    </p>
    <h3>RIB</h3>
    <p><u>FR1312739000706433417217M62</u></p>


    <p><b>
        <form method="POST" action="index.php">
            <button name="Deco">Deconnexion</button>
        </form> 
    </b></p>








</body>

</html>