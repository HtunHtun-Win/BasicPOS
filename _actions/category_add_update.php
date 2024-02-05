<?php
    require 'auth.php';
    require '../config/config.php';
    check_auth();

    if($_POST){
        $name = $_POST['name'];
        $desc = $_POST['description'];
        //check duplicate category
        if($_POST['id']){
            $id = $_POST['id'];
            $chkSql = "SELECT * FROM categories where name=:name AND isdeleted=0 AND id!=$id";
        }else{
            $chkSql = "SELECT * FROM categories where name=:name AND isdeleted=0";
        }
        $chkPdo = $pdo->prepare($chkSql);
        $chkPdo->execute([':name' => $name]);
        $result = $chkPdo->fetchObject();
        if ($result) {
            echo "exist";
            die();
        }
        if(!$_POST['id']){//Inset new user
            $addsql = "INSERT INTO categories (name,description) VALUES (:name,:description)";
            $addPdo = $pdo->prepare($addsql);
            $addPdo->execute([
                ':name' => $name,
                ':description' => $desc
            ]);
            echo "success";
        }else{
        $id = $_POST['id'];
        $updatesql = "UPDATE categories SET name=:name,description=:description WHERE id=:id";
        $upPdo = $pdo->prepare($updatesql);
        $upPdo->execute([
            ':name' => $name,
            ':description' => $desc,
            ':id' => $id,
        ]);
        echo "success";
        }
    }