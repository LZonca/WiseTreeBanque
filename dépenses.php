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
    </style>   

    
</head>
<body>
    <h1><b>Mes dépenses</b></h1>
    <h2>FR13 1273 9000 7064 3341 7217 M62</h2>
    <h3>Effectuer un virement</h3>
    <form action="dépenses.php" method="post">
        <input type="text" id="virement" name="virement" placeholder="RIB"><br><br>
        <input type="text" id="virement" name="virement" placeholder="Somme"><br><br>
        <button name="send">Envoyer</button>

    </form>
    <?php
    $confirm = "Virement effectué";

    if (isset($_POST['send'])){
        echo "<p class='confirm'> $confirm <p>";
    }
    ?>

</body>
    