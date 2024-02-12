<?php
    require "../config/config.php";
    if($_POST){
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        //update shop info
        $sql = "UPDATE shop_info SET shop_name=:name, shop_address=:address, shop_phone=:phone";
        $pdostatement = $pdo->prepare($sql);
        $pdostatement->execute([
            ':name' => $name,
            ':address' => $address,
            ':phone' => $phone
        ]);
    }
?>