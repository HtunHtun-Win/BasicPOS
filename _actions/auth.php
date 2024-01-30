<?php
    session_start();

    function logged_in(){
        if ($_SESSION['logged_in']) {
            header('Location: /home.php');
        }
    }

    function check_auth(){
        if (!$_SESSION['logged_in']) {
            header('Location: /index.php');
        }
    }