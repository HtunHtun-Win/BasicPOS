<?php
require '../_actions/auth.php';
require '../config/config.php';
check_auth();
//get shop info
$id = 1;
if ($_GET['id']) {
    $id = $_GET['id'];
}
$sql = "SELECT * FROM gen_id WHERE id=$id";
$pdostatement = $pdo->prepare($sql);
$pdostatement->execute();
$genId = $pdostatement->fetchObject();
?>

<form method="post" id="voucher-info-id">
    <div class="form-group mt-2">
        <label for="">Type</label>
        <select name="typeId" class="form-control" onclick="loadVoucherData(this.value)">
            <option value="1">Sale</option>
            <option value="2" <?php if ($id == 2) {
                                    echo "selected";
                                } ?>>Purchase</option>
        </select>
    </div>
    <div class="form-group mt-2">
        <label for="">Prefix</label>
        <input type="text" class="form-control" name="prefix" value="<?= $genId->prefix ?>">
    </div>
    <div class="form-group mt-2">
        <label for="">Digit</label>
        <input type="text" class="form-control" name="digit" value="<?= $genId->digit ?>">
    </div>
    <button type="button" class="btn btn-primary float-right" onclick="voucher_update()">Save</button>
</form>