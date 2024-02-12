<?php
require '../_actions/auth.php';
require '../config/config.php';
check_auth();
//get shop info
$sql = "SELECT * FROM shop_info";
$pdostatement = $pdo->prepare($sql);
$pdostatement->execute();
$shop = $pdostatement->fetchObject();
?>

<form method="post" id="shop-info-id">
    <div class="form-group mt-2">
        <label for="">Name</label>
        <input type="text" class="form-control" name="name" value="<?= $shop->shop_name ?>">
    </div>
    <div class="form-group mt-2">
        <label for="">Phone</label>
        <input type="text" class="form-control" name="phone" value="<?= $shop->shop_phone ?>">
    </div>
    <div class="form-group mt-2">
        <label for="">Address</label>
        <textarea class="form-control" name="address" cols="30" rows="10"><?= $shop->shop_address ?></textarea>
    </div>
    <button type="button" class="btn btn-primary float-right" onclick="shop_update()">Save</button>
</form>