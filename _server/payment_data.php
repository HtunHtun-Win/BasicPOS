<?php
require '../_actions/auth.php';
require '../config/config.php';
check_auth();
//get data to edit
if ($_GET) {
    $id = $_GET['id'];
    $getSql = "SELECT * FROM payment_type WHERE id=$id";
    $getPdo = $pdo->prepare($getSql);
    $getPdo->execute();
    $data = $getPdo->fetchObject();
}
//add and update payment method
if ($_POST) {
    $name = $_POST['name'];
    $desc = $_POST['desc'];
    if ($_POST['payment_id']) {
        $pmId = $_POST['payment_id'];
        $sql = "UPDATE payment_type SET name=:name, description=:desc WHERE id=:pmId";
        $pdostatement = $pdo->prepare($sql);
        $pdostatement->execute([
            ':name' => $name,
            ':desc' => $desc,
            ':pmId' => $pmId,
        ]);
    } else {
        $sql = "INSERT INTO payment_type(name,description) VALUES(:name,:desc)";
        $pdostatement = $pdo->prepare($sql);
        $pdostatement->execute([
            ':name' => $name,
            ':desc' => $desc,
        ]);
    }
}
//get bank payment type
$sql = "SELECT * FROM payment_type WHERE id!=0";
$pdostatement = $pdo->prepare($sql);
$pdostatement->execute();
$types = $pdostatement->fetchAll(PDO::FETCH_OBJ);
$no = 1;
?>

<div class="row">
    <!-- data edit form -->
    <div class="col-md-4">
        <form method="post" id="payment-type-id">
            <input type="hidden" name="payment_id" id="pay_id" value="<?= $data->id ?>">
            <div class="form-group">
                <label for="">Name</label>
                <input type="text" class="form-control" name="name" id="name_id" value="<?= $data->name ?>">
            </div>
            <div class="form-group mb-3">
                <label for="">Description</label>
                <textarea rows="3" class="form-control" name="desc" id="desc_id"><?= $data->description ?></textarea>
            </div>
            <div class="float-right">
                <button type="button" class="btn btn-primary" onclick="payment_update()">Save</button>
                <button type="button" class="btn btn-danger" onclick="clear_form()">Clear</button>
            </div>
        </form>
    </div>
    <!-- data lists -->
    <div class="col-md-8">
        <table class="table">
            <thead>
                <th width="100px">No</th>
                <th width="150px">Name</th>
                <th>Description</th>
                <th>#</th>
            </thead>
            <tbody>
                <?php foreach ($types as $type) : ?>
                    <tr>
                        <td><?= $no ?></td>
                        <td><?= $type->name ?></td>
                        <td><?= $type->description ?></td>
                        <td>
                            <a href="#" onclick="edit(<?= $type->id ?>)">
                                <i class='fa fa-edit'></i>
                            </a>
                        </td>
                    </tr>
                <?php $no++;
                endforeach; ?>
            </tbody>
        </table>
    </div>
</div>