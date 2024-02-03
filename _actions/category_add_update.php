<?php
    require 'auth.php';
    require '../config/config.php';
    check_auth();

    if($_POST){
        $name = $_POST['name'];
        $desc = $_POST['description'];
        if(!$_POST['id']) {//check duplicate login_id
            $chkSql = "SELECT * FROM categories where name=:name";
            $chkPdo = $pdo->prepare($chkSql);
            $chkPdo->execute([':name'=> $name]);
            $result = $chkPdo->fetchObject();
            if($result){
                echo "exits category";
                $_SESSION['msg'] = "msg";
                header('Location: ../category.php');
            }else {//Inset new user
                $addsql = "INSERT INTO categories(name,description) VALUES(:name,:description)";
                $addPdo = $pdo->prepare($addsql);
                $addPdo->execute([
                    ':name' => $name,
                    ':description' => $desc
                ]);
                header('Location: ../category.php');
            }
        }else{
            $id = $_POST['id'];
            $updatesql = "UPDATE categories SET name=:name,description=:description WHERE id=:id";
            $upPdo = $pdo->prepare($updatesql);
            $upPdo->execute([
                ':name' => $name,
                ':description' => $desc,
                ':id' => $id,
            ]);
            header('Location: ../category.php');
        }
    }else{
        header('Location: ../category.php');
    }

