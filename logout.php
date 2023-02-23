<?php

session_start();
if(isset($_POST['Deco']))
    {
        unset($_SESSION['userid']);
        echo "<h1>Deconnexion en cours !</h1>";
        header('Location: index.php');
    }



?>