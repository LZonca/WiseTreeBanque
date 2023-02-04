<?php
    session_start();
    if(isset($_POST['Deco']))
        {
            unset($_SESSION['userid']);
        }
    
    if(!isset($_SESSION))
    {
        header('Location: index.php');
    }
// var_dump($_SESSION['userid']); // A enlever si nécéssaire
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Wise Tree Banque</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="icon" type="image/jpg" href="logo.jpg" />
        <style>
        </style>
    </head>
    <body>
        <header>
            <div class="nav_bar">
                <form method="POST" action="index.php">
                    <button name="Deco">Deconnexion</button>
                </form>
            </div>
           
        </header>
        <?php
        echo "<h1>Bonjour \"Nom\"</h1>";?>
        <h2><u>Bienvenue sur la Wise Tree Bank</u></h2>
        <h1>Vos comptes<h1>
        <div class="comptes_container">
            <div class='compte'>
            <?php
                $nbcompte = 1;
                $solde = 0;
                echo "<a href='compte.php'>Compte $nbcompte</a>";
                echo "<p>Solde: $solde €</p>";
            ?>
            </div>
        </div>
        

    </body>

</html>