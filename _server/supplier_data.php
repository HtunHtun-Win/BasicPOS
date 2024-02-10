<?php
require '../_actions/auth.php';
require '../config/config.php';
check_auth();
check_privilege();
//user list query
if($_GET['search']){
    $search = $_GET['search'];
    $suppSql = "SELECT * FROM suppliers WHERE id!=1 AND isdeleted=0 AND name LIKE '%$search%'";
    $suppPdo = $pdo->prepare($suppSql);
    $suppPdo->execute();
    $suppliers = $suppPdo->fetchAll(PDO::FETCH_OBJ);
}else{
    $suppSql = "SELECT * FROM suppliers WHERE id!=1 AND isdeleted=0";
    $suppPdo = $pdo->prepare($suppSql);
    $suppPdo->execute();
    $suppliers = $suppPdo->fetchAll(PDO::FETCH_OBJ);
}
?>
<table class='table table-bordered table-striped'>
    <thead>
        <tr>
            <th>Name</th>
            <th>Phone</th>
            <th>Address</th>
            <th width="50px">#</th>
            <th width="50px">#</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($suppliers as $supplier) : ?>
            <tr>
                <td><?= $supplier->name ?></td>
                <td><?= $supplier->phone ?></td>
                <td><?= $supplier->address ?></td>
                <td>
                    <a href="supplier.php?id=<?= $supplier->id ?>">
                        <i class='fa fa-edit'></i>
                    </a>
                </td>
                <td>
                    <?php if ($_SESSION['user_id'] != $user->id) : ?>
                        <a type="submit" onclick="deleteUser(<?= $supplier->id ?>)">
                            <i class='fa fa-trash'></i>
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>