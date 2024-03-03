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
          <a class="btn btn-default btn-block" onclick="loadShopInfo()">Shop Info</a>
          <a class="btn btn-default btn-block" onclick="loadVoucherData(1)">Voucher Info</a>
          <a class="btn btn-default btn-block" onclick="loadPayment()">Payment Type</a>
        </div>
        <div class="col-md-4">
          <div id="info-area"></div>
        </div>
        <div class="col-md-4"></div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
  //get shop info
  function loadShopInfo() {
    fetch("/_server/shop_info.php")
      .then(resp => resp.text())
      .then(data => document.getElementById('info-area').innerHTML = data)
      .catch()
  }
  //update shop info
  function shop_update() {
    const formData = new FormData(document.getElementById('shop-info-id'));
    fetch('/_actions/shop_info_update.php', {
        method: 'post',
        body: formData
      })
      .then(resp => resp.data())
      .catch()
  }
  //load voucher data
  function loadVoucherData(id) {
    fetch("/_server/voucher_data.php?id=" + id)
      .then(resp => resp.text())
      .then(data => document.getElementById('info-area').innerHTML = data)
      .catch()
  }
  //update voucher info
  function voucher_update() {
    const formData = new FormData(document.getElementById('voucher-info-id'));
    fetch('/_actions/voucher_update.php', {
        method: 'post',
        body: formData
      })
      .then(resp => resp.data())
      .catch()
  }
  //load payment type
  function loadPayment() {
    fetch("/_server/payment_data.php")
      .then(resp => resp.text())
      .then(data => document.getElementById('info-area').innerHTML = data)
      .catch()
  }
  //init state
  window.onload = function() {
    loadShopInfo();
  }
</script>
<?php
require 'footer.php';
?>