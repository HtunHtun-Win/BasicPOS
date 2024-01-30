<?php
    require 'auth.php';
    require '../config/config.php';
    check_auth();

    if($_POST){
        $name = $_POST['name'];
        $login_id = $_POST['login_id'];
        $password = $_POST['password'];
        $role = $_POST['role_id']>3 ? 2 : $_POST['role_id'];
        if(!$_POST['id']){
            // echo $name.$login_id.$password.$role;
            $addsql = "INSERT INTO users(name,login_id,password,role_id) VALUES(:name,:login_id,:password,:role_id)";
            $addPdo = $pdo->prepare($addsql);
            $addPdo->execute([
                ':name' => $name,
                ':login_id' => $login_id,
                ':password' => $password,
                ':role_id' => $role,
            ]);
            header('Location: ../user.php');
        }else{
            $id = $_POST['id'];
            $updatesql = "UPDATE users SET name=:name,login_id=:login_id,password=:password,role_id=:role_id WHERE id=:id";
            $upPdo = $pdo->prepare($updatesql);
            $upPdo->execute([
                ':name' => $name,
                ':login_id' => $login_id,
                ':password' => $password,
                ':role_id' => $role,
                ':id' => $id,
            ]);
            header('Location: ../user.php');
        }
    }else{
        header('Location: ../user.php');
    }

