<?php
    require 'auth.php';
    require '../config/config.php';
    check_auth();

    if($_POST){
        $name = $_POST['name'];
        $login_id = $_POST['login_id'];
        $password = $_POST['password'];
        $role = $_POST['role_id']>3 ? 2 : $_POST['role_id'];
        if(strlen($name)==0 or strlen($login_id)==0 or strlen($password)==0){
            echo "require";
            die();
        }
        if(!$_POST['id']) {//check duplicate login_id
            $chkSql = "SELECT * FROM users where login_id=:login_id AND isdeleted=0";
            $chkPdo = $pdo->prepare($chkSql);
            $chkPdo->execute([':login_id'=>$login_id]);
            $result = $chkPdo->fetchObject();
            if($result){
                echo "exist";
                die();
            }
            //Inset new user
            try{
                $addsql = "INSERT INTO users (name,login_id,password,role_id) VALUES (:name,:login_id,:password,:role_id)";
                $addPdo = $pdo->prepare($addsql);
                $addPdo->execute([
                    ':name' => $name,
                    ':login_id' => $login_id,
                    ':password' => $password,
                    ':role_id' => $role,
                ]);
                echo "success";
            }catch(Exception $e){
                echo $e;
            }
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
        }
    }

