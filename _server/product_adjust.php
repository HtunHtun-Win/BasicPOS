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
        $items = $_SESSION['sitem'];
    } else if (!isset($_SESSION['sitem'][$id])) {
        $_SESSION['sitem'][$id] = 0;
        $items = $_SESSION['sitem'];
    } else {
        $_SESSION['sitem'][$id] += 1;
        $items = $_SESSION['sitem'];
    }
}

if ($_GET) {
    //submit data
    if(isset($_GET['save'])){
        $items = $_SESSION['sitem'];
        $svSql = "UPDATE products SET quantity=quantity+:qty WHERE id=:pid";
        $svPdo = $pdo->prepare($svSql);
        $logSql = "INSERT INTO product_log(product_id,quantity,note,user_id) VALUES(:pid,:qty,:note,:user_id)";
        $logPdo = $pdo->prepare($logSql);
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
        }
        unset($_SESSION['sitem']);
        die();
    }else if (isset($_GET['del_id'])) {//remove item
        $pid = $_GET['del_id'];
        unset($_SESSION['sitem'][$pid]);
    }else{
        //add quantity
        $pid = $_GET['id']; // product id
        $temp_qty = $_GET['qty']; // product quantity
        $_SESSION['sitem'][$pid] = $temp_qty;
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
                <input type="number" class="form-control" value="<?= $qty ?>" onkeyup="addQuantity(<?= $product->id ?>,this.value)">
            </td>
            <td id="new-qty"></td>
            <td>
                <a type="submit" onclick="deleteProduct(<?= $product->id ?>)">
                    <i class='fa fa-trash'></i>
                </a>
            </td>
        </tr>
    <?php $no++;
    endforeach; ?>
</tbody>