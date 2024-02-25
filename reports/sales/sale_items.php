<?php
require '../../_actions/auth.php';
require '../../config/config.php';
check_auth();
//
if ($_GET) { //filter
    if ($_GET['date']) {
        $date = $_GET['date'];
        //calculate date
        if ($date == 'today') {
            $startDate = date('Y-m-d');
            $stopDate = date('Y-m-d', strtotime("+1 days"));
        } else if ($date == 'yesterday') {
            $startDate = date('Y-m-d', strtotime("yesterday"));
            $stopDate = date('Y-m-d');
        } else if ($date == 'thismonth') {
            $startDate = date('Y-m-01');
            $stopDate = date('Y-m-d', strtotime("+1 days"));
        } else if ($date == 'lastmonth') {
            $startDate = date('Y-m-d', strtotime("first day of previous month"));
            $stopDate = date('Y-m-d', strtotime("last day of previous month"));
        } else if ($date == 'thisyear') {
            $startDate = date('Y-01-01');
            $stopDate = date('Y-m-d', strtotime("+1 days"));
        } else if ($date == 'lastyear') {
            $startDate = date('Y-01-01', strtotime("-1 year"));
            $stopDate = date('Y-12-31', strtotime("-1 year"));
        } else {
            $startDate = $date;
            $stopDate = date('Y-m-d', strtotime($startDate . '+1 day'));
        }
        // date only
        $getSql = "SELECT products.code,products.name,sum(sales_detail.quantity) AS quantity,sales_detail.price FROM sales_detail LEFT JOIN products ON sales_detail.product_id=products.id WHERE sales_detail.created_at>'$startDate' AND sales_detail.created_at<'$stopDate' GROUP BY name";
        $$getPdo = $pdo->prepare($getSql);
        $$getPdo->execute();
        $saleitems = $$getPdo->fetchAll(PDO::FETCH_OBJ);
    }
} else {
    // all items
    $getSql = "SELECT products.code,products.name,sum(sales_detail.quantity) AS quantity,sales_detail.price FROM sales_detail LEFT JOIN products ON sales_detail.product_id=products.id GROUP BY name";
    $getPdo = $pdo->prepare($getSql);
    $getPdo->execute();
    $saleitems = $getPdo->fetchAll(PDO::FETCH_OBJ);
}
$totalQty = 0;
$totalAmount = 0;
$no = 1;
//
$_SESSION['report'] = 'sale-item';
//
?>
<table class="table">
    <thead class="thead-dark">
        <th>No</th>
        <th>Code</th>
        <th>Name</th>
        <th>Quantity</th>
        <th width="150px">Amount</th>
    </thead>
    <tbody>
        <?php foreach ($saleitems as $saleitem) : ?>
            <tr>
                <td><?= $no ?></td>
                <td><?= $saleitem->code ?></td>
                <td><?= $saleitem->name ?></td>
                <td><?= $saleitem->quantity ?></td>
                <td><?= $saleitem->quantity * $saleitem->price ?></td>
            </tr>
        <?php
            $totalQty += $saleitem->quantity;
            $totalAmount += $saleitem->quantity * $saleitem->price;
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
            <td><b><?= $totalAmount ?></b></td>
        </tr>
    </tbody>
</table>