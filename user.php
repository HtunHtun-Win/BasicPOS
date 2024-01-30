<?php
require '_actions/auth.php';
require 'config/config.php';
check_auth();
$roleSql = "SELECT * FROM roles";
$rolePdo = $pdo->prepare($roleSql);
$rolePdo->execute();
$roles = $rolePdo->fetchAll(PDO::FETCH_OBJ);
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
          <form action="">
            <div class='from-group'>
              <label>Name</label>
              <input type="text" class='form-control'>
            </div>
            <div class='from-group mt-3'>
              <label>LoginID</label>
              <input type="text" class='form-control'>
            </div>
            <div class='from-group mt-3'>
              <label>Password</label>
              <input type="text" class='form-control'>
            </div>
            <div class='from-group mt-3'>
              <label>Role</label>
              <select name="role" id="" class="form-control">
                <?php foreach ($roles as $role) : ?>
                  <option value="<?= $role->id ?>"><?= $role->name ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class='btn-group float-right mt-3'>
              <button class='btn btn-warning'>Clear</button>
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
                    <a href="">
                      <i class='fa fa-edit'></i>
                    </a>
                  </td>
                  <td>
                    <a href="">
                      <i class='fa fa-trash'></i>
                    </a>
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