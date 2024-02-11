<?php
require '../_actions/auth.php';
require '../config/config.php';
check_auth();
//user list query
if ($_GET['search']) {
    $search = $_GET['search'];
    $custSql = "SELECT * FROM customers WHERE id!=1 AND isdeleted=0 AND name LIKE '%$search%'";
    $custPdo = $pdo->prepare($custSql);
    $custPdo->execute();
    $customers = $custPdo->fetchAll(PDO::FETCH_OBJ);
} else {
    $custSql = "SELECT * FROM customers WHERE id!=1 AND isdeleted=0";
    $custPdo = $pdo->prepare($custSql);
    $custPdo->execute();
    $customers = $custPdo->fetchAll(PDO::FETCH_OBJ);
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
        <?php foreach ($customers as $customer) : ?>
            <tr>
                <td><?= $customer->name ?></td>
                <td><?= $customer->phone ?></td>
                <td><?= $customer->address ?></td>
                <td>
                    <?php if ($_SESSION['user_role'] == 1) : ?>
                        <a href="customer.php?id=<?= $customer->id ?>">
                            <i class='fa fa-edit'></i>
                        </a>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($_SESSION['user_role'] == 1) : ?>
                        <?php if ($_SESSION['user_id'] != $user->id) : ?>
                            <a type="submit" onclick="deleteUser(<?= $customer->id ?>)">
                                <i class='fa fa-trash'></i>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>