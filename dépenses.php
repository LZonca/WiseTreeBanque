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
            ?>
            var popup = document.getElementById("popup");
            popup.classList.add("show");
            setTimeout(function(){
                popup.classList.remove("show");
            }, 5000);
            <?php } ?>
        }
    </script>
</head>
<body style="text-align:center">
    <h1><b>Mes dépenses</b></h1>
    <h2>FR13 1273 9000 7064 3341 7217 M62</h2>
    <h3>Effectuer un virement</h3>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="container">
            <input type="text" id="destinataire" name="destinataire" placeholder="RIB du destinataire"><br><br>
            <input type="text" id="virement" name="virement" placeholder="Somme"><br><br>
            <button type="submit" name="submit">Cliquez ici</button>
            <!-- Ajouter le popup dans le même conteneur que le bouton -->
            <div id="popup" class="popup">
                <p>Texte à afficher dans le popup</p>
            </div>
        </div>
    </form>

</body>
</html>
