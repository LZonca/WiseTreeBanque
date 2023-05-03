<?php
session_start();
    unset($_SESSION['userid']);
    echo "<h1>Deconnexion en cours !</h1>";
    unset($_SESSION['usermessage']);
    session_destroy();
    header('Location: connexion');

    if(!isset($_SESSION))
    {
        header('Location: connexion');
    }
?>