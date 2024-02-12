<?php
    require 'auth.php';
    require '../config/config.php';
    check_auth();
    //
    if($_POST){
        $type = $_POST['type'];
        $amount = $_POST['amount'];
        $desc = $_POST['desc'];
        $note = $_POST['note'];
        $user_id = $_SESSION['user_id'];
        //check blank
        if(strlen($amount)==0 or strlen($desc)==0 or strlen($note)==0){
            echo "require";
            die();
        }
        if(!$_POST['id']) {
            //Inset new data
            try{
                $addsql = "INSERT INTO income_expense (amount,description,note,flow_type_id,user_id) VALUES (:amount,:description,:note,:flow_type_id,:user_id)";
                $addPdo = $pdo->prepare($addsql);
                $addPdo->execute([
                    ':amount' => $amount,
                    ':description' => $desc,
                    ':note' => $note,
                    ':flow_type_id' => $type,
                    ':user_id' => $user_id,
                ]);
                echo "success";
            }catch(Exception $e){
                echo $e;
            }
        }else{
            //update data
            $id = $_POST['id'];
        }
    }

