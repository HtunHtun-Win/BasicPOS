<?php
    require "../config/config.php";
    require "auth.php";
    check_auth();
    check_privilege();
    if($_POST){
        $name = $_POST['name'];
        $code = $_POST['code'];
        $pprice = $_POST['pprice'];
        $sprice = $_POST['sprice'];
        $category_id = $_POST['category'];
        $quantity = $_POST['quantity'] ?? 0;
        $desc = $_POST['description'] ?? '';
        $user_id = $_SESSION['user_id'];
        //check duplicate product
        if($_POST['id']){
            $id = $_POST['id'];
            $dupSql = "SELECT * FROM products WHERE code=:code AND isdeleted=0 AND id!=$id";
        }else{
            $dupSql = "SELECT * FROM products WHERE code=:code AND isdeleted=0";
        }
        $dupPdo = $pdo->prepare($dupSql);
        $dupPdo->execute([':code'=>$code]);
        $result = $dupPdo->fetchObject();
        if ($result) {
            echo "exist";
            die();
        }
        if(!$_POST['id']){
            //insert new product
            $addSql = "INSERT INTO products (code, name, description, quantity, category_id, purchase_price, sale_price) VALUES (:code,:name, :desc, :quantity, :category_id,:pprice,:sprice)";
            $addPdo = $pdo->prepare($addSql);
            //product log
            $logSql = "INSERT INTO product_log(product_id,quantity,note,user_id) VALUES(:pid,:qty,:note,:user_id)";
            $logPdo = $pdo->prepare($logSql);
            //add purchase price and quantity log
            $pPriceSql = "INSERT INTO purchase_price(product_id,quantity,price) VALUES(:pid,:qty,:pprice)";
            $pPricePdo = $pdo->prepare($pPriceSql);
            //
            try{
                $addPdo->execute([
                    ':code' => $code,
                    ':name' => $name,
                    ':desc' => $desc,
                    ':quantity' => $quantity,
                    ':category_id' => $category_id,
                    ':pprice' => $pprice,
                    ':sprice' => $sprice
                ]);
                $last_product_id = $pdo->lastInsertId();
                //add product log
                $logPdo->execute([
                    ':pid' => $last_product_id,
                    ':qty' => $quantity,
                    ':note' => "Initial Quantity",
                    ':user_id' => $user_id
                ]);
                //add purchase price log
                $pPricePdo->execute([
                    ':pid' => $last_product_id,
                    ':qty' => $quantity,
                    ':pprice' => $pprice
                ]);
                echo "success";
            }catch(Exception $e){
                echo $e;
            }
        }else{
            $id = $_POST['id'];
            $updateSql = "UPDATE products SET code=:code, name=:name, category_id=:category_id, description=:desc WHERE id=:id";
            $updatePdo = $pdo->prepare($updateSql);
            $updatePdo->execute([
                ':code' => $code,
                ':name' => $name,
                ':desc' => $desc,
                ':category_id' => $category_id,
                ':id' => $id
            ]);
            echo "update";
        }
    }
?>