<?php
require '_actions/auth.php';
require 'config/config.php';
check_auth();
check_privilege();
?>

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
                <!-- user list -->
                <div class="col-md-12">
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
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th width="100px">Quantity</th>
                                        <th width="200px">Adjust Quantity</th>
                                        <th width="200px">New Quantity</th>
                                        <th width="50px">#</th>
                                    </tr>
                                </thead>
                                <tbody id="product_data"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="act-button">
                    <button class="btn btn-warning mr-2" onclick="sitemClear()">Clear All Item</button>
                    <button class="btn btn-primary" onclick="save()">Save</button>
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
        fetch("/_server/product_adjust.php")
            .then(resp => resp.text())
            .then(data => document.getElementById("product_data").innerHTML = data)
            .catch()
    }
    //add item
    function addItem() {
        const formData = new FormData(document.getElementById("item-form"));
        fetch("/_server/product_adjust.php", {
                method: "post",
                body: formData
            })
            .then(resp => resp.text())
            // .then(data => console.log(data))
            .then(data => document.getElementById("product_data").innerHTML = data)
            .catch()
    }
    //add quantity
    function addQuantity(id, qty) {
        fetch("/_server/product_adjust.php?id=" + id + "&qty=" + qty)
            .then(resp => resp.text())
            .then(data => console.log(data))
            .catch()
    }
    //clear selected items
    function sitemClear() {
        fetch("/_actions/sitem_clear.php")
            .then(resp => resp.text())
            .then(loadDataList())
            .catch()
    }
    //delete product by id
    function deleteProduct(id) {
        fetch("/_server/product_adjust.php?del_id=" + id)
            .then(resp => resp.text())
            .then(loadDataList())
            .catch()
    }
    //save
    function save() {
        fetch("/_server/product_adjust.php?save=" + 1)
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