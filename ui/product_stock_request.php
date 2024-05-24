<?php

include_once 'connectdb.php';
session_start();

if ( $_SESSION['role'] == $amploye or $_SESSION['role'] == $warehouse or  $_SESSION['username'] == "") {

    header('location:logout.php');
}

if ($_SESSION['role'] == $admin) {
    include_once 'header.php';
}
?>
<style>
    .error{
        color: red;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Product Stock Request</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <!-- <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Starter Page</li> -->
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <form id="product_stock" name="product_stock" method="post">
                            <div class="card-header">
                                <h5 class="m-0">Product Stock Request To Warehouse</h5>
                            </div>
                            <div class="panel mt-3 mx-3 ">
                                <div class="row">
                                    <div class="mb-3 col-sm-2">
                                        <label class="form-label" for="pmdate"> Date</label>
                                        <input type="date" id="pmdate" name="pmdate" class="form-control" placeholder="Enter Date"  value="<?php echo date('Y-m-d');?>" readonly/>
                                        <div class="invalid-feedback"> Please enter Date. </div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="mb-3 col-sm-3">
                                        <label class="form-label" for="category">Category</label>
                                        <select class="form-control" id="categoryid" name="categoryid" >

                                        </select>
                                    </div>
                                    <div class="mb-3 col-sm-3">
                                        <label class="form-label" for="productid">Product</label>
                                        <select class="form-control" id="productid" name="productid" >

                                        </select>
                                    </div>
                                    <div class="mb-3 col-sm-2">
                                        <label class="form-label" for="qty">Qty</label>
                                        <input type="text" id="qty" name="qty" class="form-control" placeholder="Enter Qty " onkeyup="numonly('qty')" />
                                        <div class="invalid-feedback"> Please enter Qty </div>
                                    </div>
                                    
                                    <div class="mb-3 col-sm-2 mt-4 ">
                                        <button class="btn btn-primary offset-sm-5" name="add" id="add">+</button>
                                    </div>
                                </div>
                                <table class="table" id="table1">
                                    <thead>
                                        <tr>
                                            <th scope="col">Category</th>
                                            <th scope="col">Product</th>
                                            <th scope="col">Qty</th>
                                            <th scope="col">Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody id="addtable">
                                    </tbody>
                                </table>
                            </div>
                            <div class="row mt-3 my-5">
                                <div class="col-sm-2 offset-10 text-end">
                                    <button class="btn btn-primary offset-sm-3" type="submit" id="save" name="save">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.col-md-6 -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php
include_once "footer.php";
?>
<script src="assets/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="assets/plugins/jquery/pnotify.custom.js"></script>


<script>
    $(document).ready(function() {
        fillcategory();
    })

    $("#add").click(function(e) {
        e.preventDefault();
        var check = $("#productid").val();
        var qtycheck = $("#qty").val();
        
        if(check != null && check !== '' && qtycheck > 0 ){
            setpurchase();
        }else{
            showError("Somthing Went Wrong!");
            $('#addtable').html('');
        }
    });

    async function setpurchase() {
        var count = $('.tbl_totalamount').length;

        var category = $("#categoryid option:selected").text();
        var product = $("#productid option:selected").text();
        var productid = $("#productid").val();
        var categoryid = $("#categoryid").val();
        var qty = $("#qty").val() || 0;
       
        // console.log(p_stateid+'||'+s_stateid);
        var newRow = "";
        newRow += '<tr style="align-item:center;" data-index="' + count + '">';
        newRow += '<td><span class="tbl_categoryspan' + count + '">' + category + '</span><input type=hidden id="tbl_categoryid' + count + '" class="tbl_categoryid form-control" name="tbl_categoryid[]" value="' + categoryid+ '" ><input type=hidden id="tbl_category' + count + '" class="tbl_category form-control" name="tbl_category[]" value="' + category + '" ></td>';
        newRow += '<td><span class="tbl_productspan' + count + '">' + product + '</span><input type=hidden id="tbl_productid' + count + '" class="tbl_productid form-control" name="tbl_productid[]" value="' + productid + '" ><input type=hidden id="tbl_product' + count + '" class="tbl_product form-control" name="tbl_product[]" value="' + product + '" ></td>';
        newRow += '<td><span class="tbl_qtyspan' + count + '">' + qty + '</span><input type=hidden id="tbl_qty' + count + '" class="tbl_qty form-control" name="tbl_qty[]" style="width: 90px;" value="' + qty + '" ></td>';
        newRow += '<td><a href="javascript:void(0)" class="btn btn-danger rounded-pill tbl-btn text-end removetablerow"  ><i class="fas fa-trash "></i></a></td>';
        newRow += "</tr>";

        $("#addtable").append(newRow);
        $("#qty").val("");
    }

    $("body").on("click", '.removetablerow', function() {
        $(this).parent().parent().remove();
    });

    function fillcategory() {
        $.ajax({
            url: "fill_selection.php",
            type: "POST",
            dataType: 'json',
            data: {
                action: "fill_category"
            },
            success: function(data) {
                var myJSON = JSON.stringify(data);
                var json = JSON.parse(myJSON);
                if (json.status === 1) {
                    var op_product = '<option value="">Select Category</option>';
                    var row = json.data;
                    for (var j = 0; j < row.length; j++) {
                        op_product += '<option value="' + row[j].catid + '">' + row[j].category + '</option>'
                    }
                    $('#categoryid').html(op_product);
                } else {
                    $('#categoryid').html();
                }
            }
        })
    }

    $('#categoryid').change(function() {
        product();
    });

    function product() {
        var category_id = $('#categoryid').val();
        $.ajax({
            url: 'fill_selection.php',
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'fill_product',
                category_id: category_id
            },
            success: function(data) {
                var myJSON = JSON.stringify(data);
                var json = JSON.parse(myJSON);
                if (json.status === 1) {
                    var op_product = '<option value="">Select Product</option>';;
                    var row = json.data;
                    for (var j = 0; j < row.length; j++) {
                        op_product += '<option value="' + row[j].pid + '">' + row[j].product + '</option>'
                    }
                    $('#productid').html(op_product);
                } else {
                    $('#productid').html();
                }
            }
        });
    }
    $("body").on("input", '#qty', function () {
        var check= parseFloat($('#qty').val());
        if(check > 1000){
            $('#qty').val(0);
            showError('Please Request Less Than 1000');
        }
    });

if($('#product_stock').length){   
    $('#product_stock').validate({
        rules: {
            pmdate: {
                required: true
            }
        },
        messages: {
            pmdate: {
                required: "Date is required"
            }
        },
        submitHandler: function(form) {
            var values = $('#pmdate').val();
            var purchase_table = [];
            $('#addtable tr').each(function() {
                var sub_item = {
                    tbl_categoryid: $(this).find('.tbl_categoryid').val(),
                    tbl_category: $(this).find('.tbl_category').val(),
                    tbl_productid: $(this).find('.tbl_productid').val(),
                    tbl_product: $(this).find('.tbl_product').val(),
                    tbl_qty: $(this).find('.tbl_qty').val(),
                };
                purchase_table.push(sub_item);
            });
            if(purchase_table.length>0){
                $.ajax({
                    type: "POST",
                    url: "getdata.php",
                    data: {
                        action: "product_stock",
                        data: values,
                        purchase_table: purchase_table
                    },
                    success: function(data) {
                        var json = JSON.parse(data);
                        $("#product_stock")[0].reset();
                        $('#addtable').html('');
                        showMessage(json.message);
                    }
                });
            }else{
                showError("Please Add at list one Stock Request");
            }
           
        }
    });
}
</script>
