<?php
ob_start();

include_once 'connectdb.php';
session_start();

if ($_SESSION['role'] == $warehouse or $_SESSION['username'] == "") {
  header('location:logout.php');
}
if ($_SESSION['role'] == $admin or $amploye) {
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
          <h1 class="m-0">Point Of Sale</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
            <li class="breadcrumb-item active">POS</li>
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
            <div class="card-header">
              <h5 class="m-0">POS</h5>
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


                      <select class="form-control select2" name="select2" data-dropdown-css-class="select2-purple"
                        style="width: 100%;">
                        <option>Select or Search</option>
                        <?php echo fill_product($conn); ?>
                      </select>

                      <div class="tableFixHead mt-3" style="width: 100%;">
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
                          <tbody id="addtable">
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">

                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Sub Total(Rs)</span>
                      </div>
                      <input type="text" class="form-control" id="txtsubtotal_id" name="txtsubtotal_id" readonly>
                      <div class="input-group-append">
                        <span class="input-group-text">Rs</span>
                      </div>
                    </div>

                    <hr style="height:2px; border-width:0; color:black; background-color:black;">

                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Total(Rs)</span>
                      </div>
                      <input type="text" class="form-control form-control-lg total" id="txttotal" name="txttxttotal"
                        readonly>
                      <div class="input-group-append">
                        <span class="input-group-text">RS</span>
                      </div>
                    </div>

                    <hr style="height:2px; border-width:0; color:black; background-color:black;">

                    <div class="icheck-success d-inline">
                      <input type="radio" name="r3" checked id="radioSuccess1" value="cash">
                      <label for="radioSuccess1">
                        Cash
                      </label>
                    </div>
                    <div class="icheck-primary d-inline">
                      <input type="radio" name="r3" id="radioSuccess2" value="card">
                      <label for="radioSuccess2">
                        Card
                      </label>
                    </div>
                    <div class="icheck-danger d-inline">
                      <input type="radio" name="r3" id="radioSuccess3" value="net banking">
                      <label for="radioSuccess3">
                        Net Banking
                      </label>
                    </div>

                    <hr style="height:2px; border-width:0; color:black; background-color:black;">


                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Round Amt(Rs)</span>
                      </div>
                      <input type="text" class="form-control" id="ramount" name="ramount" readonly>
                      <div class="input-group-append">
                        <span class="input-group-text">RS</span>
                      </div>
                    </div>

                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Paid(Rs)</span>
                      </div>
                      <input type="text" class="form-control" id="txtpaid" name="txtpaid" readonly>
                      <div class="input-group-append">
                        <span class="input-group-text">RS</span>
                      </div>
                    </div>

                    <hr style="height:2px; border-width:0; color:black; background-color:black;">

                    <div class="card-footer">
                      <button class="btn btn-primary offset-sm-3 save" type="submit" id="save" name="save">Submit</button>
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

  var productarr = [];

  $(function () {
    $('#txtbarcode_id').on('change', function (e) {
        e.preventDefault();
      var barcode = $("#txtbarcode_id").val();

      $.ajax({
        url: "getproduct.php",
        method: "get",
        dataType: "json",
        data: {
          id: barcode,
          action: 'get_product'
        },
        success: function (data) {
          var myJSON = JSON.stringify(data);
          var json = JSON.parse(myJSON);
          var row = json.data;
          if(json.status === 1){
            for (var j = 0; j < row.length; j++) {
              if (row[j].current_stock > 0) {
                var count = $('.tbl_totalamount').length;
                var newRow = "";
                newRow += '<tr style="align-item:center;" data-index="' + count + '">';
                newRow += '<td><span class="tbl_productspan' + count + '">' + row[j].product + '</span><input type=hidden id="tbl_productid' + count + '" class="tbl_productid form-control" name="tbl_productid[]" value="' + row[j].pid + '" ><input type=hidden id="tbl_product' + count + '" class="tbl_product form-control" name="tbl_product[]" value="' + row[j].product + '" ></td>';
                newRow += '<td><span class="tbl_stockspan' + count + '">' + row[j].current_stock + '</span><input type=hidden id="tbl_stock' + count + '" class="tbl_stock form-control" name="tbl_stock[]" value="' + row[j].current_stock + '" ></td>';
                newRow += '<td><span class="tbl_salepricespan' + count + '">' + row[j].saleprice + '</span><input type=hidden id="tbl_saleprice' + count + '" class="tbl_saleprice form-control" name="tbl_saleprice[]" style="width: 90px;" value="' + row[j].saleprice + '" ></td>';
                newRow += '<td><input type=text id="tbl_qty' + count + '" class="tbl_qty form-control" name="tbl_qty[]" style="width: 150px;" value="'+1+'"  ><input type=hidden id="tbl_barcode' + count + '" class="tbl_barcode form-control" name="tbl_barcode[]" value="' + row[j].barcode + '" ></td>';
                newRow += '<td><input type=text  id="tbl_totalamount' + count + '" class="tbl_totalamount" name="tbl_totalamount[]" style="width: 70px;"   readonly /></td>';
                newRow += '<td><a href="javascript:void(0)" class="btn btn-danger rounded-pill tbl-btn text-end removetablerow"  ><i class="fas fa-trash "></i></a></td>';
                newRow += "</tr>";
              } else {
                showError('Product Stock Not Available !');
              }
            }

            $('#addtable').append(newRow);
            $("#txtbarcode_id").val('');
            tbl_qty();
            showMessage(json.message);
          }else{
            showError(json.message);
          }
         
        } // end success
      }); // enf ajax re
    })
  }) // end onchange

  var productarr = [];
  $(function () {
    $('.select2').on('change', function (e) {
      e.preventDefault();
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
          if(json.status === 1){
            for (var j = 0; j < row.length; j++) {
              if (row[j].current_stock > 0) {

                var count = $('.tbl_totalamount').length;
                var newRow = "";
                newRow += '<tr style="align-item:center;" data-index="' + count + '">';
                newRow += '<td><span class="tbl_productspan' + count + '">' + row[j].product + '</span><input type=hidden id="tbl_productid' + count + '" class="tbl_productid form-control" name="tbl_productid[]" value="' + row[j].pid + '" ><input type=hidden id="tbl_product' + count + '" class="tbl_product form-control" name="tbl_product[]" value="' + row[j].product + '" ></td>';
                newRow += '<td><span class="tbl_stockspan' + count + '">' + row[j].current_stock + '</span><input type=hidden id="tbl_stock' + count + '" class="tbl_stock form-control" name="tbl_stock[]" value="' + row[j].current_stock + '" ></td>';
                newRow += '<td><span class="tbl_salepricespan' + count + '">' + row[j].saleprice + '</span><input type=hidden id="tbl_saleprice' + count + '" class="tbl_saleprice form-control" name="tbl_saleprice[]" style="width: 90px;" value="' + row[j].saleprice + '" ></td>';
                newRow += '<td><input type=text id="tbl_qty' + count + '" class="tbl_qty form-control" name="tbl_qty[]" style="width: 150px;" value="'+1+'" ><input type=hidden id="tbl_barcode' + count + '" class="tbl_barcode form-control" name="tbl_barcode[]" value="' + row[j].barcode + '" ></td>';
                newRow += '<td><input type=text  id="tbl_totalamount' + count + '" class="tbl_totalamount" name="tbl_totalamount[]" style="width: 70px;"   readonly /></td>';
                newRow += '<td><a href="javascript:void(0)" class="btn btn-danger rounded-pill tbl-btn text-end removetablerow"  ><i class="fas fa-trash "></i></a></td>';
                newRow += "</tr>";
              } else {
                showError('Product Stock Not Available');
              }
            }
            $('#addtable').append(newRow);
            tbl_qty();
            showMessage(json.message);
          }else{
            showError(json.message);
          }
        } // end success
      });
    }) // end ajax re.
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
    tbl_qty();
  });

  $("body").on("click", '.save', function () {
   var check= $('#txtpaid').val();
   if(check === '' || check === 0){
    showError('Somthing Went Wrong');
   }
  });

  function tbl_qty(){
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
        showError("SORRY! Quantity Is Not Available");
        $('#tbl_qty' + count).val('');
      }
      var tbl_totaltax = parseFloat(total);
      $('#tbl_totalamount' + count).val(tbl_totaltax);
    });

    autofill();
  }

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
      var purchase_table = [];
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
        purchase_table.push(sub_item);
      });

      $.ajax({
        type: "POST",
        url: "getdata.php",
        dataType: 'json',
        data: {
          action: "pos_master",
          data: values,
          purchase_table: purchase_table
        },
        success: function (data) {
          var myJSON = JSON.stringify(data);
          var json = JSON.parse(myJSON);
          if (json.status === 1) {
            window.location.href = 'orderlist.php';
          }else{
            showError(json.message);
          }
        }
      });
    }
  });

</script>