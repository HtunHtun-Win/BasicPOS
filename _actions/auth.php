<?php
    session_start();

    function logged_in(){
        if ($_SESSION['logged_in']) {
            header('Location: /sales.php');
        }
    }

    function check_auth(){
        if (!$_SESSION['logged_in']) {
            header('Location: /index.php');
        }
    }

    function check_privilege()
    {
        if ($_SESSION['user_role']!=1) {
            header('Location: /index.php');
        }
    }