<?php
    require "../config/config.php";
    require "auth.php";
    check_auth();

    unset($_SESSION['purchase-item']);
    if($_GET){
        $purchase_id = $_GET['purchase_id'];
        $_SESSION['purchase_id'] = $purchase_id;
        $sql = "SELECT * FROM purchase_detail WHERE purchase_id=$purchase_id";
        $pdostatement = $pdo->prepare($sql);
        $pdostatement->execute();
        $items = $pdostatement->fetchAll(PDO::FETCH_OBJ);
        foreach($items as $item){
            $_SESSION['purchase-item'][$item->product_id] = [$item->quantity, $item->price];
        }
    }
?>