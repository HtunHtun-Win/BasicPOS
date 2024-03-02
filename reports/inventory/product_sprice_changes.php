<?php
require '../../_actions/auth.php';
require '../../config/config.php';
check_auth();
// all stock balance
if($_GET['search']){
    $pname = $_GET['search'];
    $getSql = "SELECT * FROM products WHERE name LIKE '%$pname%' OR code LIKE '%$pname%' ORDER BY name";
    $getPdo = $pdo->prepare($getSql);
    $getPdo->execute();
    $products = $getPdo->fetchAll(PDO::FETCH_OBJ);
}else{
    $getSql = "SELECT * FROM products";
    $getPdo = $pdo->prepare($getSql);
    $getPdo->execute();
    $products = $getPdo->fetchAll(PDO::FETCH_OBJ);
}
//get sale price changes
$getSpriceSql = "SELECT * FROM sale_price_log ORDER BY id DESC";
$getSpricePdo = $pdo->prepare($getSpriceSql);
$getSpricePdo->execute();
$sprices = $getSpricePdo->fetchAll(PDO::FETCH_OBJ);
//
$_SESSION['report'] = 'product-sprice-changes';
$no = 1;
//
?>
<table class="table">
    <thead class="thead-dark">
        <th width="100px">No</th>
        <th>Date</th>
        <th>Code</th>
        <th>Name</th>
        <th width="150px">Old_Price</th>
        <th width="150px">New_Price</th>
    </thead>
    <tbody>
        <?php foreach ($sprices as $sprice) : ?>
            <?php
                foreach ($products as $product) :
                    if($sprice->product_id == $product->id):
            ?>
                <tr>
                    <td><?= $no ?></td>
                    <td><?= date("Y-M-d (h:ia)",strtotime($sprice->created_at)) ?></td>
                    <td><?= $product->code ?></td>
                    <td><?= $product->name ?></td>
                    <td><?= $sprice->old_price ?></td>
                    <td><?= $sprice->new_price ?></td>
                </tr>
            <?php
                $no++;
                endif;
                endforeach;
            ?>
        <?php
            $totalQty += $product->quantity;
            endforeach;
        ?>
    </tbody>
</table>