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
    
</body>
<body style="text-align:center">
    <h1><b>Mes dépenses</b></h1>
    <h2>FR13 1273 9000 7064 3341 7217 M62</h2>
    <h3>Effectuer un virement</h3>
    <form action="dépenses.php" method="post">
        <input type="text" id="virement" name="virement" placeholder="RIB"><br><br>
        <input type="text" id="virement" name="virement" placeholder="Somme"><br><br>
        <!-- <input type="submit" name="send" class="button" value="Envoyer" /> -->
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
   

</body>
    