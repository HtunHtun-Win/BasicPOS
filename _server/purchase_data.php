<?php
require '../_actions/auth.php';
require '../config/config.php';
check_auth();
$user_id = $_SESSION['user_id'];

//add item
if (isset($_POST)) {
    if ($_POST['supplierId']) {
        //sale voucher
        if (!$_SESSION['purchase-item']) {
            die();
        }
        $supplier_id = $_POST['supplierId'];
        $user_id = $_SESSION['user_id'];
        $netPrice = $_POST['netPrice'];
        $discount = $_POST['discount'];
        $totalPrice = $_POST['totPrice'];
        //get Invoice No.
        $invSql = "SELECT * FROM gen_id WHERE id=2";
        $invPdo = $pdo->prepare($invSql);
        $invPdo->execute();
        $invObj = $invPdo->fetchObject();
        $invNo = "";
        for ($digit = strlen(strval($invObj->no + 1)); $digit < $invObj->digit; $digit++) {
            $invNo .= "0";
        }
        $invoiceNo = $invObj->prefix . $invNo . ($invObj->no + 1);
        //insert sale voucher
        $purchaseSql = "INSERT INTO purchase (purchase_no,supplier_id,user_id,net_price,discount,total_price) VALUES('$invoiceNo',$supplier_id,$user_id,$netPrice,$discount,$totalPrice)";
        $purchasePdo = $pdo->prepare($purchaseSql);
        $purchasePdo->execute();
        $last_sale_id = $pdo->lastInsertID();
        //update voucher no
        $updateVnoSql = "UPDATE gen_id SET no=no+1 WHERE id=2";
        $updateVPdo = $pdo->prepare($updateVnoSql);
        $updateVPdo->execute();
        //sale voucher detail
        $items = $_SESSION['purchase-item'];
        $vDetailSql = "INSERT INTO purchase_detail(purchase_id,product_id,quantity,price) VALUES($last_sale_id,:pid,:qty,:price)";
        $vDetailPdo = $pdo->prepare($vDetailSql);
        //Update product quantity and add log
        $svSql = "UPDATE products SET quantity=quantity+:qty, purchase_price=:pprice WHERE id=:pid";
        $svPdo = $pdo->prepare($svSql);
        $logSql = "INSERT INTO product_log(product_id,quantity,note,user_id) VALUES(:pid,:qty,:note,:user_id)";
        $logPdo = $pdo->prepare($logSql);
        //add purchase price and quantity log
        $pPriceSql = "INSERT INTO purchase_price(product_id,quantity,price) VALUES(:pid,:qty,:pprice)";
        $pPricePdo = $pdo->prepare($pPriceSql);
        //execute
        foreach ($items as $id => $value) {
            //inset sale detail
            $vDetailPdo->execute([
                ':pid' => $id,
                ':qty' => $value[0],
                ':price' => $value[1]
            ]);
            //update product quantity
            $svPdo->execute([
                ':qty' => $value[0],
                ':pprice' => $value[1],
                ':pid' => $id
            ]);
            //add log
            $logPdo->execute([
                ':pid' => $id,
                ':qty' => +$value[0],
                ':note' => "purchase",
                ':user_id' => $user_id
            ]);
            //add purchase price log
            $pPricePdo->execute([
                ':pid' => $id,
                ':qty' => $value[0],
                ':pprice' => $value[1]
            ]);
        }
        unset($_SESSION['purchase-item']);
        die();
    } else {
        $id = $_POST['item_id'];
        if ($id == 0) {
            $items = $_SESSION['purchase-item'];
        } else if (!isset($_SESSION['purchase-item'][$id])) {
            //get item init price
            $sql = "SELECT purchase_price FROM products WHERE id=$id";
            $pricePdo = $pdo->prepare($sql);
            $pricePdo->execute();
            $price = $pricePdo->fetchObject();
            //
            $_SESSION['purchase-item'][$id] = [1, $price->purchase_price];
            $items = $_SESSION['purchase-item'];
        } else {
            $_SESSION['purchase-item'][$id][0] += 1;
            $items = $_SESSION['purchase-item'];
        }
    }
}
//increase quantity and change price
if ($_GET) {
    if (isset($_GET['del_id'])) { //remove item
        $pid = $_GET['del_id'];
        unset($_SESSION['purchase-item'][$pid]);
    } else if (isset($_GET['price'])) {
        //add price
        $pid = $_GET['id']; // product id
        $temp_price = $_GET['price']; // price
        $_SESSION['purchase-item'][$pid][1] = $temp_price;
    } else {
        //add quantity
        $pid = $_GET['id']; // product id
        $temp_qty = $_GET['qty']; // product quantity
        $_SESSION['purchase-item'][$pid][0] = $temp_qty;
    }
}

$no = 1;
?>
<tbody>
    <?php foreach ($items as $key => $value) :
        $id = $key;
        $productSql = "SELECT * FROM products WHERE isdeleted=0 AND id=$id";
        $productPdo = $pdo->prepare($productSql);
        $productPdo->execute();
        $product = $productPdo->fetchObject();
        $totalAmount = 0;
    ?>
        <tr>
            <td><?= $no ?></td>
            <td><?= $product->code ?></td>
            <td><?= $product->name ?></td>
            <td>
                <input type="number" class="form-control" value="<?= $value[0] ?>" onfocusout="addQuantity(<?= $product->id ?>,this.value)">
            </td>
            <td>
                <input type="number" class="form-control" value="<?= $value[1] ?>" onfocusout="addPrice(<?= $product->id ?>,this.value)">
            </td>
            <td id="amount" class="c-amount"><?= $value[0] * $value[1] ?></td>
            <td>
                <a type="submit" onclick="deleteProduct(<?= $product->id ?>)">
                    <i class='fa fa-trash'></i>
                </a>
            </td>
        </tr>
    <?php
        $no++;
    endforeach; ?>
</tbody>