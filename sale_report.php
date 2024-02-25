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
          <a class="btn btn-default btn-block" onclick="loadDataList('sale-item')">Sale items</a>
          <a class="btn btn-default btn-block" onclick="loadDataList('sale-voucher')">Sale Voucher</a>
          <a class="btn btn-default btn-block" onclick="loadDataList('sale-by-customer')">Sale By Customer</a>
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
    if (report == 'sale-item') { //sale items
      if (date.length == 0) {
        fetch("/reports/sales/sale_items.php")
          .then(resp => resp.text())
          .then(data => document.getElementById('info-area').innerHTML = data)
          .catch()
      } else {
        fetch("/reports/sales/sale_items.php?date=" + date)
          .then(res => res.text())
          .then(data => document.getElementById("info-area").innerHTML = data);
      }
      document.getElementById('typeId').value = report;
    } else if (report == 'sale-voucher') { //sale vouchers
      if (date.length == 0) {
        fetch("/reports/sales/sale_vc.php")
          .then(resp => resp.text())
          .then(data => document.getElementById('info-area').innerHTML = data)
          .catch()
      } else {
        fetch("/reports/sales/sale_vc.php?date=" + date)
          .then(res => res.text())
          .then(data => document.getElementById("info-area").innerHTML = data);
      }
      document.getElementById('typeId').value = report;
    } else if (report == 'sale-by-customer') {
      if (date.length == 0) {
        fetch("/reports/sales/sale_by_customer.php")
          .then(resp => resp.text())
          .then(data => document.getElementById('info-area').innerHTML = data)
          .catch()
      } else {
        fetch("/reports/sales/sale_by_customer.php?date=" + date)
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
  //init state
  window.onload = function() {
    loadDataList('sale-item');
  }
</script>
<?php
require 'footer.php';
?>