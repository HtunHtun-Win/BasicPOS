<?php
    require 'auth.php';
    require '../config/config.php';
    check_auth();
    check_privilege();

    if($_GET['id']){
        $delSql = "UPDATE income_expense SET isdeleted=1 WHERE id=".$_GET['id'];
        $delPdo = $pdo->prepare($delSql);
        $delPdo->execute();
    }