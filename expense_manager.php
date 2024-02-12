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
        <div class="col-md-4 mt-3">
          <form id="myForm">
            <!-- id to edit -->
            <input type="hidden" id="input_id" value="1">
            <!-- choose type -->
            <div class="form-group">
              <input type="radio" name="type" value='1' id="in" checked>
              <label for="in">Income</label>
            </div>
            <div class="form-group">
              <input type="radio" name="type" value='2' id="out">
              <label for="out">Expense</label>
            </div>
            <!-- Description -->
            <div class="form-group">
              <label>Description</label>
              <input type="text" name="desc" id="input_desc" class="form-control" value=''>
            </div>
            <!-- Amount -->
            <div class="form-group">
              <label>Amount</label>
              <input type="text" name="amount" id="input_amount" class="form-control" value=''>
            </div>
            <!-- Note -->
            <div class="form-group">
              <label>Note</label>
              <textarea name="note" id="input_note" class="form-control" rows="5"></textarea>
            </div>
            <div class="float-right">
              <button type="button" class="btn btn-warning" onclick="clearForm()">Clear</button>
              <button type="button" class="btn btn-primary" onclick="upload()">Save</button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div id="data-list"></div>
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
      fetch("/_server/expense_data.php")
        .then(res => res.text()).
      then(data => document.getElementById("data-list").innerHTML = data);
    } else {
      fetch("/_server/expense_data.php?search=" + search)
        .then(res => res.text()).
      then(data => document.getElementById("data-list").innerHTML = data);
    }
  }
  //delete data by id
  function deleteUser(id) {

  }
  //post data to update and create
  function upload() {
    url = "/_actions/expense_add_update.php";
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
    document.getElementById("input_amount").value = "";
    document.getElementById("input_desc").value = "";
    document.getElementById("input_note").value = "";
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