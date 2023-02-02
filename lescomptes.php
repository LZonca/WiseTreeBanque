<?php
    session_start();
    var_dump($_SESSION);

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Wise Tree Banque</title>
        <style>
        </style>
    </head>
    <body>
        <header>
            <form method="POST" action="index.php">
                <button name="Deco">Deconnexion</button>
            </form>
        </header>
        <h1>Vos comptes<h1>
        <a href="mainpage.php">Compte1</a>
        <p>Solde</p>

    </body>

</html>