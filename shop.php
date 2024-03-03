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
      .then(resp => resp.text())
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
      .then(resp => resp.text())
      .catch()
  }
  //load payment type
  function loadPayment() {
    fetch("/_server/payment_data.php")
      .then(resp => resp.text())
      .then(data => document.getElementById('info-area').innerHTML = data)
      .catch()
  }
  //update payment type
  function payment_update() {
    const formData = new FormData(document.getElementById('payment-type-id'));
    fetch('/_server/payment_data.php', {
        method: 'post',
        body: formData
      })
      .then(resp => resp.text())
      .then(data => document.getElementById('info-area').innerHTML = data)
      .catch();
      clear_form();
  }
  //get data to edit
  function edit(id) {
    fetch("/_server/payment_data.php?id=" + id)
      .then(resp => resp.text())
      .then(data => document.getElementById('info-area').innerHTML = data)
      .catch()
  }
  //edit form clear
  function clear_form() {
    document.getElementById('pay_id').value = "";
    document.getElementById('name_id').value = "";
    document.getElementById('desc_id').value = "";
  }
  //init state
  window.onload = function() {
    loadShopInfo();
  }
</script>
<?php
require 'footer.php';
?>