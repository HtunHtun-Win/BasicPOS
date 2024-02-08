<?php
require '../_actions/auth.php';
require '../config/config.php';
check_auth();
//get voucher list
if ($_GET) {
    $search = $_GET['search'];
    $vSql = "SELECT sales.id,sales.created_at,sales.sale_no,customers.name,sales.total_price FROM sales LEFT JOIN customers on sales.customer_id=customers.id WHERE sale_no LIKE '%$search%' OR total_price LIKE '%$search%' OR name LIKE '%$search%' ORDER BY id DESC";
    $vPdo = $pdo->prepare($vSql);
    $vPdo->execute();
    $vouchers = $vPdo->fetchAll(PDO::FETCH_OBJ);
} else {
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