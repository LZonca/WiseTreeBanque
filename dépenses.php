<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ma banque</title>
    <style>
        h1, h2{
        text-align: center;
        }
        .confirm{
            color: green;
        }

        .erreur{
            color: red;
        }

        .nav_bar{
            display: flex;
        }
    </style>   

    
</head>
<body>
    <header>
        <div class="nav_bar">
            <form method="POST" action="mainpage.php">
                <button name="lescomptes">Retour</button>
            </form>
            <form method="POST" action="lescomptes.php">
                <button name="lescomptes">Vos comptes</button>
            </form>
            
            <form method="POST" action="index.php">
                <button name="Deco">Deconnexion</button>
            </form>
        </div>
        
    </header>
    <h1><b>Mes dépenses</b></h1>
    <h2>FR13 1273 9000 7064 3341 7217 M62</h2>
    <h3>Effectuer un virement</h3>
    <form action="dépenses.php" method="post">
        <input type="text" id="virement" name="destinataire" placeholder="RIB du destinataire"><br><br>
        <input type="text" id="virement" name="virement" placeholder="Somme"><br><br>
        <button name="send">Envoyer</button>

    </form>
    <?php
    

    function checkvirement()
    {
        $confirm = "Virement effectué";
        if (isset($_POST['send'])) {
            if (isset($_POST['virement']) && $_POST['virement'] != '' && strlen($_POST['virement']) >= 0) {
                if (isset($_POST['destinataire']) && $_POST['destinataire'] != '' && strlen($_POST['destinataire']) == 11) {
                    $err = 0;
                } else {
                    $err = 1;
                }
            }else
            {
                $err = 2;
            }
            return $err;
        }  
    }
    ?>
    <?php
        $confirm = "Virement effectué";
        if(isset($_POST['send']))
        {
            if(checkvirement() == 0)
            {
                echo "<p class='confirm'> $confirm <p>";
            }
            elseif(checkvirement() == 1)
            {
                echo '<div class="error_box">';
                    echo '<p class ="error">Veuillez entrer le RIB du destinataire.</p>';
                echo '</div>';
            }
            elseif(checkvirement() == 2)
            {
                echo '<div class="error_box">';
                    echo '<p class ="error">Veuillez entrer une somme à transférer.</p>';
                echo '</div>';
            }
        }
    ?>

</body>
    