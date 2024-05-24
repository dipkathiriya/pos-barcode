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
                    <h1 class="m-0">Purchase Master (Warehouse)</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="warehouse.php">Home</a></li>
                        <li class="breadcrumb-item active">Purchase Master</li>
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
                        <form id="purchase_master" name="purchase_master" method="post">
                            <div class="card-header">
                                <h5 class="m-0">Purchase Form</h5>
                            </div>
                            <div class="panel mt-3 mx-3 ">
                                <div class="row">
                                    <div class="mb-3 col-sm-3">
                                        <label class="form-label" for="venderid">Vender <span class="text-danger">*</span></label>
                                        <select class="form-control" name="venderid" id="venderid" >
                                            <option value="" disabled selected>Select Vender</option>
                                                <?php
                                                $select = $conn->prepare("SELECT * FROM tbl_vendermaster ORDER BY id DESC");
                                                $select->execute();

                                                while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                                                ?>
                                                    <option value=<?php echo $row['id'] ?>><?php echo $row['name'] ?></option>
                                                <?php
                                                }
                                                ?>
                                        </select>
                                        <div class="invalid-feedback"> Please enter Vender. </div>
                                    </div>
                                    <div class="mb-3 col-sm-3">
                                        <label class="form-label" for="billdate">Bill Date <span class="text-danger">*</span> </label>
                                        <input type="date" id="billdate" name="billdate" class="form-control" placeholder="Enter Date"  />
                                        <div class="invalid-feedback"> Please enter Bill Date. </div>
                                    </div>
                                    <div class="mb-3 col-sm-3">
                                        <label class="form-label" for="billno">Bill No <span class="text-danger">*</span></label>
                                        <input type="text" id="billno" name="billno" class="form-control" onkeyup="numonly('billno')"  />
                                        <div class="invalid-feedback"> Please Enter Bill No. </div>
                                    </div>
                                    <div class="mb-3 col-sm-2">
                                        <label class="form-label" for="pmdate"> Date <span class="text-danger">*</span></label>
                                        <input type="date" id="pmdate" name="pmdate" class="form-control" placeholder="Enter Date" />
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
                                    <div class="mb-3 col-sm-2">
                                        <label class="form-label" for="Rate">Rate</label>
                                        <input type="text" id="rate" name="rate" class="form-control" placeholder="Enter Rate " />
                                        <div class="invalid-feedback"> Please enter Rate </div>
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
                                            <th scope="col">Rate</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody id="addtable">
                                    </tbody>
                                </table>
                            </div>
                            <div class="row mx-3 mt-4">
                                <div class="mb-3 col-sm-6">
                                    <label class="form-label" for="des">Description </label>
                                    <textarea class="form-control" name="des" id="des"></textarea>
                                    <div class="invalid-feedback"> Please enter Description </div>
                                </div>
                                <div class="mb-3 col-sm-6">
                                    <div class="row ">
                                        <div class="mb-3 col-sm-4">
                                            <label class="form-label" for="tamount">Total Amount</label>
                                            <input type="text" class="form-control" id="tamount" name="tamount" readonly>
                                        </div>
                                        <div class="mb-3 col-sm-4">
                                            <label class="form-label" for="tqty">Total Qty</label>
                                            <input type="text" class="form-control" id="tqty" name="tqty" readonly>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="mb-3 col-sm-4 ">
                                            <label class="form-label" for="ramount">Round Amt</label>
                                            <input type="text" class="form-control" id="ramount" name="ramount" readonly>
                                        </div>
                                        <div class="mb-3 col-sm-4 ">
                                            <label class="form-label" for="famount">Final Amount</label>
                                            <input type="text" class="form-control" id="famount" name="famount" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3 my-5">
                                <div class="col-sm-3 offset-9 text-end">
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
        var ratecheck = $("#rate").val();
        if(check != null && check !== '' && qtycheck > 0 && ratecheck > 0){
            var check= parseFloat($('#rate').val());
            var id= $('#productid :selected').val();
        
            $.ajax({
                url: "fill_selection.php",
                type: "POST",
                dataType: 'json',
                data: {
                    id:id,
                    action: "check_prices"
                },
                success: function(data) {
                    var myJSON = JSON.stringify(data);
                    var json = JSON.parse(myJSON);
                    var row = json.data;
                    // console.log(row);
                    if(json.status === 1){
                        if (row[0].saleprice < check) {
                            showError(`product's sale price is ${row[0].saleprice}`);
                        }else{
                            setpurchase();
                            $('#qty').val('');
                            $('#rate').val('');
                        }
                    }else{
                        showError(json.message);
                    }
                }
            })
        }else{
            showError("Fill Product,Qty,Rate");
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
        var rate = $("#rate").val() || 0;
        var taxable = parseFloat(qty) * parseFloat(rate);
        var amount = parseFloat(taxable);
        // console.log(p_stateid+'||'+s_stateid);
        var newRow = "";
        newRow += '<tr style="align-item:center;" data-index="' + count + '">';
        newRow += '<td><span class="tbl_categoryspan' + count + '">' + category + '</span><input type=hidden id="tbl_categoryid' + count + '" class="tbl_categoryid form-control" name="tbl_categoryid[]" value="' + categoryid + '" ><input type=hidden id="tbl_category' + count + '" class="tbl_category form-control" name="tbl_category[]" value="' + category + '" ></td>';
        newRow += '<td><span class="tbl_productspan' + count + '">' + product + '</span><input type=hidden id="tbl_productid' + count + '" class="tbl_productid form-control" name="tbl_productid[]" value="' + productid+ '" ><input type=hidden id="tbl_product' + count + '" class="tbl_product form-control" name="tbl_product[]" value="' + product + '" ></td>';
        newRow += '<td><span class="tbl_qtyspan' + count + '">' + qty + '</span><input type=hidden id="tbl_qty' + count + '" class="tbl_qty form-control" name="tbl_qty[]" style="width: 90px;" value="' + qty + '" ></td>';
        newRow += '<td><span class="tbl_ratespan' + count + '">' + rate + '</span><input type=hidden id="tbl_rate' + count + '" class="tbl_rate form-control" name="tbl_rate[]" style="width: 90px;" value="' + rate + '" ></td>';
        newRow += '<td><span class="tbl_totalamountspan' + count + '"></span>' + amount + '<input type=hidden  id="tbl_totalamount' + count + '" class="tbl_totalamount" name="tbl_totalamount[]" value="' + amount + '"  /></td>';
        newRow += '<td><a href="javascript:void(0)" class="btn btn-danger rounded-pill tbl-btn text-end removetablerow"  ><i class="fas fa-trash "></i></a></td>';
        newRow += "</tr>";

        $("#addtable").append(newRow);
        autofill();
    }

    function autofill() {
        var total = 0;
        var tqty = 0;
        $('#addtable tr').each(function() {
            var count = $(this).attr('data-index');
            var amt = parseFloat($('#tbl_totalamount' + count).val()) || 0;
            var qty = parseFloat($('#tbl_qty' + count).val()) || 0;
            total += amt;
            tqty += qty;
        });
        // console.log(total);
        $('#tamount').val(total);
        $('#tqty').val(tqty);
        var famt = total;
        var finalamt = famt.toFixed(0);
        var roundamt = finalamt - famt;
        $('#famount').val(finalamt);
        $('#ramount').val(roundamt.toFixed(2));
    }

    $("body").on("click", '.removetablerow', function() {
        $(this).parent().parent().remove();
        autofill()
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
                    var op_product = '<option value="%">Select Category</option>';
                    var row = json.data;
                    for (var j = 0; j < row.length; j++) {
                        op_product += '<option value="' + row[j].catid + '">' + row[j].category + '</option>'
                    }
                    $('#categoryid').html(op_product);
                    // product();
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
                        op_product += '<option value="' + row[j].pid + '" >' + row[j].product + '</option>'
                    }
                    $('#productid').html(op_product);
                } else {
                    $('#productid').html('');
                }
            }
        });
    }

    $("body").on("input", '#qty', function () {
        var check= parseFloat($('#qty').val());
        if(check > 1000){
            $('#qty').val(0);
            showError('Please Write Less Than 1000');
        }
    });
    
if($('#purchase_master').length){
    $('#purchase_master').validate({
        rules: {
            billdate: {
                required: true
            },
            billno: {
                required: true
            },
            productid: {
                required: true
            },
            famount: {
                required: true
            },
            tqty: {
                required: true
            },
            venderid:{
                required: true,			
            },
            pmdate:{
                required: true,			
            }
        },
        messages: {
            billdate: {
                required: "Bill date is required"
            },
            billno: {
                required: "Bill number is required"
            },
            productid: {
                required: "Product is required"
            },
            famount: {
                required: "Final Amount is required"
            },
            tqty: {
                required: "Total Qty is required"
            },
            venderid:{
                required:"Vender is required",		
            },
            pmdate:{
                required:"Date is required",		
            }
        },
        submitHandler: function(form) {
            var values = $('#purchase_master').serialize();
            var purchase_table = [];
            $('#addtable tr').each(function() {
                var sub_item = {
                    tbl_categoryid: $(this).find('.tbl_categoryid').val(),
                    tbl_category: $(this).find('.tbl_category').val(),
                    tbl_productid: $(this).find('.tbl_productid').val(),
                    tbl_product: $(this).find('.tbl_product').val(),
                    tbl_qty: $(this).find('.tbl_qty').val(),
                    tbl_rate: $(this).find('.tbl_rate').val(),
                    tbl_totalamount: $(this).find('.tbl_totalamount').val()
                };
                purchase_table.push(sub_item);
            });
            if(purchase_table.length>0){
                $.ajax({
                    type: "POST",
                    url: "getdata.php",
                    data: {
                        action: "purchase_master",
                        data: values,
                        purchase_table: purchase_table
                    },
                    success: function(data) {
                        var json = JSON.parse(data);
                        if(json.status === 1 ){
                            $("#purchase_master")[0].reset();
                            $('#addtable').html('');
                            showMessage(json.message);
                        }else{
                            showError(json.message);
                        }
                    }
                });
            }else{
                showError("Please Add at list one Product");
            }
        }
    }); 
}
</script>
