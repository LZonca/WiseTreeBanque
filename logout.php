<?php

session_start();
    unset($_SESSION['userid']);
    echo "<h1>Deconnexion en cours !</h1>";
    header('Location: index.php');

    if(!isset($_SESSION))
    {
        header('Location: index.php');
    }
?>