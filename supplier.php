<?php
require '_actions/auth.php';
require 'config/config.php';
check_auth();
//get user info to edit
if ($_GET['id']) {
  $custInfoSql = "SELECT * FROM suppliers WHERE id=" . $_GET['id'];
  $custInfoPdo = $pdo->prepare($custInfoSql);
  $custInfoPdo->execute();
  $custInfo = $custInfoPdo->fetchObject();
  // print_r($custInfo->id);
  // die();
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
            <input type="hidden" name="id" id="input_id" value="<?= $custInfo->id ?>">
            <div class='from-group'>
              <label>Name</label>
              <input type="text" name="name" id="input_name" class='form-control' value="<?= $custInfo->name ?>" required>
            </div>
            <div class='from-group mt-3'>
              <label>Phone</label>
              <input type="text" name="phone" id="input_phone" class='form-control' value="<?= $custInfo->phone ?>" required>
            </div>
            <div class='from-group mt-3'>
              <label>Address</label>
              <textarea name="address" id="input_address" class="form-control"><?= $custInfo->address ?></textarea>
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
      fetch("/_server/supplier_data.php")
        .then(res => res.text()).
      then(data => document.getElementById("user_list").innerHTML = data);
    } else {
      fetch("/_server/supplier_data.php?search=" + search)
        .then(res => res.text()).
      then(data => document.getElementById("user_list").innerHTML = data);
    }
  }
  //delete user by id
  function deleteUser(id) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "/_actions/supplier_delete.php?id=" + id, true);
    xmlhttp.send();
    loadDataList();
  }
  //post data to update and create
  function upload() {
    url = "/_actions/supplier_add_update.php";
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
          alert('Name and phone number must be unique!');
        } else if (data == 'success') {
          clearForm();
        }
        loadDataList();
      })
      .then(clearForm)
      .catch(function(error) {
        console.error(error);
      })
  }
  //clear form data value
  function clearForm() {
    document.getElementById("input_id").value = "";
    document.getElementById("input_name").value = "";
    document.getElementById("input_phone").value = "";
    document.getElementById("input_address").value = "";
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