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
        $insDetailSql = "INSERT INTO sales_detail(sales_id,product_id,quantity,price,pprice) VALUES($sale_id,:pid,:qty,:price,:pprice)";
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
        //update purchase price quantity
        $upPpriceSql = "UPDATE purchase_price SET quantity=quantity+:qty WHERE product_id=:pid AND price=:pprice ORDER BY id DESC LIMIT 1";
        $upPpricePdo = $pdo->prepare($upPpriceSql);
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
                        //update purchase price quantity log
                        $upPpricePdo->execute([
                            ':qty' => $product['quantity'],
                            ':pid' => $productId,
                            ':pprice' => $product['pprice'],
                        ]);
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
                            //add purchase price quantity
                            if ($adjQty > 0) {
                                $upPpricePdo->execute([
                                    ':qty' => $adjQty,
                                    ':pid' => $pid,
                                    ':pprice' => $product['pprice'],
                                ]);
                            } else {
                                $selected_qty = abs($adjQty);
                                while ($selected_qty > 0) {
                                    //get purchase price
                                    $ppSql = "SELECT quantity FROM purchase_price WHERE product_id=$pid AND quantity!=0 ORDER BY id DESC LIMIT 1";
                                    $ppPdo = $pdo->prepare($ppSql);
                                    $ppPdo->execute();
                                    $pPrice = $ppPdo->fetchObject();
                                    //
                                    if ($pPrice->quantity >= $selected_qty) {
                                        $upPpricePdo->execute([
                                            ':qty' => -$selected_qty,
                                            ':pid' => $pid,
                                            ':pprice' => $product['pprice'],
                                        ]);
                                        $selected_qty = 0;
                                    } else {
                                        $upPpricePdo->execute([
                                            ':qty' => -$pPrice->quantity,
                                            ':pid' => $pid,
                                            ':pprice' => $product['pprice'],
                                        ]);
                                        $selected_qty = $selected_qty - $pPrice->quantity;
                                    }
                                }
                            }
                        }
                    }
                }
            }else{
                //add new item to voucher
                $selected_qty = $value[0];
                while ($selected_qty > 0) {
                    //get purchase price
                    $ppSql = "SELECT quantity, price FROM purchase_price WHERE product_id=$pid AND quantity!=0 ORDER BY id LIMIT 1";
                    $ppPdo = $pdo->prepare($ppSql);
                    $ppPdo->execute();
                    $pPrice = $ppPdo->fetchObject();
                    //inset sale detail
                    if ($pPrice->quantity >= $selected_qty) {
                        $insDetailPdo->execute([
                            ':pid' => $pid,
                            ':qty' => $selected_qty,
                            ':price' => $value[1],
                            ':pprice' => $pPrice->price
                        ]);
                        $upPpricePdo->execute([
                            ':qty' => -$selected_qty,
                            ':pid' => $pid,
                            ':pprice' => $pPrice->price
                        ]);
                        $selected_qty = 0;
                    } else {
                        $insDetailPdo->execute([
                            ':pid' => $pid,
                            ':qty' => $pPrice->quantity,
                            ':price' => $value[1],
                            ':pprice' => $pPrice->price
                        ]);
                        $upPpricePdo->execute([
                            ':qty' => -$pPrice->quantity,
                            ':pid' => $pid,
                            ':pprice' => $pPrice->price
                        ]);
                        $selected_qty = $selected_qty - $pPrice->quantity;
                    }
                }
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