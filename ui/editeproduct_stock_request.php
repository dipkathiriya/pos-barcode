<?php

include_once 'connectdb.php';
session_start();

if ($_SESSION['role'] == $warehouse  or   $_SESSION['username'] == "") {

    header('location:login.php');
}
include_once 'header.php';
    $id = $_GET['id'];
    $select = "SELECT p.date,p.id FROM tbl_product_request p WHERE p.id=$id";
    $row=execute($select);
    if(sizeof($row)>0)
        {
            $outputjson['id'] = $row[0]['id'];
            $outputjson['date'] = date('Y-m-d',strtotime($row[0]['date']));
            $subpsid=$row[0]['id'];
            // print_r($outputjson['date']);

            $query ="SELECT ps.categoryid,ps.category,ps.productid,ps.product,ps.qty,ps.id FROM tbl_productrequest_detail ps WHERE ps.pid=$subpsid";
            $result=execute($query);
            // $row=$query->fetch(PDO::FETCH_ASSOC);
            // print_r($result);
            $outputjson['subdatalen'] = $result;
            for ($i=0; $i < sizeof($result); $i++) { 
                $subd=$result[$i];
                $outputjson['subdata'][$i]['srno'] = $i+1;
                $outputjson['subdata'][$i]['categoryid'] = $subd['categoryid'];
                $outputjson['subdata'][$i]['category'] = $subd['category'];
                $outputjson['subdata'][$i]['productid'] = $subd['productid'];
                $outputjson['subdata'][$i]['product'] = $subd['product'];
                $outputjson['subdata'][$i]['qty'] = $subd['qty'];
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
                                        <input type="date" id="pmdate" name="pmdate" class="form-control" placeholder="Enter Date" value="<?php echo $outputjson['date'] ?>" />
                                        <input type="hidden" id="tbl_id" class="tbl_id" name="tbl_id" value="<?php echo $subpsid ?>">
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
                                <?php if (isset($outputjson['subdata'])): ?>
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
                                    <?php foreach ($outputjson['subdata'] as $subdata): ?>
                                        <tr data-index=<?php echo $subdata['srno'] ?>>
                                            <td><span class="tbl_categoryspan<?php echo $subdata['srno'] ?>"><?php echo $subdata['category']; ?></span><input type=hidden id="tbl_categoryid <?php echo $subdata['srno'] ?>"  class="tbl_categoryid form-control" name="tbl_categoryid[]" value="<?php echo $subdata['categoryid']; ?>" ><input type=hidden id="tbl_category<?php echo $subdata['srno'] ?>" class="tbl_category form-control" name="tbl_category[]" value="<?php echo $subdata['category']; ?>" ></td>
                                            <td><span class="tbl_productspan<?php echo $subdata['srno'] ?>"><?php echo $subdata['product']; ?></span><input type=hidden id="tbl_productid<?php echo $subdata['srno'] ?>" class="tbl_productid form-control" name="tbl_productid[]" value="<?php echo $subdata['productid']; ?>" ><input type=hidden id="tbl_product<?php echo $subdata['srno'] ?>" class="tbl_product form-control" name="tbl_product[]" value="<?php echo $subdata['product']; ?>" ></td>
                                            <td><span class="tbl_qtyspan<?php echo $subdata['srno'] ?>"><?php echo $subdata['qty']; ?></span><input type=hidden id="tbl_qty<?php echo $subdata['srno'] ?>" class="tbl_qty form-control" name="tbl_qty[]" style="width: 90px;" value="<?php echo $subdata['qty']; ?>" ></td>
                                            <td><a href="javascript:void(0)" class="btn btn-danger rounded-pill tbl-btn text-end removetablerow"  ><i class="fas fa-trash "></i></a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php endif; ?>
                            </div>
                            <div class="row mt-3 my-5">
                                <div class="col-sm-12 offset-8 text-end">
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
<?php    }?>
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
        setpurchase();
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
            var values = $('#product_stock').serialize();
            console.log(values);
            var purchase_table = [];
            $('#addtable tr').each(function() {
                var sub_item = {
                    tbl_id: $(this).find('.tbl_id').val(),
                    tbl_categoryid: $(this).find('.tbl_categoryid').val(),
                    tbl_category: $(this).find('.tbl_category').val(),
                    tbl_productid: $(this).find('.tbl_productid').val(),
                    tbl_product: $(this).find('.tbl_product').val(),
                    tbl_qty: $(this).find('.tbl_qty').val(),
                };
                purchase_table.push(sub_item);
            });
            $.ajax({
                type: "POST",
                url: "getdata.php",
                data: {
                    action: "edit_product_stock",
                    data: values,
                    purchase_table: purchase_table
                },
                success: function(data) {
                var json = JSON.parse(data);
                // console.log(json.status);
                if (json.status === 1) {
                   window.location.href ='productstock_list.php';
                }
                }
            });
        }
    });

</script>
<?php
if (isset($_SESSION['status']) && $_SESSION['status'] != '') {

?>
    <script>
        Swal.fire({
            icon: '<?php echo $_SESSION['status_code']; ?>',
            title: '<?php echo $_SESSION['status']; ?>'
        });
    </script>

<?php
    unset($_SESSION['status']);
}
?>