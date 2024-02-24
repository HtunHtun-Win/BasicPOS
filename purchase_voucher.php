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
            <div class="row">
                <!-- voucher list -->
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width='50px'>No</th>
                                <th width='150px'>Date</th>
                                <th width='200px'>Invoice No.</th>
                                <th>Customer Name</th>
                                <th>Total</th>
                                <th width='50px'>#</th>
                                <th width='50px'>#</th>
                            </tr>
                        </thead>
                        <tbody id="v-list">
                        </tbody>
                    </table>
                </div>
                <!-- voucher preview -->
                <div class="col-md-4">
                    <div id="v-detail"></div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    //get voucher list
    function loadDataList(search = '', date = '') {
        date = document.getElementById('date').value;
        if (date == "custom") {
            date = document.getElementById('datepicker').value;
        }
        search = document.getElementById('search').value;
        if (search.length == 0 && date.length == 0) {
            fetch("/reports/purchase_voucher.php")
                .then(res => res.text()).
            then(data => document.getElementById("v-list").innerHTML = data);
        } else {
            if (date.length != 0) {
                if (search.length == 0) {
                    fetch("/reports/purchase_voucher.php?date=" + date)
                        .then(res => res.text())
                        .then(data => document.getElementById("v-list").innerHTML = data);
                } else {
                    fetch("/reports/purchase_voucher.php?search=" + search + "&date=" + date)
                        .then(res => res.text())
                        .then(data => document.getElementById("v-list").innerHTML = data);
                }
            } else {
                fetch("/reports/purchase_voucher.php?search=" + search)
                    .then(res => res.text())
                    .then(data => document.getElementById("v-list").innerHTML = data);
            }
        }
    }
    //get voucher detail by id
    function voucherDetail(id) {
        fetch("/reports/purchase_voucher_detail.php?vid=" + id)
            .then(res => res.text())
            .then(data => document.getElementById("v-detail").innerHTML = data);
    }
    //voucher edit
    function voucherEdit(id) {
        fetch("/_actions/purchase_voucher_edit.php?purchase_id=" + id)
            .then(res => res.text())
            .then(data =>
                setTimeout(() => {
                    window.location.href = "/purchase.php"
                }, 500)
            );
    }
    //init state
    window.onload = function() {
        loadDataList();
    }
</script>
<?php
require 'footer.php';
?>