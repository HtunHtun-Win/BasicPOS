<?php
require '../_actions/auth.php';
require '../config/config.php';
check_auth();
check_privilege();
//user list query
if ($_GET['search']) {
    $search = $_GET['search'];
    $productSql = "SELECT * FROM products WHERE isdeleted=0 AND (name LIKE '%$search%' OR code LIKE '%$search%')";
    $productPdo = $pdo->prepare($productSql);
    $productPdo->execute();
    $products = $productPdo->fetchAll(PDO::FETCH_OBJ);
} else {
    $productSql = "SELECT * FROM products WHERE isdeleted=0";
    $productPdo = $pdo->prepare($productSql);
    $productPdo->execute();
    $products = $productPdo->fetchAll(PDO::FETCH_OBJ);
}
if (!$products) {
    die();
}
$no = 1;
?>
<tbody>
    <?php foreach ($products as $product) :
        $catSql = "SELECT * FROM categories WHERE id=$product->category_id";
        $catPdo = $pdo->prepare($catSql);
        $catPdo->execute();
        $category = $catPdo->fetchObject();
    ?>
        <tr>
            <td><?= $no ?></td>
            <td><?= $product->code ?></td>
            <td><?= $product->name ?></td>
            <td><?= $product->description ?></td>
            <td><?= $category->name ?></td>
            <td><?= $product->quantity ?></td>
            <td><?= $product->purchase_price ?></td>
            <td><?= $product->sale_price ?></td>
            <td>
                <a href="/product_add_update.php?id=<?= $product->id ?>">
                    <i class='fa fa-edit'></i>
                </a>
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