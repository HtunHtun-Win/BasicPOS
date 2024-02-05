<?php
require '_actions/auth.php';
require 'config/config.php';
check_auth();
check_privilege();
//get category info to edit
if ($_GET['id']) {
  $cfSql = "SELECT * FROM categories WHERE id=" . $_GET['id'];
  $cfPdo = $pdo->prepare($cfSql);
  $cfPdo->execute();
  $catinfo = $cfPdo->fetchObject();
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
          <form method="post" id="myForm">
            <input type="hidden" name="id" id="input_id" value="<?= $catinfo->id ?>">
            <div class='from-group'>
              <label>Name</label>
              <input type="text" name="name" id="input_name" class='form-control' value="<?= $catinfo->name ?>" required>
            </div>
            <div class='from-group mt-3'>
              <label>Description</label>
              <textarea type="text" name="description" id="input_desc" class='form-control' required><?= $catinfo->description ?></textarea>
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
      fetch("/_server/category_data.php")
        .then(res => res.text()).
      then(data => document.getElementById("user_list").innerHTML = data);
    } else {
      fetch("/_server/category_data.php?search=" + search)
        .then(res => res.text()).
      then(data => document.getElementById("user_list").innerHTML = data);
    }
  }
  //delete category by id
  function deleteCategory(id) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "/_actions/category_delete.php?id=" + id, true);
    xmlhttp.send();
    loadDataList();
  }
  //post data to update and create
  function upload() {
    url = "_actions/category_add_update.php";
    const formData = new FormData(document.getElementById("myForm"));
    fetch(url, {
        method: "POST",
        body: formData
      }).then(resp => resp.text())
      .then(function(data) {
        if (data == 'exist') {
          alert('Category name must be unique!');
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
    document.getElementById("input_desc").value = "";
  }
  //initial state
  window.onload = function() {
    // setInterval(() => {
    //   loadDataList();
    // }, 1000);
    loadDataList();
  };
</script>
<?php
require 'footer.php';
?>