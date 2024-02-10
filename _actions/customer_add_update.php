<?php
    require 'auth.php';
    require '../config/config.php';
    check_auth();

    if($_POST){
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        if(strlen($name)==0 or strlen($phone)==0 or strlen($address)==0){
            echo "require";
            die();
        }
        if(!$_POST['id']) {//check duplicate login_id
            $chkSql = "SELECT * FROM customers WHERE name=:name AND phone=:phone AND isdeleted=0";
            $chkPdo = $pdo->prepare($chkSql);
            $chkPdo->execute([
                ':name'=>$name,
                ':phone' => $phone,
            ]);
            $result = $chkPdo->fetchObject();
            if($result){
                echo "exist";
                die();
            }
            //Inset new user
            try{
                $addsql = "INSERT INTO customers (name,phone,address) VALUES (:name,:phone,:address)";
                $addPdo = $pdo->prepare($addsql);
                $addPdo->execute([
                    ':name' => $name,
                    ':phone' => $phone,
                    ':address' => $address
                ]);
                echo "success";
            }catch(Exception $e){
                echo $e;
            }
        }else{
            $id = $_POST['id'];
            $updatesql = "UPDATE customers SET name=:name,phone=:phone,address=:address WHERE id=:id";
            $upPdo = $pdo->prepare($updatesql);
            $upPdo->execute([
                ':name' => $name,
                ':phone' => $phone,
                ':address' => $address,
                ':id' => $id,
            ]);
        }
    }

