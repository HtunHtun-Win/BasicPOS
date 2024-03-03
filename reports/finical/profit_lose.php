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
        $getSql = "SELECT products.code,products.name,sum(sales_detail.quantity) AS quantity,sales_detail.price,sales_detail.pprice FROM sales_detail LEFT JOIN products ON sales_detail.product_id=products.id WHERE sales_detail.created_at>'$startDate' AND sales_detail.created_at<'$stopDate' GROUP BY name";
        $getPdo = $pdo->prepare($getSql);
        $getPdo->execute();
        $saleitems = $getPdo->fetchAll(PDO::FETCH_OBJ);
        // get purchase discount by date
        $getPdiscount = "SELECT SUM(discount) AS discount FROM purchase WHERE created_at>'$startDate' AND created_at<'$stopDate'";
        $getPdiscoutPdo = $pdo->prepare($getPdiscount);
        $getPdiscoutPdo->execute();
        $purchaseDiscount = $getPdiscoutPdo->fetchObject();
        // get other income by date
        $getIncome = "SELECT SUM(amount) AS amount FROM income_expense WHERE flow_type_id=1 AND isdeleted=0 AND created_at>'$startDate' AND created_at<'$stopDate'";
        $getIncomePdo = $pdo->prepare($getIncome);
        $getIncomePdo->execute();
        $income = $getIncomePdo->fetchObject();
        // get sale discount by date
        $getSdiscount = "SELECT SUM(discount) AS discount FROM sales WHERE created_at>'$startDate' AND created_at<'$stopDate'";
        $getSdiscoutPdo = $pdo->prepare($getSdiscount);
        $getSdiscoutPdo->execute();
        $saleDiscount = $getSdiscoutPdo->fetchObject();
        // get other expense by date
        $getExpense = "SELECT SUM(amount) AS amount FROM income_expense WHERE flow_type_id=2 AND isdeleted=0 AND created_at>'$startDate' AND created_at<'$stopDate'";
        $getExpensePdo = $pdo->prepare($getExpense);
        $getExpensePdo->execute();
        $expense = $getExpensePdo->fetchObject();
    }
} else {
    // get all sold price and capital price
    $getSql = "SELECT products.code,products.name,sum(sales_detail.quantity) AS quantity,sales_detail.price,sales_detail.pprice FROM sales_detail LEFT JOIN products ON sales_detail.product_id=products.id GROUP BY name";
    $getPdo = $pdo->prepare($getSql);
    $getPdo->execute();
    $saleitems = $getPdo->fetchAll(PDO::FETCH_OBJ);
    // get all purchase discount
    $getPdiscount = "SELECT SUM(discount) AS discount FROM purchase";
    $getPdiscoutPdo = $pdo->prepare($getPdiscount);
    $getPdiscoutPdo->execute();
    $purchaseDiscount = $getPdiscoutPdo->fetchObject();
    // get all other income
    $getIncome = "SELECT SUM(amount) AS amount FROM income_expense WHERE flow_type_id=1 AND isdeleted=0";
    $getIncomePdo = $pdo->prepare($getIncome);
    $getIncomePdo->execute();
    $income = $getIncomePdo->fetchObject();
    // get all sale discount
    $getSdiscount = "SELECT SUM(discount) AS discount FROM sales";
    $getSdiscoutPdo = $pdo->prepare($getSdiscount);
    $getSdiscoutPdo->execute();
    $saleDiscount = $getSdiscoutPdo->fetchObject();
    // get all other expense
    $getExpense = "SELECT SUM(amount) AS amount FROM income_expense WHERE flow_type_id=2 AND isdeleted=0";
    $getExpensePdo = $pdo->prepare($getExpense);
    $getExpensePdo->execute();
    $expense = $getExpensePdo->fetchObject();
}
$totalSalePrice = 0;
$totalCapitalPrice = 0;
//
$_SESSION['report'] = 'profit-lose';
//
?>
<h4 style="color:Green;">Profit</h4>
<table class="table">
    <thead class="thead-dark">
        <th width="100">No</th>
        <th>Type</th>
        <th width="150px">Amount</th>
    </thead>
    <tbody>
        <?php
        foreach ($saleitems as $saleitem) {
            $totalQty += $saleitem->quantity;
            $totalSalePrice += $saleitem->quantity * $saleitem->price;
            $totalCapitalPrice += $saleitem->quantity * $saleitem->pprice;
        }
        ?>
        <tr>
            <td>1</td>
            <td>Sold Item Price</td>
            <td><?= $totalSalePrice ?></td>
        </tr>
        <tr>
            <td>2</td>
            <td>Capital Price</td>
            <td>(<?= $totalCapitalPrice ?>)</td>
        </tr>
        <tr>
            <td>3</td>
            <td>Purchase Discount</td>
            <td><?= $purchaseDiscount->discount ?? 0 ?></td>
        </tr>
        <tr>
            <td>4</td>
            <td>Other Income</td>
            <td><?= $income->amount ?? 0 ?></td>
        </tr>
        <tr class="table-active">
            <td colspan="2">
                <center>
                    <b>Total Profit</b>
                </center>
            </td>
            <td><b><?= ($totalSalePrice + $purchaseDiscount->discount + $income->amount) - $totalCapitalPrice ?></b></td>
        </tr>
    </tbody>
</table>

<!-- lose section -->
<h4 style="color:Green;">Lose</h4>
<table class="table">
    <thead class="thead-dark">
        <th width="100">No</th>
        <th>Type</th>
        <th width="150px">Amount</th>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Sale Discount</td>
            <td><?= $saleDiscount->discount ?? 0 ?></td>
        </tr>
        <tr>
            <td>2</td>
            <td>Other Expense</td>
            <td><?= $expense->amount ?? 0 ?></td>
        </tr>
        <tr class="table-active">
            <td colspan="2">
                <center>
                    <b>Total Lose</b>
                </center>
            </td>
            <td><b>(<?= $saleDiscount->discount + $expense->amount ?>)</b></td>
        </tr>
    </tbody>
</table>