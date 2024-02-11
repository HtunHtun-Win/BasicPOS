<?php
require '_actions/auth.php';
require 'config/config.php';
check_auth();
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
          <div class="card">
            <div class="card-header">
              <a href="/product_add_update.php" class="btn btn-primary float-right">Add Item</a>
            </div>
            <div class="card-body" id="body">
              <table class='table table-bordered table-striped'>
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Purchase Price</th>
                    <th>Sale Price</th>
                    <th width="50px">#</th>
                    <th width="50px">#</th>
                  </tr>
                </thead>
                <tbody id="product_data"></tbody>
              </table>
            </div>
          </div>
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
  //get product list
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
  //delete product by id
  function deleteProduct(id) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "/_actions/product_delete.php?id=" + id, true);
    xmlhttp.send();
    loadDataList();
  }
  //initial state
  window.onload = function() {
    loadDataList();
  };
</script>
<?php
require 'footer.php';
?>