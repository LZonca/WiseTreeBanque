<?php
session_start();
    unset($_SESSION['userid']);
    echo "<h1>Deconnexion en cours !</h1>";
    unset($_SESSION['usermessage']);
    session_destroy();
    header('Location: Connexion');

    if(!isset($_SESSION))
    {
        header('Location: Connexion');
    }
?>