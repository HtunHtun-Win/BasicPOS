<?php
    require 'auth.php';
    require '../config/config.php';
    check_auth();
    check_privilege();

    if($_GET['id']){
        $delSql = "UPDATE products SET isdeleted=1 WHERE id=".$_GET['id'];
        $delPdo = $pdo->prepare($delSql);
        $delPdo->execute();
        // header('Location: /user.php');
    }