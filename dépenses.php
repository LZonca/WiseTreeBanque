<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/jpg" href="logo.jpg" />
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

        <style>
        h1, h2{
        text-align: center;
        }
        .confirm{
            color: green;
        }
        
        /* Popup container - can be anything you want */
        .popup {
        position: relative;
        display: inline-block;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        }

        /* The actual popup */
        .popup .popuptext {
        visibility: hidden;
        width: 160px;
        background-color: #555;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 8px 0;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        transform : translateY(200%);
        left: 50%;
        margin-left: -80px;
        }


        /* Toggle this class - hide and show the popup */
        .popup .show {
        visibility: visible;
        -webkit-animation: fadeIn 1s;
        animation: fadeIn 1s;
        }

        /* Add animation (fade in the popup) */
        @-webkit-keyframes fadeIn {
        from {opacity: 0;} 
        to {opacity: 1;}
        }

        @keyframes fadeIn {
        from {opacity: 0;}
        to {opacity:1 ;}
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
    <div class="popup" onclick="myFunction()">Effectuer le virement 
            <span class="popuptext" id="myPopup">Virement effectué ! </span>
    </div>
    <script>
        // When the user clicks on div, open the popup
        function myFunction() {
            var popup = document.getElementById("myPopup");
            popup.classList.toggle("show");
        }
</script>
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
</html>
    