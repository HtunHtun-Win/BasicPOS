<?php
session_start();
require '_actions/auth.php';
require 'config/config.php';
check_auth();
check_privilege();
//Get Category List
$catSql = "SELECT * FROM categories WHERE isdeleted=0";
$catPdo = $pdo->prepare($catSql);
$catPdo->execute();
$categories = $catPdo->fetchAll(PDO::FETCH_OBJ);
//Get Product data
if ($_GET['id']) {
    $id = $_GET['id'];
    $pdSql = "SELECT * FROM products WHERE id=$id";
    $pPdo = $pdo->prepare($pdSql);
    $pPdo->execute();
    $product = $pPdo->fetchObject();
}
?>

<?php require 'header.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <!-- form -->
            <form method="post" id="myForm">
                <div class="card">
                    <div class="card-body">
                        <!-- product id to edit -->
                        <input type="hidden" name="id" id="input_id" value="<?= $product->id ?>">
                        <div class="row">
                            <!-- input left -->
                            <div class="col-md-5">
                                <!-- name -->
                                <div class="from-group">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" name="name" id="input_name" value="<?= $product->name ?>">
                                </div>
                                <!-- code -->
                                <div class="from-group">
                                    <label for="">Code</label>
                                    <input type="text" class="form-control" name="code" id="input_code" value="<?= $product->code ?>">
                                </div>
                                <!-- Purchase Price -->
                                <div class=" from-group">
                                    <label for="">Purchase Price</label>
                                    <input type="number" class="form-control" name="pprice" id="input_pprice" value="<?= $product->purchase_price ?>" <?php if ($product) {
                                                                                                                                                            echo "disabled";
                                                                                                                                                        } ?>>
                                </div>
                                <!-- Sale Price -->
                                <div class="from-group">
                                    <label for="">Sale Price</label>
                                    <input type="number" class="form-control" name="sprice" id="input_sprice" value="<?= $product->sale_price ?>" <?php if ($product) {
                                                                                                                                                        echo "disabled";
                                                                                                                                                    } ?>>
                                </div>
                            </div>
                            <!-- input space -->
                            <div class="col-md-1"></div>
                            <!-- input right -->
                            <div class="col-md-5">
                                <!-- Category -->
                                <div class="from-group">
                                    <label for="">Category</label>
                                    <select class="form-control" name="category">
                                        <?php foreach ($categories as $category) : ?>
                                            <option value="<?= $category->id ?>" <?php if ($category->id == $product->category_id) {
                                                                                        echo "selected";
                                                                                    } ?>><?= $category->name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- Quantity -->
                                <div class="from-group">
                                    <label for="">Quantity</label>
                                    <input type="number" class="form-control" name="quantity" id="input_quantity" value="<?= $product->quantity ?>" <?php if ($product) {
                                                                                                                                                        echo "disabled";
                                                                                                                                                    } ?>>
                                </div>
                                <!-- description -->
                                <div class="from-group">
                                    <label for="">Description</label>
                                    <textarea name="description" id="input_description" rows="5" class="form-control"><?= $product->description ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <Button type="button" class="btn btn-warning" onclick="clearForm()">Clear</Button>
                        <Button type="button" class="btn btn-primary" onclick="upload()">Save</Button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    //add product
    function upload() {
        url = "/_actions/product_add_update.php";
        const formData = new FormData(document.getElementById("myForm"));
        fetch(url, {
                method: "POST",
                body: formData
            }).then(resp => resp.text())
            .then(function(data) {
                chk_id = document.getElementById("input_id").value;
                console.log(chk_id);
                if (chk_id) {
                    window.location.href = "/product.php";
                }else if(data=='success') {
                    clearForm();
                }else if(data=='exist'){
                    alert('Code can\'s be duplicate value!');
                }
                
            })
            .catch(function(error) {
                console.error(error);
            })
    }
    //clear form data value
    function clearForm() {
        document.getElementById("input_id").value = "";
        document.getElementById("input_name").value = "";
        document.getElementById("input_code").value = "";
        document.getElementById("input_pprice").value = "";
        document.getElementById("input_sprice").value = "";
        document.getElementById("input_quantity").value = "";
        document.getElementById("input_description").value = "";
    }
</script>
<?php
require 'footer.php';
?>