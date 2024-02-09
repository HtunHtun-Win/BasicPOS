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
        $items = $_SESSION['sale-item'];
    } else if (!isset($_SESSION['sale-item'][$id])) {
        //get item init price
        $sql = "SELECT sale_price FROM products WHERE id=$id";
        $pricePdo = $pdo->prepare($sql);
        $pricePdo->execute();
        $price = $pricePdo->fetchObject();
        //
        $_SESSION['sale-item'][$id] = [1, $price->sale_price];
        $items = $_SESSION['sale-item'];
    } else {
        $_SESSION['sale-item'][$id][0] += 1;
        $items = $_SESSION['sale-item'];
    }
}
//increase quantity and change price
if ($_GET) {
    if (isset($_GET['del_id'])) { //remove item
        $pid = $_GET['del_id'];
        unset($_SESSION['sale-item'][$pid]);
    } else if (isset($_GET['price'])) {
        //add price
        $pid = $_GET['id']; // product id
        $temp_price = $_GET['price']; // price
        $_SESSION['sale-item'][$pid][1] = $temp_price;
    } else {
        //add quantity
        $pid = $_GET['id']; // product id
        $temp_qty = $_GET['qty']; // product quantity
        $_SESSION['sale-item'][$pid][0] = $temp_qty;
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