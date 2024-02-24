<?php
require '../_actions/auth.php';
require '../config/config.php';
check_auth();
check_privilege();
$user_id = $_SESSION['user_id'];
//add item
if (isset($_POST)) {
    $id = $_POST['item_id'];
    if ($id == 0) {
        $items = $_SESSION['adj-item'];
    } else if (!isset($_SESSION['adj-item'][$id])) {
        $_SESSION['adj-item'][$id] = 0;
        $items = $_SESSION['adj-item'];
    } else {
        $_SESSION['adj-item'][$id] += 1;
        $items = $_SESSION['adj-item'];
    }
}

if ($_GET) {
    //submit data
    if(isset($_GET['save'])){
        $items = $_SESSION['adj-item'];
        //Update product quantity and add log
        $svSql = "UPDATE products SET quantity=quantity+:qty WHERE id=:pid";
        $svPdo = $pdo->prepare($svSql);
        $logSql = "INSERT INTO product_log(product_id,quantity,note,user_id) VALUES(:pid,:qty,:note,:user_id)";
        $logPdo = $pdo->prepare($logSql);
        //update purchase price quantity
        $upPpriceSql = "UPDATE purchase_price SET quantity=quantity+:qty WHERE product_id=:pid AND quantity!=0 ORDER BY id DESC LIMIT 1";
        $upPpricePdo = $pdo->prepare($upPpriceSql);
        //execute
        foreach($items as $id => $qty){
            $svPdo->execute([
                ':qty' => $qty,
                ':pid' => $id
            ]);
            $logPdo->execute([
                ':pid' => $id,
                ':qty' => $qty,
                ':note' => "adjust",
                ':user_id' => $user_id
            ]);
            //purchase price qty change
            if($qty > 0){
                $upPpricePdo->execute([
                    ':qty' => $qty,
                    ':pid' => $id
                ]);
            }else{
                $selected_qty = abs($qty);
                while($selected_qty > 0){
                    //get purchase price
                    $ppSql = "SELECT quantity FROM purchase_price WHERE product_id=$id AND quantity!=0 ORDER BY id DESC LIMIT 1";
                    $ppPdo = $pdo->prepare($ppSql);
                    $ppPdo->execute();
                    $pPrice = $ppPdo->fetchObject();
                    //
                    if ($pPrice->quantity >= $selected_qty) {
                        $upPpricePdo->execute([
                            ':qty' => -$selected_qty,
                            ':pid' => $id
                        ]);
                        $selected_qty = 0;
                    } else {
                        $upPpricePdo->execute([
                            ':qty' => -$pPrice->quantity,
                            ':pid' => $id
                        ]);
                        $selected_qty = $selected_qty - $pPrice->quantity;
                    }
                }
            }
        }
        unset($_SESSION['adj-item']);
        die();
    }else if (isset($_GET['del_id'])) {//remove item
        $pid = $_GET['del_id'];
        unset($_SESSION['adj-item'][$pid]);
    }else{
        //add quantity
        $pid = $_GET['id']; // product id
        $temp_qty = $_GET['qty']; // product quantity
        $_SESSION['adj-item'][$pid] = $temp_qty;
    }
}

$no = 1;
?>
<tbody>
    <?php foreach ($items as $key => $qty) :
        $id = $key;
        $productSql = "SELECT * FROM products WHERE isdeleted=0 AND id=$id";
        $productPdo = $pdo->prepare($productSql);
        $productPdo->execute();
        $product = $productPdo->fetchObject();
    ?>
        <tr>
            <td><?= $no ?></td>
            <td><?= $product->code ?></td>
            <td><?= $product->name ?></td>
            <td><?= $product->quantity ?></td>
            <td>
                <input type="number" class="form-control" value="<?= $qty ?>" onfocusout="addQuantity(<?= $product->id ?>,this.value)">
            </td>
            <td id="new-qty"><?= $product->quantity+$qty ?></td>
            <td>
                <a type="submit" onclick="deleteProduct(<?= $product->id ?>)">
                    <i class='fa fa-trash'></i>
                </a>
            </td>
        </tr>
    <?php $no++;
    endforeach; ?>
</tbody>