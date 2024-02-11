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
        $items = $_SESSION['price-item'];
    } else if (!isset($_SESSION['price-item'][$id])) {
        $_SESSION['price-item'][$id] = 0;
        $items = $_SESSION['price-item'];
    }
}

if ($_GET) {
    //submit data
    if(isset($_GET['save'])){
        $items = $_SESSION['price-item'];
        //Update product sale price and add log
        $updatePriceSql = "UPDATE products SET sale_price=:price WHERE id=:pid";
        $updatePricePdo = $pdo->prepare($updatePriceSql);
        $priceLogSql = "INSERT INTO sale_price_log(product_id,old_price,new_price) VALUES(:pid,:old_price,:new_price)";
        $priceLogPdo = $pdo->prepare($priceLogSql);
        foreach($items as $id => $price){
            //get old price
            $productSql = "SELECT sale_price FROM products WHERE isdeleted=0 AND id=$id";
            $productPdo = $pdo->prepare($productSql);
            $productPdo->execute();
            $product = $productPdo->fetchObject();
            //update section
            $updatePricePdo->execute([
                ':price' => $price,
                ':pid' => $id
            ]);
            $priceLogPdo->execute([
                ':pid' => $id,
                ':old_price' => $product->sale_price,
                ':new_price' => $price,
            ]);
        }
        unset($_SESSION['price-item']);
        die();
    }else if (isset($_GET['del_id'])) {//remove item
        $pid = $_GET['del_id'];
        unset($_SESSION['price-item'][$pid]);
    }else{
        //add price
        $pid = $_GET['id']; // product id
        $temp_price = $_GET['price']; // product price
        $_SESSION['price-item'][$pid] = $temp_price;
    }
}

$no = 1;
?>
<tbody>
    <?php foreach ($items as $key => $price) :
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
            <td><?= $product->sale_price ?></td>
            <td>
                <input type="number" class="form-control" value="<?= $price ?>" onfocusout="addPrice(<?= $product->id ?>,this.value)">
            </td>
            <td>
                <a type="submit" onclick="deleteProduct(<?= $product->id ?>)">
                    <i class='fa fa-trash'></i>
                </a>
            </td>
        </tr>
    <?php $no++;
    endforeach; ?>
</tbody>