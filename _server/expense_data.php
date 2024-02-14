<?php
require '../_actions/auth.php';
require '../config/config.php';
check_auth();
check_privilege();
//
$totalIncome = 0;
$totalExpense = 0;
$no = 1;
$totSql = "SELECT * FROM income_expense WHERE isdeleted=0";
$totPdo = $pdo->prepare($totSql);
$totPdo->execute();
$totDatas = $totPdo->fetchAll(PDO::FETCH_OBJ);
foreach ($totDatas as $totData) {
    if ($totData->flow_type_id == 1) {
        $totalIncome += $totData->amount;
    } else {
        $totalExpense += $totData->amount;
    }
}
//user list query
if ($_GET['search']) {
    $search = $_GET['search'];
    $expSql = "SELECT * FROM income_expense WHERE isdeleted=0 AND (description LIKE '%$search%' OR note LIKE '%$search%') ORDER BY id DESC";
    $expPdo = $pdo->prepare($expSql);
    $expPdo->execute();
    $datas = $expPdo->fetchAll(PDO::FETCH_OBJ);
} else {
    $expSql = "SELECT * FROM income_expense WHERE isdeleted=0 ORDER BY id DESC";
    $expPdo = $pdo->prepare($expSql);
    $expPdo->execute();
    $datas = $expPdo->fetchAll(PDO::FETCH_OBJ);
}
?>
<div class="total container bg-gray mt-3 mb-3">
    <div class="row">
        <div class="col-md-6">
            <h4>Income</h4>
            <h5>+<?= $totalIncome ?></h5>
        </div>
        <div class="col-md-6">
            <h4>Expense</h4>
            <h5>-<?= $totalExpense ?></h5>
        </div>
    </div>
</div>
<!-- data lists -->
<?php foreach ($datas as $data) : ?>
    <div class="card">
        <div class="container">
            <div class="row">
                <div class="col-md-1">
                    <h5><?= $no ?></h5>
                </div>
                <div class="col-md-3">
                    <h5><?= $data->description ?></h6>
                        <h6><?= date('Y-M-d', strtotime($data->created_at)) ?></h6>
                </div>
                <div class="col-md-4">
                    <h5>
                        <?php
                        if ($data->flow_type_id == 1) {
                            echo "<span class='text-success'>+$data->amount</span>";
                        } else {
                            echo "<span class='text-danger'>-$data->amount</span>";
                        }
                        ?>
                    </h5>
                    <h6><?= $data->note ?></h6>
                </div>
                <div class="col-md-4">
                    <div class="float-right mt-1">
                        <a href="expense_manager.php?id=<?= $data->id ?>">
                            <i class='fa fa-edit'></i>
                        </a>
                        &ensp;
                        <a onclick="deleteExpense(<?= $data->id ?>)">
                            <i class='fa fa-trash'></i>
                        </a>
                        <h6>
                            <?php
                            if ($data->flow_type_id == 1) {
                                echo "<span class='badge bg-success'>income</span>";
                            } else {
                                echo "<span class='badge bg-danger'>expense</span>";
                            }
                            ?>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $no++;
endforeach; ?>