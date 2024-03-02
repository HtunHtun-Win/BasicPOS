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
        <div class="col-md-4 mt-2">
          <a class="btn btn-default btn-block" onclick="loadDataList('product-balance')">Product Balance</a>
          <a class="btn btn-default btn-block" onclick="loadDataList('product-balance-valuation')">Product Balance With Valuation</a>
          <a class="btn btn-default btn-block" onclick="loadDataList('product-sprice-changes')">Sale Price Changes</a>
        </div>
        <div class="col-md-8">
          <div id="info-area"></div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
  //get shop info
  function loadDataList(report = "", date = "", search = "") {
    console.log(report);
    console.log(date);
    if (date == "custom") {
      date = document.getElementById('datepicker').value;
    }
    if (report == 'product-balance') { //Product Balance
      fetch("/reports/inventory/product_balance.php")
        .then(resp => resp.text())
        .then(data => document.getElementById('info-area').innerHTML = data)
        .catch()
      document.getElementById('typeId').value = report;
      document.getElementById('price-search').style.display = "none";
      document.getElementById('date').style.display = "none";
    } else if (report == 'product-balance-valuation') { //Product Balance valuation
      fetch("/reports/inventory/product_balance_valuation.php")
        .then(resp => resp.text())
        .then(data => document.getElementById('info-area').innerHTML = data)
        .catch()
      document.getElementById('typeId').value = report;
      document.getElementById('price-search').style.display = "none";
      document.getElementById('date').style.display = "none";
    } else if (report == 'product-sprice-changes') {
      if (search.length == 0) {
        fetch("/reports/inventory/product_sprice_changes.php")
          .then(resp => resp.text())
          .then(data => document.getElementById('info-area').innerHTML = data)
          .catch()
      } else {
        fetch("/reports/inventory/product_sprice_changes.php?search=" + search)
          .then(resp => resp.text())
          .then(data => document.getElementById('info-area').innerHTML = data)
          .catch()
      }
      document.getElementById('typeId').value = report;
      document.getElementById('price-search').style.display = "inline";
    }
  }
  //
  function getType() {
    return document.getElementById('typeId').value;
  }
  //init state
  window.onload = function() {
    loadDataList('product-balance');
  }
</script>
<?php
require 'footer.php';
?>