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
    <?php
    //get shop info
    $shopSql = "SELECT * FROM shop_info";
    $shopPdo = $pdo->prepare($shopSql);
    $shopPdo->execute();
    $shop = $shopPdo->fetchObject();
    ?>
    <center>
        <h4><?= $shop->shop_name ?></h4> <br>
    </center>
    Address&nbsp;: <b><?= $shop->shop_address ?></b> <br>
    Phone&ensp;&ensp;: <b><?= $shop->shop_phone ?></b>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <small>
                Customer : <?= $customer->name ?>
                <br>
                Phone : <?= $customer->phone ?>
                <br>
                Address : <?= $customer->address ?>
            </small>
        </div>
        <div class="col-md-6">
            Invoive-No : <?= $voucher->sale_no ?>
            <br>
            Sale Staff: <?= $user->name ?>
            <br>
            <small>Date: <?= $voucher->created_at ?></small>
        </div>
    </div>
    <!-- get items in voucher -->
    <?php
    $itemSql = "SELECT products.name,SUM(sales_detail.quantity) as quantity,sales_detail.price FROM sales_detail LEFT JOIN products ON sales_detail.product_id=products.id WHERE sales_detail.sales_id=$vid GROUP BY sales_detail.product_id";
    $itemPdo = $pdo->prepare($itemSql);
    $itemPdo->execute();
    $items = $itemPdo->fetchAll(PDO::FETCH_OBJ);
    ?>
    <table class="table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Item</th>
                <th width="200px">Price</th>
                <th width="250px">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item) : ?>
                <tr>
                    <td> <?= $no ?></td>
                    <td> <?= $item->name ?></td>
                    <td> <?= $item->quantity . " x " . "($item->price)" ?></td>
                    <td> <?= $item->quantity * $item->price ?></td>
                </tr>
            <?php $no++;
            endforeach; ?>
            <tr>
                <td></td>
                <td></td>
                <td>Net Amount</td>
                <td><?= $voucher->net_price ?></td>
            </tr>
            <?php if($voucher->discount!=0): ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td>Discount</td>
                    <td><?= $voucher->discount ?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td></td>
                <td></td>
                <td>Total Amount</td>
                <td><?= $voucher->total_price ?></td>
            </tr>
            <tr>
                <!-- get payment name -->
                <?php
                    $getNameSql = "SELECT name FROM payment_type WHERE id=$voucher->payment_type_id";
                    $getNamePdo = $pdo->prepare($getNameSql);
                    $getNamePdo->execute();
                    $payment = $getNamePdo->fetchObject();
                ?>
                <td></td>
                <td></td>
                <td>PaymentType</td>
                <td><?= $payment->name ?></td>
            </tr>
        </tbody>
    </table>
    <div class="float-right">
        <button class="btn btn-primary">Print</button>
        <button class="btn btn-primary">Save</button>
    </div>
</div>