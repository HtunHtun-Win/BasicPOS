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
          <a class="btn btn-default btn-block" onclick="loadDataList('purchase-item')">Purchase items</a>
          <a class="btn btn-default btn-block" onclick="loadDataList('purchase-voucher')">Purchase Voucher</a>
          <a class="btn btn-default btn-block" onclick="loadDataList('purchase-by-supplier')">Purchase By Customer</a>
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
  function loadDataList(report = "", date = "") {
    console.log(report);
    console.log(date);
    if (date == "custom") {
      date = document.getElementById('datepicker').value;
    }
    if (report == 'purchase-item') { //purchase items
      if (date.length == 0) {
        fetch("/reports/purchase/purchase_items.php")
          .then(resp => resp.text())
          .then(data => document.getElementById('info-area').innerHTML = data)
          .catch()
      } else {
        fetch("/reports/purchase/purchase_items.php?date=" + date)
          .then(res => res.text())
          .then(data => document.getElementById("info-area").innerHTML = data);
      }
      document.getElementById('typeId').value = report;
    } else if (report == 'purchase-voucher') { //purchase vouchers
      if (date.length == 0) {
        fetch("/reports/purchase/purchase_vc.php")
          .then(resp => resp.text())
          .then(data => document.getElementById('info-area').innerHTML = data)
          .catch()
      } else {
        fetch("/reports/purchase/purchase_vc.php?date=" + date)
          .then(res => res.text())
          .then(data => document.getElementById("info-area").innerHTML = data);
      }
      document.getElementById('typeId').value = report;
    } else if (report == 'purchase-by-supplier') { //purchase by supplier
      if (date.length == 0) {
        fetch("/reports/purchase/purchase_by_supplier.php")
          .then(resp => resp.text())
          .then(data => document.getElementById('info-area').innerHTML = data)
          .catch()
      } else {
        fetch("/reports/purchase/purchase_by_supplier.php?date=" + date)
          .then(res => res.text())
          .then(data => document.getElementById("info-area").innerHTML = data);
      }
      document.getElementById('typeId').value = report;
    }
  }
  //
  function getType() {
    return document.getElementById('typeId').value;
  }
  //supplier filter
  function filterSupplier() {
    var supplier = document.getElementById('supplier').value;
    var date = document.getElementById('date').value;
    if (date) {
      fetch("/reports/purchase/purchase_by_supplier.php?supplier=" + supplier + "&date=" + date)
        .then(resp => resp.text())
        .then(data => document.getElementById('info-area').innerHTML = data)
        .catch()
    } else {
      fetch("/reports/purchase/purchase_by_supplier.php?supplier=" + supplier)
        .then(resp => resp.text())
        .then(data => document.getElementById('info-area').innerHTML = data)
        .catch()
    }

  }
  //init state
  window.onload = function() {
    loadDataList('purchase-item', 'today');
  }
</script>
<?php
require 'footer.php';
?>