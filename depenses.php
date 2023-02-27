<?php
session_start();
if(isset($_POST['submit'])){
    $_SESSION['popup'] = true;
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/jpg" href="logo.jpg" />
    <title>Ma banque</title>
    <style>
        h1, h2 {
            text-align: center;
        }

        .confirm {
            color: green;
        }

        .erreur {
            color: red;
        }

        .nav_bar {
            display: flex;
        }

        /* Ajouter une position relative au conteneur */
        .container {
            position: relative;
        }

        /* Modifier la position du popup */
        .popup {
            display: block;
            position: absolute;
            bottom: 100%; /* Afficher le popup juste en dessous du bouton */
            right: 0;
            background-color: #f1f1f1;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px 2px rgba(0,0,0,0.3);
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .popup.show {
            opacity: 1;
        }
    </style>

    <script>
        window.onload = function() {
            <?php
                if(isset($_SESSION['popup'])){
                    unset($_SESSION['popup']);
                    if(!empty($_POST['destinataire']) && !empty($_POST['virement'])){
                ?>
                var popup = document.getElementById("popup");
                popup.classList.add("show");
                setTimeout(function(){
                    popup.classList.remove("show");
                }, 5000);
                <?php
                    }
                }
            ?>
        }
    </script>
</head>
<body style="text-align:center">
<header>
        <div class="nav_bar">
            <form method="POST" action="compte.php">
                <button name="lescomptes">Retour</button>
            </form>
            <form method="POST" action="lescomptes.php">
                <button name="lescomptes">Vos comptes</button>
            </form>
            
            <form method="POST" action="logout.php">
                <button name="Deco">Deconnexion</button>
            </form>
        </div>
        
    </header>
    <h1><b>Mes dépenses</b></h1>
    <h2>FR13 1273 9000 7064 3341 7217 M62</h2>
    <h3>Effectuer un virement</h3>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="container">
            <input type="text" id="destinataire" name="destinataire" placeholder="RIB du destinataire"><br><br>
            <input type="text" id="virement" name="virement" placeholder="Somme"><br><br>
            <button type="submit" name="submit">Effectuer le virement</button>
            <!-- Ajouter le popup dans le même conteneur que le bouton -->
            <div id="popup" class="popup">
                <p>Virement effectué</p>
            </div>
        </div>
    </form>

</body>
</html>

    <?php
    

    function checkvirement()
    {
        $confirm = "Virement effectué";
        if (isset($_POST['send'])) {
            if (isset($_POST['virement']) && $_POST['virement'] != '' && strlen($_POST['virement']) >= 0) {
                if (isset($_POST['destinataire']) && $_POST['destinataire'] != '' && strlen($_POST['destinataire']) = 27) {
                    $err = 0;
                    echo '<script> myFunction(); <script>';
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
</html>
    