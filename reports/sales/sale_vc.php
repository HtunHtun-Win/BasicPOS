<?php
require '../../_actions/auth.php';
require '../../config/config.php';
check_auth();
//
$_SESSION['report'] = 'sale-voucher';
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
        $vSql = "SELECT sales.id,sales.created_at,sales.sale_no,customers.name,sales.total_price FROM sales LEFT JOIN customers on sales.customer_id=customers.id WHERE sales.created_at>'$startDate' AND sales.created_at<'$stopDate' ORDER BY id DESC";
        $vPdo = $pdo->prepare($vSql);
        $vPdo->execute();
        $vouchers = $vPdo->fetchAll(PDO::FETCH_OBJ);
    }
} else {
    // all voucher
    $vSql =
        "SELECT sales.id,sales.created_at,sales.sale_no,customers.name,sales.total_price FROM sales LEFT JOIN customers on sales.customer_id=customers.id ORDER BY id DESC";
    $vPdo = $pdo->prepare($vSql);
    $vPdo->execute();
    $vouchers = $vPdo->fetchAll(PDO::FETCH_OBJ);
}
$no = 1;
$totalAmount = 0;
?>
<table class="table">
    <thead class="table-dark">
        <tr>
            <th width='50px'>No</th>
            <th width='150px'>Date</th>
            <th width='200px'>Invoice No.</th>
            <th>Customer Name</th>
            <th width="150px">Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($vouchers as $voucher) : ?>
            <tr>
                <td><?= $no ?></td>
                <td><?= date("Y-M-d", strtotime($voucher->created_at)) ?></td>
                <td><?= $voucher->sale_no ?></td>
                <td><?= $voucher->name ?></td>
                <td><?= $voucher->total_price ?></td>
            </tr>
        <?php $no++;
            $totalAmount += $voucher->total_price;
        endforeach; ?>
        <tr class="table-active">
            <td colspan="4">
                <center><b>TotalAmount</b></center>
            </td>
            <td><b><?= $totalAmount ?></b></td>
        </tr>
    </tbody>
</table>