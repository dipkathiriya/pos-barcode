<?php

include_once 'connectdb.php';
session_start();


if ( $_SESSION['role'] == $amploye or $_SESSION['username'] == "") {
    header('location:logout.php');
}
if ($_SESSION['role'] == $admin or $warehouse) {
    include_once 'header.php';
}

    $id = $_GET['id'];
    $select = "SELECT * FROM tbl_purchase_master p WHERE p.id=$id";
    $row=execute($select);
    if(sizeof($row)>0)
        {
            $outputjson['id'] = $row[0]['id'];
            $outputjson['vender'] = $row[0]['venderid'];
            $outputjson['billdate'] = date('Y-m-d',strtotime($row[0]['billdate']));
            $outputjson['bill_no'] = $row[0]['bill_no'];
            $outputjson['date'] = date('Y-m-d',strtotime($row[0]['date']));
            $outputjson['description'] = $row[0]['description'];
            $outputjson['tamount'] = $row[0]['tamount'];
            $outputjson['tqty'] = $row[0]['tqty'];
            $outputjson['ramount'] = $row[0]['ramount'];
            $outputjson['famount'] = $row[0]['famount'];

            $subpsid=$row[0]['id'];
            $venderid=$row[0]['venderid'];
            // print_r($outputjson['description']);

            $query ="SELECT * FROM tbl_subpurchase ps WHERE ps.pid=$subpsid";
            $result=execute($query);
            // $row=$query->fetch(PDO::FETCH_ASSOC);
            // print_r($result);
            $outputjson['subdatalen'] = $result;
            $j=1;
            for ($i=0; $i < sizeof($result); $i++) { 
                $subd=$result[$i];
                $outputjson['subdata'][$i]['srno'] = $j;
                $outputjson['subdata'][$i]['categoryid'] = $subd['categoryid'];
                $outputjson['subdata'][$i]['category'] = $subd['category'];
                $outputjson['subdata'][$i]['productid'] = $subd['productid'];
                $outputjson['subdata'][$i]['product'] = $subd['product'];
                $outputjson['subdata'][$i]['qty'] = $subd['qty'];
                $outputjson['subdata'][$i]['rate'] = $subd['rate'];
                $outputjson['subdata'][$i]['totalamount'] = $subd['totalamount'];
                $j++;
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
                    <h1 class="m-0">Purchase Form</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="purchasemaster_list.php">Purchase List</a></li>
                        <li class="breadcrumb-item active">Edit Purchase List</li>
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
                    <div class="card card-success card-outline">
                    <form id="purchase_master" name="purchase_master" method="post">
                            <div class="card-header">
                                <h5 class="m-0">Purchase Form</h5>
                            </div>
                            <div class="panel mt-3 mx-3 ">
                                <div class="row">
                                    <div class="mb-3 col-sm-3">
                                        <label class="form-label" for="venderid">Vender <span class="text-danger">*</span></label>
                                        <select class="form-control" name="venderid" id="venderid" required>
                                        <option value="" disabled selected>Select Vender</option>
                                            <?php
                                            $select = $conn->prepare("SELECT v.name,v.id FROM tbl_purchase_master pm
                                            INNER JOIN tbl_vendermaster v ON v.id=pm.venderid
                                            ORDER BY pm.venderid DESC");
                                            $select->execute();

                                            while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                                                extract($row);
                                                ?>
                                                <option <?php if ($row['id'] == $venderid) { ?> selected="selected"
                                                    <?php } ?> value=<?php echo $row['id']; ?>><?php echo $row['name']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <div class="invalid-feedback"> Please enter Vender. </div>
                                    </div>
                                    <div class="mb-3 col-sm-3">
                                        <label class="form-label" for="billdate">Bill Date <span class="text-danger">*</span> </label>
                                        <input type="date" id="billdate" name="billdate" class="form-control" placeholder="Enter Date" value="<?php echo $outputjson['billdate'] ?>" required />
                                        <input type="hidden" id="tbl_id" class="tbl_id" name="tbl_id" value="<?php echo $subpsid ?>">
                                        <div class="invalid-feedback"> Please enter Bill Date. </div>
                                    </div>
                                    <div class="mb-3 col-sm-3">
                                        <label class="form-label" for="billno">Bill No <span class="text-danger">*</span></label>
                                        <input type="text" id="billno" name="billno" class="form-control" onkeyup="numonly('billno')" value="<?php echo $outputjson['bill_no'] ?>" required />
                                        <div class="invalid-feedback"> Please Enter Bill No. </div>
                                    </div>
                                    <div class="mb-3 col-sm-2">
                                        <label class="form-label" for="pmdate"> Date</label>
                                        <input type="date" id="pmdate" name="pmdate" class="form-control" value="<?php echo $outputjson['date'] ?>" placeholder="Enter Date" />
                                        <div class="invalid-feedback"> Please enter Date. </div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="mb-3 col-sm-3">
                                        <label class="form-label" for="category">Category</label>
                                        <select class="form-control" id="categoryid" name="categoryid"  >

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
                                        <button class="btn btn-primary offset-sm-5" name="add" id="add" >+</button>
                                    </div>
                                </div>
                                <?php if (isset($outputjson['subdata'])): ?>
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
                                    <tbody id="addtable" onload="autofill()" >
                                    <?php foreach ($outputjson['subdata'] as $subdata)
                                    {
                                        ?>
                                        <tr data-index=<?php echo $subdata['srno'] ?>>
                                            <td><span class="tbl_categoryspan<?php echo $subdata['srno'] ?>"><?php echo $subdata['category']; ?></span><input type=hidden id="tbl_categoryid <?php echo $subdata['srno'] ?>"  class="tbl_categoryid form-control" name="tbl_categoryid[]" value="<?php echo $subdata['categoryid']; ?>" ><input type=hidden id="tbl_category<?php echo $subdata['srno'] ?>" class="tbl_category form-control" name="tbl_category[]" value="<?php echo $subdata['category']; ?>" ></td>
                                            <td><span class="tbl_productspan<?php echo $subdata['srno'] ?>"><?php echo $subdata['product']; ?></span><input type=hidden id="tbl_productid<?php echo $subdata['srno'] ?>" class="tbl_productid form-control" name="tbl_productid[]" value="<?php echo $subdata['productid']; ?>" ><input type=hidden id="tbl_product<?php echo $subdata['srno'] ?>" class="tbl_product form-control" name="tbl_product[]" value="<?php echo $subdata['product']; ?>" ></td>
                                            <td><span class="tbl_qtyspan<?php echo $subdata['srno'] ?>"><?php echo $subdata['qty']; ?></span><input type=hidden id="tbl_qty<?php echo $subdata['srno'] ?>" class="tbl_qty form-control" name="tbl_qty[]" style="width: 90px;" value="<?php echo $subdata['qty']; ?>" ></td>
                                            <td><span class="tbl_ratespan<?php echo $subdata['srno'] ?>"><?php echo $subdata['rate']; ?></span><input type=hidden id="tbl_rate<?php echo $subdata['srno'] ?>" class="tbl_rate form-control" name="tbl_rate[]" style="width: 90px;" value="<?php echo $subdata['rate']; ?>" ></td>
                                            <td><span class="tbl_totalamountspan<?php echo $subdata['srno'] ?>"><?php echo $subdata['totalamount']; ?></span><input type=hidden id="tbl_totalamount<?php echo $subdata['srno'] ?>" class="tbl_totalamount form-control" name="tbl_totalamount[]" style="width: 90px;" value="<?php echo $subdata['totalamount']; ?>" ></td>
                                            <td><a href="javascript:void(0)" class="btn btn-danger rounded-pill tbl-btn text-end removetablerow"  ><i class="fas fa-trash "></i></a></td>
                                        </tr>
                                        <?php
                                    } ?>
                                    </tbody>
                                </table>
                                <?php endif; ?>
                            </div>
                            <div class="row mx-3 mt-4">
                                <div class="mb-3 col-sm-6">
                                    <label class="form-label" for="des">Description </label>
                                    <textarea class="form-control" name="des" id="des" ><?php echo $outputjson['description'] ?></textarea>
                                    <div class="invalid-feedback"> Please enter Description </div>
                                </div>
                                <div class="mb-3 col-sm-6">
                                    <div class="row ">
                                        <div class="mb-3 col-sm-4">
                                            <label class="form-label" for="tamount">Total Amount</label>
                                            <input type="text" class="form-control" id="tamount" name="tamount" value="<?php echo $outputjson['tamount'] ?>" readonly>
                                        </div>
                                        <div class="mb-3 col-sm-4">
                                            <label class="form-label" for="tqty">Total Qty</label>
                                            <input type="text" class="form-control" id="tqty" name="tqty" value="<?php echo $outputjson['tqty'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="mb-3 col-sm-4 ">
                                            <label class="form-label" for="ramount">Round Amt</label>
                                            <input type="text" class="form-control" id="ramount" name="ramount" value="<?php echo $outputjson['ramount'] ?>" readonly>
                                        </div>
                                        <div class="mb-3 col-sm-4 ">
                                            <label class="form-label" for="famount">Final Amount</label>
                                            <input type="text" class="form-control" id="famount" name="famount" value="<?php echo $outputjson['famount'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
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
        fill_update();
    })

    $("#add").click(function(e) {
        e.preventDefault();
        setpurchase();
    });

    async function setpurchase() {
        var count = parseFloat($('.tbl_totalamount').length) + 1;

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
        $("#qty").val("");
        $("#rate").val("");
        autofill();
    }

    $("body").on("click", '.removetablerow', function() {
        $(this).parent().parent().remove();
        autofill()
    });

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
        $('#tamount').val(total);
        $('#tqty').val(tqty);
        var famt = total;
        var finalamt = famt.toFixed(0);
        var roundamt = finalamt - famt;
        $('#famount').val(finalamt);
        $('#ramount').val(roundamt.toFixed(2));
    }

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
            }
        },
        messages: {
            billdate: {
                required: "Bill Date is required"
            },
            billno: {
                required: "Bill No is required"
            },
            productid: {
                required: "Product is required"
            }
        },
        submitHandler: function(form) {
            var values = $('#purchase_master').serialize();
            // console.log(values);
            var purchase_table = [];
            $('#addtable tr').each(function() {
                var sub_item = {
                    tbl_id: $(this).find('.tbl_id').val(),
                    tbl_categoryid: $(this).find('.tbl_categoryid').val(),
                    tbl_category: $(this).find('.tbl_category').val(),
                    tbl_productid: $(this).find('.tbl_productid').val(),
                    tbl_product: $(this).find('.tbl_product').val(),
                    tbl_qty: $(this).find('.tbl_qty').val(),
                    tbl_rate: $(this).find('.tbl_rate').val(),
                    tbl_totalamount: $(this).find('.tbl_totalamount').val(),
                };
                purchase_table.push(sub_item);
            });
            $.ajax({
                type: "POST",
                url: "getdata.php",
                data: {
                    action: "edit_purchase_master",
                    data: values,
                    purchase_table: purchase_table
                },
                success: function(data) {
                var json = JSON.parse(data);
                // console.log(json.status);
                    if (json.status === 1) {
                    window.location.href ='purchasemaster_list.php';
                    }else{
                        showError(json.message);
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