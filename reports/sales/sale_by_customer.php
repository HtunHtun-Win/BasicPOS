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
        $getSql = "SELECT products.name,sum(sales_detail.quantity) AS quantity,sales_detail.price FROM sales_detail LEFT JOIN products ON sales_detail.product_id=products.id WHERE sales_detail.created_at>'$startDate' AND sales_detail.created_at<'$stopDate' GROUP BY name";
        $$getPdo = $pdo->prepare($getSql);
        $$getPdo->execute();
        $saleitems = $$getPdo->fetchAll(PDO::FETCH_OBJ);
    }
} else {
    // all items
    $getSql = "SELECT sales.customer_id,sales_detail.product_id,SUM(sales_detail.quantity) AS qty FROM sales_detail LEFT JOIN sales ON sales.id = sales_detail.sales_id WHERE customer_id=:cid GROUP BY product_id;";
    $getPdo = $pdo->prepare($getSql);
}
//get product name and code
$getPdSql = "SELECT * FROM products WHERE id=:id";
$getPdPdo = $pdo->prepare($getPdSql);
//
$totalQty = 0;
$totalAmount = 0;
$no = 1;
//
$_SESSION['report'] = 'sale-by-customer';
//
?>
<div class="row">
    <!-- get customer list -->
    <?php
    $custSql = "SELECT * FROM customers WHERE isdeleted=0";
    $custPdo = $pdo->prepare($custSql);
    $custPdo->execute();
    $customers = $custPdo->fetchAll(PDO::FETCH_OBJ);
    ?>
    <div class="col-md-9"></div>
    <div class="col-md-3">
        <select class="form-control" name="" id="">
            <option value="">All</option>
            <?php foreach ($customers as $customer) : ?>
                <option value="<?= $customer->id ?>"><?= $customer->name ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<table class="table">
    <?php
    foreach ($customers as $customer) :
        $getPdo->execute([':cid' => $customer->id]);
        $datas = $getPdo->fetchAll(PDO::FETCH_OBJ);
        if ($datas) :
    ?>
            <thead>
                <tr>
                    <td>
                        <h5><b><?= $customer->name ?></b></h5>
                    </td>
                </tr>
                <tr>
                    <th width="100px">No</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th width="150px">Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($datas as $data) : ?>
                    <?php
                    $getPdPdo->execute([':id' => $data->product_id]);
                    $products = $getPdPdo->fetchAll(PDO::FETCH_OBJ);
                    foreach($products as $product):
                    ?>
                    <tr>
                        <td><?= 1 ?></td>
                        <td><?= $product->code ?></td>
                        <td><?= $product->name ?></td>
                        <td><?= $data->qty ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
    <?php endif;
    endforeach; ?>
</table>