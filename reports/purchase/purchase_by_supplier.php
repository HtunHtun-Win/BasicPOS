<?php
require '../../_actions/auth.php';
require '../../config/config.php';
check_auth();
// all items
$getSql = "SELECT purchase.supplier_id,purchase_detail.product_id,SUM(purchase_detail.quantity) AS qty FROM purchase_detail LEFT JOIN purchase ON purchase.id = purchase_detail.purchase_id WHERE supplier_id=:cid GROUP BY product_id;";
$getPdo = $pdo->prepare($getSql);
if($_GET['supplier']){
    $supplierId = $_GET['supplier'];
}
//filter
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
    $getSql = "SELECT purchase.supplier_id,purchase_detail.product_id,SUM(purchase_detail.quantity) AS qty FROM purchase_detail LEFT JOIN purchase ON purchase.id = purchase_detail.purchase_id WHERE supplier_id=:cid AND purchase_detail.created_at>'$startDate' AND purchase_detail.created_at<'$stopDate' GROUP BY product_id";
    $getPdo = $pdo->prepare($getSql);
    // $getPdo->execute();
    // $saleitems = $getPdo->fetchAll(PDO::FETCH_OBJ);
}

//get product name and code
$getPdSql = "SELECT * FROM products WHERE id=:id";
$getPdPdo = $pdo->prepare($getPdSql);
//
$totalQty = 0;
$totalAmount = 0;
$no = 1;
//
$_SESSION['report'] = 'purchase-by-supplier';
//
?>
<div class="row">
    <!-- get supplier list -->
    <?php
    $suppSql = "SELECT * FROM suppliers WHERE isdeleted=0";
    $suppPdo = $pdo->prepare($suppSql);
    $suppPdo->execute();
    $suppliers = $suppPdo->fetchAll(PDO::FETCH_OBJ);
    ?>
    <div class="col-md-9"></div>
    <div class="col-md-3">
        <select class="form-control" name="" id="supplier" onclick="filterSupplier()">
            <option value="0">All</option>
            <?php foreach ($suppliers as $supplier) : ?>
                <option value="<?= $supplier->id ?>" <?php if ($supplier->id == $supplierId){echo "selected";} ?>><?= $supplier->name ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<table class="table">
    <?php
    foreach ($suppliers as $supplier) :
        if(isset($supplierId)){
            if ($supplier->id == $supplierId) {
                $getPdo->execute([':cid' => $supplier->id]);
                $datas = $getPdo->fetchAll(PDO::FETCH_OBJ);
            } else {
                continue;
            }
        }else{
            $getPdo->execute([':cid' => $supplier->id]);
            $datas = $getPdo->fetchAll(PDO::FETCH_OBJ);
        }
        if ($datas) :
    ?>
            <thead>
                <tr>
                    <td>
                        <h5><b><?= $supplier->name ?></b></h5>
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
                    foreach ($products as $product) :
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