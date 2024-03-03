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
          <a class="btn btn-default btn-block" onclick="loadDataList('profit-lose','thismonth')">Profit and Lose</a>
          <a class="btn btn-default btn-block" onclick="loadDataList('cash-flow','thismonth')">CashFlow</a>
          <a class="btn btn-default btn-block" onclick="loadDataList('bank-payment','thismonth')">BankPayment</a>
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
    } else {
      date = document.getElementById('date').value;
    }
    if (report == 'profit-lose') { //profit and lose
      if (date.length == 0) {
        fetch("/reports/finical/profit_lose.php")
          .then(resp => resp.text())
          .then(data => document.getElementById('info-area').innerHTML = data)
          .catch()
      } else {
        fetch("/reports/finical/profit_lose.php?date=" + date)
          .then(res => res.text())
          .then(data => document.getElementById("info-area").innerHTML = data);
      }
      document.getElementById('typeId').value = report;
    } else if (report == 'cash-flow') { //cash flow
      if (date.length == 0) {
        fetch("/reports/finical/cash_flow.php")
          .then(resp => resp.text())
          .then(data => document.getElementById('info-area').innerHTML = data)
          .catch()
      } else {
        fetch("/reports/finical/cash_flow.php?date=" + date)
          .then(res => res.text())
          .then(data => document.getElementById("info-area").innerHTML = data);
      }
      document.getElementById('typeId').value = report;
    } else if (report == 'bank-payment') { //cash flow
      if (date.length == 0) {
        fetch("/reports/finical/bank_payment.php")
          .then(resp => resp.text())
          .then(data => document.getElementById('info-area').innerHTML = data)
          .catch()
      } else {
        fetch("/reports/finical/bank_payment.php?date=" + date)
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
    loadDataList('profit-lose', 'thismonth');
  }
</script>
<?php
require 'footer.php';
?>