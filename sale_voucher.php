<?php
require '_actions/auth.php';
check_auth();
?>

<?php require 'header.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="container">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width='50px'>No</th>
                            <th width='150px'>Date</th>
                            <th>Invoice No.</th>
                            <th>Customer Name</th>
                            <th>Total</th>
                            <th width='50px'>#</th>
                        </tr>
                    </thead>
                    <tbody id="v-list">
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    //get vuocher list
    function loadDataList(search = '') {
        if (search.length == 0) {
            fetch("/reports/sale_voucher.php")
                .then(res => res.text()).
            then(data => document.getElementById("v-list").innerHTML = data);
        } else {
            fetch("/reports/sale_voucher.php?search=" + search)
                .then(res => res.text()).
            then(data => document.getElementById("v-list").innerHTML = data);
        }
    }
    //init state
    window.onload = function() {
        loadDataList();
    }
</script>
<?php
require 'footer.php';
?>