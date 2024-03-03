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
        $getSql = "SELECT sales.sale_no, sales.total_price, payment_type.name FROM sales LEFT JOIN payment_type ON sales.payment_type_id=payment_type.id WHERE sales.payment_type_id!=1 AND sales.created_at>'$startDate' AND sales.created_at<'$stopDate'";
        $getPdo = $pdo->prepare($getSql);
        $getPdo->execute();
        $vouchers = $getPdo->fetchAll(PDO::FETCH_OBJ);
    }
} else {
    // get all voucher
    $getSql = "SELECT sales.sale_no, sales.total_price, payment_type.name FROM sales LEFT JOIN payment_type ON sales.payment_type_id=payment_type.id WHERE sales.payment_type_id!=1";
    $getPdo = $pdo->prepare($getSql);
    $getPdo->execute();
    $vouchers = $getPdo->fetchAll(PDO::FETCH_OBJ);
}
$totalPrice = 0;
$no = 1;
//
$_SESSION['report'] = 'bank-payment';
//
?>
<table class="table">
    <thead class="thead-dark">
        <th width="100">No</th>
        <th>InvoiceNo</th>
        <th>BankName</th>
        <th width="150px">Amount</th>
    </thead>
    <tbody>
        <?php foreach ($vouchers as $voucher) : ?>
            <tr>
                <td><?= $no ?></td>
                <td><?= $voucher->sale_no ?></td>
                <td><?= $voucher->name ?></td>
                <td><?= $voucher->total_price ?></td>
            </tr>
        <?php $no++;
        $totalPrice += $voucher->total_price;
        endforeach; ?>
        <tr class="table-active">
            <td colspan="3">
                <center>
                    <b>Total Bank Payment</b>
                </center>
            </td>
            <td><b><?= $totalPrice ?></b></td>
        </tr>
    </tbody>
</table>