<?php

ob_start();

include_once 'connectdb.php';
session_start();

if ($_SESSION['role'] == $warehouse or $_SESSION['username'] == "") {
  header('location:logout.php');
}
if($_SESSION['role']== $amploye or $admin){
  include_once "header.php";
}

ob_end_flush();

function fill_product($conn)
{
  $output = '';
  $select = $conn->prepare("SELECT * FROM tbl_product ORDER BY product ASC");
  $select->execute();

  $result = $select->fetchAll();

  foreach ($result as $row) {
    $output .= '<option value="' . $row["pid"] . '">' . $row["product"] . '</option>';
  }

  return $output;
}

$id = $_GET["id"];

$select = "SELECT * FROM tbl_invoice p WHERE p.invoice_id=$id";
$row = execute($select);
if (sizeof($row) > 0) {

  $outputjson['id'] = $row[0]['invoice_id'];
  $outputjson['subtotal'] = $row[0]['subtotal'];
  $outputjson['total'] = $row[0]['total'];
  $outputjson['payment_type'] = $row[0]['payment_type'];
  $outputjson['ramount'] = $row[0]['ramount'];
  $outputjson['paid'] = $row[0]['paid'];

  $subpsid = $row[0]['invoice_id'];
// print_r($row[0]['invoice_id']);
  $query = "SELECT ps.* 
  ,ifnull((select sum(op.stock) from store_stock op where op.productid=ps.product_id),0) as current_stock
  FROM tbl_invoice_details ps WHERE ps.invoice_id=$subpsid";
  $result = execute($query);
  // $row=$query->fetch(PDO::FETCH_ASSOC);
  // print_r($result);
  $outputjson['subdatalen'] = $result;
  $j = 1;
  for ($i = 0; $i < sizeof($result); $i++) {
    $subd = $result[$i];
    $outputjson['subdata'][$i]['srno'] = $j;
    $outputjson['subdata'][$i]['barcode'] = $subd['barcode'];
    $outputjson['subdata'][$i]['current_stock'] = $subd['current_stock'];
    $outputjson['subdata'][$i]['product_id'] = $subd['product_id'];
    $outputjson['subdata'][$i]['product_name'] = $subd['product_name'];
    $outputjson['subdata'][$i]['qty'] = $subd['qty'];
    $outputjson['subdata'][$i]['rate'] = $subd['rate'];
    $outputjson['subdata'][$i]['total_amount'] = $subd['saleprice'];
    $j++;
  }

  ?>

  <style>
    .tableFixHead {
      overflow: scroll;
      height: 520px;
    }

    .tableFixHead thead th {
      position: sticky;
      top: 0;
      z-index: 1;
    }

    table {
      border-collapse: collapse;
      width: 100px;
    }

    th,
    td {
      padding: 8px 16px;
    }

    th {
      background: #eee;
    }
  </style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Edit Order Pos</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="orderlist.php">Order List</a></li>
            <li class="breadcrumb-item active">Edit Order List</li>
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


            <div class="card card-info card-outline">
              <div class="card-header">
                <h5 class="m-0">Edit Order</h5>
              </div>
              <div class="card-body">
                <form id="pos_master" name="pos_master" method="post">
                  <div class="row">
                    <div class="col-md-8">
                      <div class="input-group mb-3">

                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fa fa-barcode"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Scan barcode" id="txtbarcode_id">
                        <input type="hidden" id="tbl_id" class="tbl_id" name="tbl_id" value="<?php echo $subpsid ?>">
                        <select class="form-control select2" name="select2" data-dropdown-css-class="select2-purple"
                          style="width: 100%;">
                          <option>Select or Search</option>
                          <?php echo fill_product($conn); ?>
                        </select>
                        <div class="tableFixHead" style="width: 100%;">
                          <table id="producttable" class="table table-bordered table-hover">
                            <thead>
                              <tr>
                                <th>Product</th>
                                <th>Stock </th>
                                <th>price </th>
                                <th>QTY </th>
                                <th>Total </th>
                                <th>Del </th>
                              </tr>
                            </thead>
                            <?php if (isset($outputjson['subdata'])): ?>
                              <tbody id="addtable" onload="autofill();">
                                <?php foreach ($outputjson['subdata'] as $subdata) 
                                {
                                  ?>
                                  <tr data-index=<?php echo $subdata['srno'] ?>>
                                      <td><span class="tbl_productspan<?php echo $subdata['srno'] ?>"><?php echo $subdata['product_name']; ?></span><input type=hidden id="tbl_productid<?php echo $subdata['srno'] ?>" class="tbl_productid form-control" name="tbl_productid[]" value="<?php echo $subdata['product_id']; ?>" ><input type=hidden id="tbl_product<?php echo $subdata['srno'] ?>" class="tbl_product form-control" name="tbl_product[]" value="<?php echo $subdata['product_name']; ?>" ></td>
                                      <td><span class="tbl_stockspan<?php echo $subdata['srno'] ?>"><?php echo $subdata['current_stock']; ?></span><input type=hidden id="tbl_stock<?php echo $subdata['srno'] ?>" class="tbl_stock form-control" name="tbl_stock[]" value="<?php echo $subdata['current_stock']; ?>" ></td>
                                      <td><span class="tbl_salepricespan<?php echo $subdata['srno'] ?>"><?php echo $subdata['rate']; ?></span><input type=hidden id="tbl_saleprice<?php echo $subdata['srno'] ?>" class="tbl_saleprice form-control" name="tbl_saleprice[]" style="width: 90px;" value="<?php echo $subdata['rate']; ?>" ></td>
                                      <td><input type=text id="tbl_qty<?php echo $subdata['srno'] ?>" class="tbl_qty form-control" name="tbl_qty[]" style="width: 150px;" value="<?php echo $subdata['qty']; ?>" ><input type=hidden id="tbl_barcode<?php echo $subdata['srno'] ?>" class="tbl_barcode form-control" name="tbl_barcode[]" value="<?php echo $subdata['barcode']; ?>" ></td>
                                      <td><input type=text  id="tbl_totalamount<?php echo $subdata['srno'] ?>" class="tbl_totalamount" name="tbl_totalamount[]" style="width: 70px;"  value="<?php echo $subdata['total_amount']; ?>" readonly /></td>
                                      <td><a href="javascript:void(0)" class="btn btn-danger rounded-pill tbl-btn text-end removetablerow"  ><i class="fas fa-trash "></i></a></td>
                                  </tr>
                                  <?php
                              } ?>
                              </tbody>
                              <?php endif; ?>
                            </table>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-4">

                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Sub Total(Rs)</span>
                        </div>
                        <input type="text" class="form-control" id="txtsubtotal_id" name="txtsubtotal_id"  value="<?php echo $outputjson['subtotal'] ?>" readonly>
                        <div class="input-group-append">
                          <span class="input-group-text">Rs</span>
                        </div>
                      </div>

                      <hr style="height:2px; border-width:0; color:black; background-color:black;">

                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Total(Rs)</span>
                        </div>
                        <input type="text" class="form-control form-control-lg total" id="txttotal" name="txttxttotal" value="<?php echo $outputjson['total'] ?>" readonly>
                        <div class="input-group-append">
                          <span class="input-group-text">RS</span>
                        </div>
                      </div>

                      <hr style="height:2px; border-width:0; color:black; background-color:black;">

                      <div class="icheck-success d-inline">
                        <input type="radio" name="r3" checked id="radioSuccess1" value="cash" <?php echo $outputjson['payment_type'] ?>>
                        <label for="radioSuccess1">
                          Cash
                        </label>
                      </div>
                      <div class="icheck-primary d-inline">
                        <input type="radio" name="r3" id="radioSuccess2" value="card" <?php echo $outputjson['payment_type'] ?>>
                        <label for="radioSuccess2">
                          Card
                        </label>
                      </div>
                      <div class="icheck-danger d-inline">
                        <input type="radio" name="r3" id="radioSuccess3" value="net banking" <?php echo $outputjson['payment_type'] ?>>
                        <label for="radioSuccess3">
                          Net Banking
                        </label>
                      </div>

                      <hr style="height:2px; border-width:0; color:black; background-color:black;">


                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Round Amt(Rs)</span>
                        </div>
                        <input type="text" class="form-control" id="ramount" value="<?php echo $outputjson['ramount'] ?>" name="ramount"   readonly>
                        <div class="input-group-append">
                          <span class="input-group-text">RS</span>
                        </div>
                      </div>

                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Paid(Rs)</span>
                        </div>
                        <input type="text" class="form-control" id="txtpaid" name="txtpaid" value="<?php echo $outputjson['paid'] ?>"   readonly>
                        <div class="input-group-append">
                          <span class="input-group-text">RS</span>
                        </div>
                      </div>

                      <hr style="height:2px; border-width:0; color:black; background-color:black;">

                      <div class="card-footer">
                        <button class="btn btn-info offset-sm-3 save" type="submit" id="save" name="btnupdateorder">Update
                          Order</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
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

<?php } ?>
<?php

include_once "footer.php";
?>
<script src="assets/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="assets/plugins/jquery/pnotify.custom.js"></script>
<script>
  //Initialize Select2 Elements
  $('.select2').select2();

  //Initialize Select2 Elements
  $('.select2bs4').select2({
    theme: 'bootstrap4'
  });

  $(function () {
    $('#txtbarcode_id').on('change', function () {

      var barcode = $("#txtbarcode_id").val();

      $.ajax({
        url: "getproduct.php",
        method: "get",
        dataType: "json",
        data: {
          id: productid,
          action: 'get_product'
        },
        success: function (data) {
          var myJSON = JSON.stringify(data);
          var json = JSON.parse(myJSON);
          var row = json.data;
          for (var j = 0; j < row.length; j++) {
            // console.log(row[j].product);
            var count = $('.tbl_totalamount').length;
            var newRow = "";
            newRow += '<tr style="align-item:center;" data-index="' + count + '">';
            newRow += '<td><span class="tbl_productspan' + count + '">' + row[j].product + '</span><input type=hidden id="tbl_productid' + count + '" class="tbl_productid form-control" name="tbl_productid[]" value="' + row[j].pid + '" ><input type=hidden id="tbl_product' + count + '" class="tbl_product form-control" name="tbl_product[]" value="' + row[j].product + '" ></td>';
            newRow += '<td><span class="tbl_stockspan' + count + '">' + row[j].stock + '</span><input type=hidden id="tbl_stock' + count + '" class="tbl_stock form-control" name="tbl_stock[]" value="' + row[j].stock + '" ></td>';
            newRow += '<td><span class="tbl_salepricespan' + count + '">' + row[j].saleprice + '</span><input type=hidden id="tbl_saleprice' + count + '" class="tbl_saleprice form-control" name="tbl_saleprice[]" style="width: 90px;" value="' + row[j].saleprice + '" ></td>';
            newRow += '<td><input type=text id="tbl_qty' + count + '" class="tbl_qty form-control" name="tbl_qty[]" style="width: 150px;" ><input type=hidden id="tbl_barcode' + count + '" class="tbl_barcode form-control" name="tbl_barcode[]" value="' + row[j].barcode + '" ></td>';
            newRow += '<td><input type=text  id="tbl_totalamount' + count + '" class="tbl_totalamount" name="tbl_totalamount[]" style="width: 70px;"  value="" readonly /></td>';
            newRow += '<td><a href="javascript:void(0)" class="btn btn-danger rounded-pill tbl-btn text-end removetablerow"  ><i class="fas fa-trash "></i></a></td>';
            newRow += "</tr>";

          }
          $('#addtable').append(newRow);
        } // end success
      }); // end ajax re
    })
  }) // end onchange

var productarr = [];
$(function () {
  $('.select2').on('change', function () {
    var productid = $(".select2").val();

    $.ajax({
      url: "getproduct.php",
      method: "get",
      dataType: "json",
      data: {
        id: productid,
        action: 'get_product'
      },
      success: function (data) {
        var myJSON = JSON.stringify(data);
        var json = JSON.parse(myJSON);
        var row = json.data;
        for (var j = 0; j < row.length; j++) {
          // console.log(row[j].product);
          var count = parseFloat($('.tbl_totalamount').length) + 1;
          var newRow = "";
          newRow += '<tr style="align-item:center;" data-index="' + count + '">';
          newRow += '<td><span class="tbl_productspan' + count + '">' + row[j].product + '</span><input type=hidden id="tbl_productid' + count + '" class="tbl_productid form-control" name="tbl_productid[]" value="' + row[j].pid + '" ><input type=hidden id="tbl_product' + count + '" class="tbl_product form-control" name="tbl_product[]" value="' + row[j].product + '" ></td>';
          newRow += '<td><span class="tbl_stockspan' + count + '">' + row[j].stock + '</span><input type=hidden id="tbl_stock' + count + '" class="tbl_stock form-control" name="tbl_stock[]" value="' + row[j].stock + '" ></td>';
          newRow += '<td><span class="tbl_salepricespan' + count + '">' + row[j].saleprice + '</span><input type=hidden id="tbl_saleprice' + count + '" class="tbl_saleprice form-control" name="tbl_saleprice[]" style="width: 90px;" value="' + row[j].saleprice + '" ></td>';
          newRow += '<td><input type=text id="tbl_qty' + count + '" class="tbl_qty form-control" name="tbl_qty[]" style="width: 150px;"  ><input type=hidden id="tbl_barcode' + count + '" class="tbl_barcode form-control" name="tbl_barcode[]" value="' + row[j].barcode + '" ></td>';
          newRow += '<td><input type=text  id="tbl_totalamount' + count + '" class="tbl_totalamount" name="tbl_totalamount[]" style="width: 70px;"  value="" readonly /></td>';
          newRow += '<td><a href="javascript:void(0)" class="btn btn-danger rounded-pill tbl-btn text-end removetablerow"  ><i class="fas fa-trash "></i></a></td>';
          newRow += "</tr>";

        }
        $('#addtable').append(newRow);
        autofill();
      } // end success
    });
  }) // enf ajax re.
}) // end onchange

$("body").on("click", '.removetablerow', function () {
  $(this).parent().parent().remove();
  autofill();
});

  function autofill() {
    var tamount = 0;
    $('#addtable tr').each(function () {
      var count = $(this).attr('data-index');
      var amount = parseFloat($('#tbl_totalamount' + count).val()) || 0;
      tamount += amount;
    });

    $('#txtsubtotal_id').val(tamount);
    $('#txttotal').val(tamount);

    var finalamt = tamount.toFixed(0);
    var roundamt = finalamt - tamount;
    $("#ramount").val(roundamt.toFixed(2));
    $("#txtpaid").val(finalamt);
  }

$("body").on("input", '.tbl_qty', function () {
  var total = 0;
  var tqty = 0;
  var amt = 0;

  $('#addtable tr').each(function () {
    var count = $(this).attr('data-index');
    var saleprice = parseFloat($('#tbl_saleprice' + count).val());
    var stock = parseFloat($('#tbl_stock' + count).val());
    var qty = parseFloat($('#tbl_qty' + count).val());
    if (stock > qty) {
      total = saleprice * qty;
      amt += total;
    } else {
      Swal.fire("WARNING!", "SORRY! Quantity Is Not Available", "warning");
      $('#tbl_qty' + count).val('');
    }
    var tbl_totaltax = parseFloat(total);
    $('#tbl_totalamount' + count).val(tbl_totaltax);
  });

  autofill();
});

$("body").on("click", '.save', function () {
  var check= $('#txtpaid').val();
  if(check == 0){
  showError('Somthing Went Wrong');
  }
});
  $('#pos_master').validate({
    rules: {
      select2: {
        required: true
      }
    },
    messages: {
      select2: {
        required: "Product Selection  is required"
      }
    },
    submitHandler: function (form) {
      var values = $('#pos_master').serialize();
      var pos_table = [];
      $('#addtable tr').each(function () {
        var sub_item = {
          tbl_productid: $(this).find('.tbl_productid').val(),
          tbl_product: $(this).find('.tbl_product').val(),
          tbl_barcode: $(this).find('.tbl_barcode').val(),
          tbl_stock: $(this).find('.tbl_stock').val(),
          tbl_saleprice: $(this).find('.tbl_saleprice').val(),
          tbl_qty: $(this).find('.tbl_qty').val(),
          tbl_totalamount: $(this).find('.tbl_totalamount').val()
        };
        pos_table.push(sub_item);
      });

      $.ajax({
        type: "POST",
        url: "getdata.php",
        dataType: 'json',
        data: {
          action: "edite_pos_list",
          data: values,
          pos_table: pos_table
        },
        success: function (data) {
          var myJSON = JSON.stringify(data);
          var json = JSON.parse(myJSON);
          if (json.status === 1) {
            window.location.href ='orderlist.php';
          }
          
        }
      });
    }
  });
</script>