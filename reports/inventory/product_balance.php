<?php
require '../../_actions/auth.php';
require '../../config/config.php';
check_auth();
// all stock balance
$getSql = "SELECT * FROM products ORDER BY name";
$getPdo = $pdo->prepare($getSql);
$getPdo->execute();
$products = $getPdo->fetchAll(PDO::FETCH_OBJ);
//
$totalQty = 0;
$totalAmount = 0;
$no = 1;
//
$_SESSION['report'] = 'product-balance';
//
?>
<table class="table">
    <thead class="thead-dark">
        <th>No</th>
        <th>Code</th>
        <th>Name</th>
        <th>Quantity</th>
    </thead>
    <tbody>
        <?php foreach ($products as $product) : ?>
            <tr>
                <td><?= $no ?></td>
                <td><?= $product->code ?></td>
                <td><?= $product->name ?></td>
                <td width="150px"><?= $product->quantity ?></td>
            </tr>
        <?php
            $totalQty += $product->quantity;
            $no++;
        endforeach;
        ?>
        <tr class="table-active">
            <td colspan="3">
                <center>
                    <b>Total</b>
                </center>
            </td>
            <td><b><?= $totalQty ?></b></td>
        </tr>
    </tbody>
</table>