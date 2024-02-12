<?php
require '_actions/auth.php';
require 'config/config.php';
check_auth();
?>

<!-- remove number arrow -->
<style>
  /* Chrome, Safari, Edge, Opera */
  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  /* Firefox */
  input[type=number] {
    -moz-appearance: textfield;
  }
</style>

<?php require 'header.php'; ?>
<style>
  .act-button {
    position: fixed;
    right: 10px;
    bottom: 10px;
  }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- selected item list -->
        <div class="col-md-9">
          <div class="card">
            <div class="card-header">

              <form method="post" id="item-form">
                <div class="row">
                  <div class="col-md-6">
                    <!-- get product list -->
                    <?php
                    $pSql = "SELECT * FROM products WHERE isdeleted=0";
                    $pPdo = $pdo->prepare($pSql);
                    $pPdo->execute();
                    $products = $pPdo->fetchall(PDO::FETCH_OBJ);
                    ?>
                    <select name="item_id" class="sproduct form-control">
                      <option value="0">-- Select Item --</option>
                      <?php foreach ($products as $product) : ?>
                        <option value="<?= $product->id ?>">
                          <?= $product->name ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <button type="button" class="btn btn-primary float-right" onclick="addItem()">Add Item</button>
                  </div>
                </div>
              </form>
            </div>
            <div class="card-body" id="body">
              <table class='table table-bordered table-striped'>
                <thead>
                  <tr>
                    <th width="50px">No</th>
                    <th width="200px">Code</th>
                    <th>Name</th>
                    <th width="120px">Quantity</th>
                    <th width="150px">Price</th>
                    <th width="200px">Amount</th>
                    <th width="50px">#</th>
                  </tr>
                </thead>
                <tbody id="product_data"></tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- voucher info -->
        <div class="col-md-3">
          <form class="mt-1" id="sale-form">
            <?php
            //get customer list
            $custSql = "SELECT * FROM customers WHERE id!=1 AND isdeleted=0 ORDER BY name";
            $custPdo = $pdo->prepare($custSql);
            $custPdo->execute();
            $customers = $custPdo->fetchAll(PDO::FETCH_OBJ);
            ?>
            <div class="form-group">
              <label>CustomerName</label>
              <select name="customerId" id="cid" class="form-control" onclick="viewAmount()">
                <option value="1">DefaultCustomer</option>
                <?php foreach ($customers as $customer) : ?>
                  <option value="<?= $customer->id ?>"><?= $customer->name ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Phone</label>
              <input type="text" class="form-control" id="phone" disabled>
            </div>
            <div class="form-group">
              <label>Address</label>
              <input type="address" class="form-control" id="address" disabled>
            </div>
            <div class="form-group">
              <label>Net Price</label>
              <input type="text" class="form-control" disabled="disabled" name="netPrice" id="netPriceId">
            </div>
            <div class="form-group">
              <label>Discount</label>
              <input type="number" class="form-control" value="0" name="discount" id="dis" onfocusout="viewAmount()">
            </div>
            <div class="form-group">
              <label>Total Price</label>
              <input type="text" class="form-control" disabled="disabled" name="totPrice" id="totPriceId">
            </div>
          </form>
        </div>
        <div class="act-button">
          <button class="btn btn-warning mr-2" onclick="sitemClear('sale-item')">Clear All Item</button>
          <button class="btn btn-primary" onclick="paid()">Paid</button>
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
  function loadDataList() {
    fetch("/_server/sale_data.php")
      .then(resp => resp.text())
      .then(data => document.getElementById("product_data").innerHTML = data)
      .then((dd) => {
        setTimeout(() => {
          viewAmount();
        }, 100);
      })
      .catch()
  }
  //add item
  function addItem() {
    const formData = new FormData(document.getElementById("item-form"));
    fetch("/_server/sale_data.php", {
        method: "post",
        body: formData
      })
      .then(resp => resp.text())
      // .then(data => console.log(data))
      .then(data => document.getElementById("product_data").innerHTML = data)
      .then(viewAmount)
      .catch()
  }
  //add quantity
  function addQuantity(id, qty) {
    if (qty <= 0) {
      qty = 1;
    }
    fetch("/_server/sale_data.php?id=" + id + "&qty=" + qty)
      .then(resp => resp.text())
      .then(loadDataList)
      .then(viewAmount)
      .catch()
  }
  //add price
  function addPrice(id, price) {
    if (price < 0) {
      price = 0;
    }
    fetch("/_server/sale_data.php?id=" + id + "&price=" + price)
      .then(resp => resp.text())
      .then(loadDataList)
      .then(viewAmount)
      .catch()
  }
  //user view total amount
  function viewAmount() {
    //for customer selection
    let cust = <?= json_encode($customers) ?>; //customer list
    let cid = Number(document.getElementById("cid").value);
    cust.forEach(element => {
      if (element.id == cid) {
        document.getElementById("phone").value = element.phone;
        document.getElementById("address").value = element.address;
      } else if (cid == 1) {
        document.getElementById("phone").value = "-";
        document.getElementById("address").value = "-";
      }
    });
    //total amount
    let netPrice = 0;
    let totPrice = 0;
    let amounts = document.getElementsByClassName('c-amount');
    let discount = document.getElementById("dis").value;
    const arr = [...amounts].map(input => input.innerText);
    arr.forEach(amount => {
      netPrice += Number(amount);
    })
    totPrice = netPrice - discount;
    document.getElementById("netPriceId").value = netPrice;
    document.getElementById("totPriceId").value = totPrice;
  }
  //paid
  function paid() {
    document.getElementById("netPriceId").disabled = false;
    document.getElementById("totPriceId").disabled = false;
    const formData = new FormData(document.getElementById("sale-form"));
    document.getElementById("netPriceId").disabled = true;
    document.getElementById("totPriceId").disabled = true;
    fetch("/_server/sale_data.php", {
        method: "POST",
        body: formData
      })
      .then(resp => resp.text())
      .then(data => console.log(data))
      .then(loadDataList)
      .then(document.getElementById("dis").value = 0)
      .then(viewAmount)
      .catch()
  }
  //clear selected items
  function sitemClear(str) {
    fetch("/_actions/sitem_clear.php?" + str + "=1")
      .then(resp => resp.text())
      .then(loadDataList())
      .then(document.getElementById("dis").value = 0)
      .catch()
  }
  //delete product by id
  function deleteProduct(id) {
    fetch("/_server/sale_data.php?del_id=" + id)
      .then(resp => resp.text())
      .then(loadDataList())
      .catch()
  }
  //initial state
  window.onload = function() {
    loadDataList();
  };
</script>
<?php
require 'footer.php';
?>