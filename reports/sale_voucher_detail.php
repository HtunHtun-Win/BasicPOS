<?php
require '../_actions/auth.php';
require '../config/config.php';
check_auth();
//get voucher info
if ($_GET) {
    $vid = $_GET['vid'];
    $vinfoSql = "SELECT * FROM sales WHERE id=$vid";
    $vinfoPdo = $pdo->prepare($vinfoSql);
    $vinfoPdo->execute();
    $voucher = $vinfoPdo->fetchObject();
    //get customer name
    $custSql = "SELECT * FROM customers WHERE id=$voucher->customer_id";
    $custPdo = $pdo->prepare($custSql);
    $custPdo->execute();
    $customer = $custPdo->fetchObject();
    //get staff name
    $userSql = "SELECT * FROM users WHERE id=$voucher->user_id";
    $userPdo = $pdo->prepare($userSql);
    $userPdo->execute();
    $user = $userPdo->fetchObject();
}
$no = 1;
?>

<div class="mt-3">
    <center>
        CompanyName <br>
        Address <br>
        Phone
        <hr>
    </center>
    <div class="row">
        <div class="col-md-6">
            Invoive-No : <?= $voucher->sale_no ?>
            <br>
            Customer : <?= $customer->name ?>
        </div>
        <div class="col-md-6">
            Date: <?= $voucher->created_at ?>
            <br>
            Staff: <?= $user->name ?>
        </div>
    </div>
    <!-- get items in voucher -->
    <?php
    $itemSql = "SELECT products.name,sales_detail.quantity,sales_detail.price FROM sales_detail LEFT JOIN products ON sales_detail.product_id=products.id WHERE sales_detail.sales_id=$vid";
    $itemPdo = $pdo->prepare($itemSql);
    $itemPdo->execute();
    $items = $itemPdo->fetchAll(PDO::FETCH_OBJ);
    $totalAmount = 0;
    ?>
    <table class="table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Item</th>
                <th>Price</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item) : ?>
                <tr>
                    <td> <?= $no ?></td>
                    <td> <?= $item->name ?></td>
                    <td> <?= $item->quantity . "($item->price)" ?></td>
                    <td> <?= $item->quantity * $item->price ?></td>
                </tr>
            <?php $no++;
                $totalAmount += $item->quantity * $item->price;
            endforeach; ?>
            <tr>
                <td></td>
                <td></td>
                <td>Total Amount</td>
                <td><?= $totalAmount ?></td>
            </tr>
        </tbody>
    </table>
</div>