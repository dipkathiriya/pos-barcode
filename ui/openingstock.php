<?php

include_once 'connectdb.php';
session_start();

if ($_SESSION['role'] == $amploye or $_SESSION['role'] == $admin or $_SESSION['username'] == "") {
    header('location:logout.php');
}

if ($_SESSION['role'] == $warehouse) {
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
                    <h1 class="m-0">Warehouse Opening Stock</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Warehouse Opening Stock</li>
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
                        <form id="opning_stock" name="opning_stock" method="post">
                            <div class="card-header">
                                <h5 class="m-0">Opening Stock</h5>
                            </div>
                            <div class="panel mt-3 mx-3 "> 
                                <div class="row mt-5">     
                                    <div class="mb-3 col-sm-3">
                                        <label class="form-label" for="date">Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="date" name="date" required>
                                    </div>
                                   <div class="mb-3 col-sm-3 mt-4" >
                                        <button class="btn btn-primary offset-sm-3" type="submit" id="save" name="save">Submit</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive" style="height: 580px;overflow-y: scroll;">
                                            <table class="table  table-borderless table-primary align-middle">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Category</th>
                                                        <th>Product</th>
                                                        <th>Current Stock</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-group-divider stockbody">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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
<script>
$(document).ready(function(){
    fill_productstock();
})

function fill_productstock(){
    $.ajax({
        url: 'fill_selection.php',
        method: 'POST',
        data: { action:'fill_productstock'},
        success: function(data){
            var json=JSON.parse(data);
            if(json.status ==1){
                var op_product='';
                var row=json.data;
                // console.log(row);
                for(var j=0;j< row.length;j++)
                {
                    if(row[j].opning_stock !== 0){
                        op_product+='<tr>';
                            op_product+='<td>'+row[j].category+'<input type="hidden" class="tbl_categoryid" id="tbl_categoryid'+row[j].id+'" name="tbl_categoryid[]" value="'+row[j].cid+'"><input type="hidden" class="tbl_category" id="tbl_category'+row[j].id+'" name="tbl_category[]" value="'+row[j].category+'"></td>';
                            op_product+='<td>'+row[j].product+'<input type="hidden" class="tbl_productid" id="tbl_productid'+row[j].id+'" name="tbl_productid[]" value="'+row[j].id+'"><input type="hidden" class="tbl_product" id="tbl_product'+row[j].id+'" name="tbl_product[]" value="'+row[j].product+'"></td>';
                            op_product+='<td>'+row[j].opning_stock+'<input type="hidden" class="tbl_stock" id="tbl_stock'+row[j].id+'" name="tbl_stock[]" value="'+row[j].opning_stock+'"></td>';
                        op_product+='</tr>';
                    }
                }
                // console.log(op_html);
                $('.stockbody').html(op_product);
                showMessage(json.message);
            }else{
                showError(json.message);
            }
        }
    });
}
$('#opning_stock').validate({
    rules: {
        date: {
            required: true
        }
    },
    messages: {
        date: {
            required: "Date Is Required"
        }
    },
    submitHandler: function(form) {
        var date = $('#date').val();
        var product_stock = [];
        $('.stockbody tr').each(function(){
            var sub_item = {
                tbl_categoryid: $(this).find('.tbl_categoryid').val(),
                tbl_category: $(this).find('.tbl_category').val(),
                tbl_productid: $(this).find('.tbl_productid').val(),
                tbl_product: $(this).find('.tbl_product').val(),
                tbl_stock: $(this).find('.tbl_stock').val()
            };
            product_stock.push(sub_item);
        });
        $.ajax({
            type: "POST",
            url: "getdata.php",
            data: {
                action: "opning_stock",
                product_stock: product_stock,
                data:date
            },
            success: function(data) {
                var json=JSON.parse(data);
                if(json.status ==1){
                    $('#date').val("");
                    fill_productstock();
                    showMessage(json.message);
                }else{
                    showError(json.message);
                }
            }
        });
    }
});
</script>