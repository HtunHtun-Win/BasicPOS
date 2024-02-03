<?php
require '../_actions/auth.php';
require '../config/config.php';
check_auth();
check_privilege();
//user list query
if($_GET['search']){
    $search = $_GET['search'];
    $userSql = "SELECT users.id,users.name,users.login_id,users.password,roles.name as rname FROM users LEFT JOIN roles ON users.role_id=roles.id WHERE users.isdeleted=0 AND users.name LIKE '%$search%'";
    $userPdo = $pdo->prepare($userSql);
    $userPdo->execute();
    $users = $userPdo->fetchAll(PDO::FETCH_OBJ);
}else{
    $userSql = "SELECT users.id,users.name,users.login_id,users.password,roles.name as rname FROM users LEFT JOIN roles ON users.role_id=roles.id WHERE users.isdeleted=0";
    $userPdo = $pdo->prepare($userSql);
    $userPdo->execute();
    $users = $userPdo->fetchAll(PDO::FETCH_OBJ);
}
?>
<table class='table table-bordered table-striped'>
    <thead>
        <tr>
            <th>Name</th>
            <th>LoginID</th>
            <th>Password</th>
            <th>Role</th>
            <th width="50px">#</th>
            <th width="50px">#</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user) : ?>
            <tr>
                <td><?= $user->name ?></td>
                <td><?= $user->login_id ?></td>
                <td><?= $user->password ?></td>
                <td><?= $user->rname ?></td>
                <td>
                    <a href="user.php?id=<?= $user->id ?>">
                        <i class='fa fa-edit'></i>
                    </a>
                </td>
                <td>
                    <?php if ($_SESSION['user_id'] != $user->id) : ?>
                        <a type="submit" onclick="deleteUser(<?= $user->id ?>)">
                            <i class='fa fa-trash'></i>
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>