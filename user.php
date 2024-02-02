<?php
require '_actions/auth.php';
require 'config/config.php';
check_auth();
check_privilege();
//get role list
$roleSql = "SELECT * FROM roles";
$rolePdo = $pdo->prepare($roleSql);
$rolePdo->execute();
$roles = $rolePdo->fetchAll(PDO::FETCH_OBJ);
//get user info to edit
if ($_GET['id']) {
  $ufSql = "SELECT * FROM users WHERE id=" . $_GET['id'];
  $ufPdo = $pdo->prepare($ufSql);
  $ufPdo->execute();
  $userinfo = $ufPdo->fetchObject();
}
?>

<?php require 'header.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- user edit form-->
        <div class="col-md-4 mt-3">
          <?php if ($_SESSION['msg']) : ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
              <strong>Operation fails!</strong> LoginID can't duplicate!
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php endif;
          unset($_SESSION['msg']); ?>
          <form action="/_actions/user_add_update.php" method="post">
            <input type="hidden" name="id" value="<?= $userinfo->id ?>">
            <div class='from-group'>
              <label>Name</label>
              <input type="text" name="name" class='form-control' value="<?= $userinfo->name ?>" required>
            </div>
            <div class='from-group mt-3'>
              <label>LoginID</label>
              <input type="text" name="login_id" class='form-control' value="<?= $userinfo->login_id ?>" required>
            </div>
            <div class='from-group mt-3'>
              <label>Password</label>
              <input type="text" name="password" class='form-control' value="<?= $userinfo->password ?>" required>
            </div>
            <div class='from-group mt-3'>
              <label>Role</label>
              <select name="role_id" id="" class="form-control">
                <?php foreach ($roles as $role) : ?>
                  <option value="<?= $role->id ?>" <?php if ($role->id == $userinfo->id) {
                                                      echo 'selected';
                                                    } ?>>
                    <?= $role->name ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class='btn-group float-right mt-3'>
              <button type="reset" class='btn btn-warning' href="user.php">Clear</button>
              <button class='btn btn-primary'>Save</button>
            </div>
          </form>
        </div>
        <!-- user list -->
        <?php
        // $userSql = "SELECT * FROM users";
        $userSql = "SELECT users.id,users.name,users.login_id,users.password,roles.name as rname FROM users LEFT JOIN roles ON users.role_id=roles.id WHERE users.isdeleted=0";
        $userPdo = $pdo->prepare($userSql);
        $userPdo->execute();
        $users = $userPdo->fetchAll(PDO::FETCH_OBJ);
        // echo "<pre>";
        // print_r($users);
        ?>
        <div class="col-md-8">
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
                      <a href="_actions/user_delete.php?id=<?= $user->id ?>" onclick="return confirm('Are you sure to delete this user!')">
                        <i class='fa fa-trash'></i>
                      </a>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
require 'footer.php';
?>