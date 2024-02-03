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
        <!-- user list -->
        <div class="col-md-12">
          <table class='table table-bordered table-striped'>
            <thead>
              <tr>
                <th>No</th>
                <th>Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Purchase Price</th>
                <th>Sale Price</th>
                <th width="50px">#</th>
                <th width="50px">#</th>
              </tr>
            </thead>
            <tbody id="product_data"></tbody>
          </table>
          <center id="msg"></center>
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
      fetch("/_server/product_data.php")
        .then(res => res.text()).
      then(function(data) {
        if (data.length == 0) {
          document.getElementById("product_data").innerHTML = data;
          document.getElementById("msg").innerHTML = "<center>No data</center>";
        } else {
          document.getElementById("product_data").innerHTML = data;
          document.getElementById("msg").innerHTML = "";
        }
      });
    } else {
      fetch("/_server/product_data.php?search=" + search)
        .then(res => res.text()).
      then(function(data) {
        if (data.length == 0) {
          document.getElementById("product_data").innerHTML = data;
          document.getElementById("msg").innerHTML = "<center>No data</center>";
        } else {
          document.getElementById("product_data").innerHTML = data;
          document.getElementById("msg").innerHTML = "";
        }

      });
    }
  }
  //delete category by id
  function deleteProduct(id) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "/_actions/product_delete.php?id=" + id, true);
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
      }).then(resp => console.log(resp.text()))
      .then(loadDataList())
      .catch(function(error) {
        console.error(error);
      })
    clearForm();
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