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
          <form method="post" id="myForm">
            <input type="hidden" name="id" id="input_id" value="<?= $userinfo->id ?>">
            <div class='from-group'>
              <label>Name</label>
              <input type="text" name="name" id="input_name" class='form-control' value="<?= $userinfo->name ?>" required>
            </div>
            <div class='from-group mt-3'>
              <label>LoginID</label>
              <input type="text" name="login_id" id="input_login_id" class='form-control' value="<?= $userinfo->login_id ?>" required>
            </div>
            <div class='from-group mt-3'>
              <label>Password</label>
              <input type="text" name="password" id="input_password" class='form-control' value="<?= $userinfo->password ?>" required>
            </div>
            <div class='from-group mt-3'>
              <label>Role</label>
              <select name="role_id" id="input_role_id" class="form-control">
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
              <button type="button" class='btn btn-warning' onclick="clearForm()">Clear</button>
              <button type="button" class='btn btn-primary' onclick="upload()">Save</button>
            </div>
          </form>
        </div>
        <!-- user list -->
        <div class="col-md-8">
          <div id='user_list'></div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
  //get user list
  function loadDataList(search = '') {
    if (search.length == 0) {
      fetch("/_server/user_data.php")
        .then(res => res.text()).
      then(data => document.getElementById("user_list").innerHTML = data);
    } else {
      fetch("/_server/user_data.php?search=" + search)
        .then(res => res.text()).
      then(data => document.getElementById("user_list").innerHTML = data);
    }
  }
  //delete user by id
  function deleteUser(id) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "/_actions/user_delete.php?id=" + id, true);
    xmlhttp.send();
    loadDataList();
  }
  //post data to update and create
  function upload() {
    url = "/_actions/user_add_update.php";
    const formData = new FormData(document.getElementById("myForm"));
    fetch(url, {
        method: "POST",
        body: formData
      }).then(resp => resp.text())
      .then(function(data) {
        console.log(data);
        if (data == 'require') {
          alert('You need to fill all blank!');
        } else if (data == 'exist') {
          console.log(data);
          alert('Login_id must be unique!');
        } else if (data == 'success') {
          clearForm();
        }
        loadDataList();
      })
      .catch(function(error) {
        console.error(error);
      })
  }
  //clear form data value
  function clearForm() {
    document.getElementById("input_id").value = "";
    document.getElementById("input_name").value = "";
    document.getElementById("input_login_id").value = "";
    document.getElementById("input_password").value = "";
  }
  //initial state
  window.onload = function() {
    // setInterval(() => {
    //   loadUserList();
    // }, 1000);
    loadDataList();
  };
</script>
<?php
require 'footer.php';
?>