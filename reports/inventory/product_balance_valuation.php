<?php
require '../../_actions/auth.php';
require '../../config/config.php';
check_auth();
// all stock balance
$getSql = "SELECT * FROM products ORDER BY name";
$getPdo = $pdo->prepare($getSql);
$getPdo->execute();
$products = $getPdo->fetchAll(PDO::FETCH_OBJ);
//get total amount
$getAmountSql = "SELECT SUM(quantity*price) as amount from purchase_price WHERE product_id=:pid";
$getAmountPdo = $pdo->prepare($getAmountSql);
//
$totalQty = 0;
$totalAmount = 0;
$no = 1;
//
$_SESSION['report'] = 'product-balance-valuation';
//
?>
<table class="table">
    <thead class="thead-dark">
        <th>No</th>
        <th>Code</th>
        <th>Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Amount</th>
    </thead>
    <tbody>
        <?php
            foreach ($products as $product) :
            $getAmountPdo->execute([':pid'=>$product->id]);
            $amount = $getAmountPdo->fetchObject();
        ?>
            <tr>
                <td><?= $no ?></td>
                <td><?= $product->code ?></td>
                <td><?= $product->name ?></td>
                <td width="150px"><?= $product->quantity ?></td>
                <td width="150px"><?= $product->purchase_price ?></td>
                <td width="150px"><?= $amount->amount ?></td>
            </tr>
        <?php
            $totalQty += $product->quantity;
            $totalAmount += $amount->amount;
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
            <td></td>
            <td><b><?= $totalAmount ?></b></td>
        </tr>
    </tbody>
</table>