<?php
    session_start();
    if(isset($_POST['Deco']))
        {
            unset($_SESSION['usernumber']);
        }
    
    if(!isset($_SESSION))
    {
        header('Location: index.php');
    }
var_dump($_SESSION['usernumber']);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Wise Tree Banque</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
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
        <h2>Bienvenue sur la Wise Tree Bank</h2>
        <h1>Vos comptes<h1>
        <div class="comptes_container">
            <?php
                $nbcompte = 1;
                $solde = 0;
                echo "<div class='compte'>";
                echo "<a href='mainpage.php'>Compte $nbcompte</a>";
                echo "<p>Solde: $solde €</p>";
                echo '</div>';
            ?>
        </div>
        

    </body>

</html>