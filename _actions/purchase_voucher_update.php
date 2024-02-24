<?php
require '../_actions/auth.php';
require '../config/config.php';
check_auth();
$user_id = $_SESSION['user_id'];
$items = $_SESSION['sale-item'];
//add item
if (isset($_POST)) {
    if ($_POST['sale_id']) {
        $sale_id = $_POST['sale_id'];
        //sale voucher
        if (!$_SESSION['sale-item']) {
            die();
        }
        $customer_id = $_POST['customerId'];
        $netPrice = $_POST['netPrice'];
        $discount = $_POST['discount'];
        $totalPrice = $_POST['totPrice'];
        //update sale voucher
        $saleSql = "UPDATE sales SET customer_id=:customer_id, net_price=:netPrice, discount=:discount, total_price=:totalPrice WHERE id=:id";
        $salePdo = $pdo->prepare($saleSql);
        $salePdo->execute([
            ':customer_id' => $customer_id,
            ':netPrice' => $netPrice,
            ':discount' => $discount,
            ':totalPrice' => $totalPrice,
            ':id' => $sale_id,
        ]);
        //Update sale detail if product exist
        $upSql = "UPDATE sales_detail SET quantity=:qty, price=:price WHERE sales_id=:saleId AND product_id=:pid";
        $upPdo = $pdo->prepare($upSql);
        //Insert new product in existing voucher
        $insDetailSql = "INSERT INTO sales_detail(sales_id,product_id,quantity,price) VALUES($sale_id,:pid,:qty,:price)";
        $insDetailPdo = $pdo->prepare($insDetailSql);
        //Update product quantity and add log
        $svSql = "UPDATE products SET quantity=quantity+:qty WHERE id=:pid";
        $svPdo = $pdo->prepare($svSql);
        $logSql = "INSERT INTO product_log(product_id,quantity,note,user_id) VALUES(:pid,:qty,:note,:user_id)";
        $logPdo = $pdo->prepare($logSql);
        //remove product item
        $rmSql = "DELETE FROM sales_detail WHERE sales_id=:sale_id AND product_id=:product_id";
        $rmPdo = $pdo->prepare($rmSql);
        //add remove log
        $rmLogSql = "INSERT INTO removed_item(sales_id,product_id) VALUES(:sale_id,:product_id)";
        $rmLogPdo = $pdo->prepare($rmLogSql);
        //get all product from sale voucher
        $getSql = "SELECT * FROM sales_detail WHERE sales_id=$sale_id";
        $getPdo = $pdo->prepare($getSql);
        $getPdo->execute();
        $products = $getPdo->fetchAll();
        $productIds = [];
        $temp_pid = [];
        foreach($products as $product){
            array_push($productIds,$product['product_id']);
        }
        foreach($items as $pid => $value){
            array_push($temp_pid,$pid);
        }
        //execute Query to remove
        foreach($productIds as $productId){
            $flag = array_search($productId, $temp_pid);
            if(strlen($flag)==0){
                //remove item form sale detail
                $rmPdo->execute([
                    ':sale_id' => $sale_id,
                    ':product_id' => $productId,
                ]);
                //add log remove 
                $rmLogPdo->execute([
                    ':sale_id' => $sale_id,
                    ':product_id' => $productId,
                ]);
                //add product and log
                foreach($products as $product){
                    if($product['product_id']==$productId){
                        //update product quantity
                        $svPdo->execute([
                            ':qty' => $product['quantity'],
                            ':pid' => $productId
                        ]);
                        //add log
                        $logPdo->execute([
                            ':pid' => $productId,
                            ':qty' => +$product['quantity'],
                            ':note' => "sale item remove",
                            ':user_id' => $user_id
                        ]);
                    }
                }
            }
        }
        //execute Query to update and insert
        foreach($items as $pid=>$value){
            $flag = array_search($pid, $productIds);
            if(strlen($flag)!=0){
                $upPdo->execute([
                    ':qty' => $value[0],
                    ':price' => $value[1],
                    ':saleId' => $sale_id,
                    ':pid' => $pid
                ]);
                //adjust product and log
                foreach ($products as $product) {
                    if ($product['product_id'] == $pid) {
                        if($product['quantity'] != $value[0]){
                            $adjQty = $product['quantity'] - $value[0];
                            //update product quantity
                            $svPdo->execute([
                                ':qty' => $adjQty,
                                ':pid' => $pid
                            ]);
                            //add log
                            $logPdo->execute([
                                ':pid' => $pid,
                                ':qty' => $adjQty,
                                ':note' => "sale item adjust",
                                ':user_id' => $user_id
                            ]);
                        }
                    }
                }
            }else{
                //add new item to voucher
                $insDetailPdo->execute([
                    ':pid' => $pid,
                    ':qty' => $value[0],
                    ':price' => $value[1]
                ]);
                //update product quantity
                $svPdo->execute([
                    ':qty' => -$value[0],
                    ':pid' => $pid
                ]);
                //add log
                $logPdo->execute([
                    ':pid' => $pid,
                    ':qty' => -$value[0],
                    ':note' => "sale",
                    ':user_id' => $user_id
                ]);
            }
            
        }
        unset($_SESSION['sale-item']);
        unset($_SESSION['sale_id']);
    }
}