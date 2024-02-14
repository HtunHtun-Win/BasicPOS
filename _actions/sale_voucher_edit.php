<?php
    require "../config/config.php";
    require "auth.php";
    check_auth();

    unset($_SESSION['sale-item']);
    if($_GET){
        $sale_id = $_GET['sale_id'];
        $_SESSION['sale_id'] = $sale_id;
        $sql = "SELECT * FROM sales_detail WHERE sales_id=$sale_id";
        $pdostatement = $pdo->prepare($sql);
        $pdostatement->execute();
        $items = $pdostatement->fetchAll(PDO::FETCH_OBJ);
        foreach($items as $item){
            $_SESSION['sale-item'][$item->product_id] = [$item->quantity, $item->price];
        }
    }
?>