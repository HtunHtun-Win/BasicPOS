<?php
require '../_actions/auth.php';
require '../config/config.php';
check_auth();
//get voucher list
if ($_GET) {//filter
    if($_GET['date']){
        $date = $_GET['date'];
        //calculate date
        if ($date == 'today') {
            $startDate = date('Y-m-d');
            $stopDate = date('Y-m-d', strtotime("+1 days"));
        }else if($date == 'yesterday'){
            $startDate = date('Y-m-d', strtotime("yesterday"));
            $stopDate = date('Y-m-d');
        }else if($date == 'thismonth'){
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
        }else{
            $startDate = $date;
            $stopDate = date('Y-m-d',strtotime($startDate.'+1 day'));
        }
        // echo $startDate;
        // echo $stopDate;
        // die();
        //date time and keywork filter
        if($_GET['search']){ //keyword and date
            $search = $_GET['search'];
            $vSql = "SELECT sales.id,sales.created_at,sales.sale_no,customers.name,sales.total_price FROM sales LEFT JOIN customers on sales.customer_id=customers.id WHERE (sale_no LIKE '%$search%' OR total_price LIKE '%$search%' OR name LIKE '%$search%') AND (sales.created_at>'$startDate' AND sales.created_at<'$stopDate') ORDER BY id DESC";
            $vPdo = $pdo->prepare($vSql);
            $vPdo->execute();
            $vouchers = $vPdo->fetchAll(PDO::FETCH_OBJ);
        }else{ // date only
            $vSql = "SELECT sales.id,sales.created_at,sales.sale_no,customers.name,sales.total_price FROM sales LEFT JOIN customers on sales.customer_id=customers.id WHERE sales.created_at>'$startDate' AND sales.created_at<'$stopDate' ORDER BY id DESC";
            $vPdo = $pdo->prepare($vSql);
            $vPdo->execute();
            $vouchers = $vPdo->fetchAll(PDO::FETCH_OBJ);
        }
    }else{
        //keyword search only
        $search = $_GET['search'];
        $vSql = "SELECT sales.id,sales.created_at,sales.sale_no,customers.name,sales.total_price FROM sales LEFT JOIN customers on sales.customer_id=customers.id WHERE sale_no LIKE '%$search%' OR total_price LIKE '%$search%' OR name LIKE '%$search%' ORDER BY id DESC";
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
?>

<tbody>
    <?php foreach ($vouchers as $voucher) : ?>
        <tr>
            <td><?= $no ?></td>
            <td><?= date('d-M-Y', strtotime($voucher->created_at)) ?></td>
            <td><?= $voucher->sale_no ?></td>
            <td><?= $voucher->name ?></td>
            <td><?= $voucher->total_price ?></td>
            <td>
                <a onclick="voucherDetail(<?= $voucher->id ?>)">
                    <i class="fa-regular fa-file"></i>
                </a>
            </td>
        </tr>
    <?php $no++;
    endforeach; ?>
</tbody>