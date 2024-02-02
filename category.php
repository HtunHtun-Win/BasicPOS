<?php
require '_actions/auth.php';
require 'config/config.php';
check_auth();
check_privilege();
//get user info to edit
if ($_GET['id']) {
  $catinfoSql = "SELECT * FROM categories WHERE id=" . $_GET['id'];
  $catinfoPdo = $pdo->prepare($catinfoSql);
  $catinfoPdo->execute();
  $catinfo = $catinfoPdo->fetchObject();
}
?>

<?php require 'header.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- category edit form-->
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
          <form action="/_actions/category_add_update.php" method="post">
            <input type="hidden" name="id" value="<?= $catinfo->id ?>">
            <div class='from-group'>
              <label>Name</label>
              <input type="text" name="name" class='form-control' value="<?= $catinfo->name ?>" required>
            </div>
            <div class='from-group mt-3'>
              <label>Description</label>
              <textarea name="description" class='form-control' cols="30" rows="10"><?= $catinfo->description ?></textarea>
            </div>
            <div class='btn-group float-right mt-3'>
              <a class='btn btn-warning' href="category.php">Clear</a>
              <button class='btn btn-primary'>Save</button>
            </div>
          </form>
        </div>
        <!-- categories list -->
        <?php
        //get categories list
        $catSql = "SELECT * FROM categories";
        $catPdo = $pdo->prepare($catSql);
        $catPdo->execute();
        $categories = $catPdo->fetchAll(PDO::FETCH_OBJ);
        $no = 1;
        ?>
        <div class="col-md-8">
          <table class='table table-bordered table-striped'>
            <thead>
              <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Description</th>
                <th width="50px">#</th>
                <th width="50px">#</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($categories as $category) : ?>
                <tr>
                  <td><?= $no ?></td>
                  <td><?= $category->name ?></td>
                  <td><?= $category->description ?></td>
                  <td>
                    <a href="category.php?id=<?= $category->id ?>">
                      <i class='fa fa-edit'></i>
                    </a>
                  </td>
                  <td>
                    <?php if ($_SESSION['user_id'] != $user->id) : ?>
                      <a href=".php?id=<?= $category->id ?>" onclick="return confirm('Are you sure to delete this user!')">
                        <i class='fa fa-trash'></i>
                      </a>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php $no++;
              endforeach; ?>
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