<?php

include_once 'connectdb.php';
session_start();

if ($_SESSION['role'] == $warehouse or $_SESSION['username'] == "") {
    header('location:logout.php');
}
if($_SESSION['role']== $admin or $amploye){
    include_once "header.php";
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">OrderList</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
            <li class="breadcrumb-item active">Order List</li>
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
              <h5 class="m-0">Orders</h5>
            </div>
            <div class="card-body">
              <table class="table table-striped table-hover" id="table_orderlist">
                <thead>
                  <tr>
                    <td>Invoice ID</td>
                    <td>Order Date</td>
                    <td>Total</td>
                    <td>Remaining</td>
                    <td>Paid</td>
                    <td>Payment Type</td>
                    <td>ActionIcons</td>

                  </tr>
                </thead>
                <tbody>
                  <?php
                  $select = $conn->prepare("select * from tbl_invoice order by invoice_id ASC");
                  $select->execute();

                  while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                    echo '
                          <tr>
                          <td>' . $row['invoice_id'] . '</td>
                          <td><button class="btn btn-info rounded-pill tbl-btn" onclick="orderlist_list(' . $row['invoice_id'] . ')">' . $row['order_date'] . '</button></td>
                          <td>' . $row['total'] . '</td>
                          <td>' . $row['ramount'] . '</td>
                          <td>' . $row['paid'] . '</td>';
                    if ($row['payment_type'] == "cash") {
                      echo '<td><span class="badge badge-warning">' . $row['payment_type'] . '</span></td>';
                    } else if ($row['payment_type'] == "card") {
                      echo '<td><span class="badge badge-success">' . $row['payment_type'] . '</span></td>';
                    } else {
                      echo '<td><span class="badge badge-danger">' . $row['payment_type'] . '</span></td>';
                    }
                    echo '
                          <td>
                          <div class="btn-group">
                          <a href="printbill.php?id=' . $row['invoice_id'] . '" class="btn btn-primary " role="button"><span class="fa fa-print" style="color:#ffffff" data-toggle="tooltip" title="Print Bill"</span></a>
                          <a href="editorderpos.php?id=' . $row['invoice_id'] . '" class="btn btn-success " role="button"><span class="fa fa-edit" style="color:#ffffff" data-toggle="tooltip" title="Edit Order"</span></a>
                          <button id=' . $row['invoice_id'] . ' class="btn btn-danger  btndelete" value="' . $row['invoice_id'] . '"><span class="fa fa-trash" style="color:#ffffff" data-toggle="tooltip" title="Delete Order"></span></button>
                          </div>
                          </td>
                          </tr>';
                  }
                  ?>
                </tbody>
              </table>
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
<script>
  $(document).ready(function() {
    $('[data-togle="tooltip"]').tooltip();
  });
</script>

<script>
  $(document).ready(function() {
    $('.btndelete').click(function() {
      var tdh = $(this);
      var id = $(this).val();
      // alert(id);
      Swal.fire({
        title: "Do you want to delete?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: 'productdelete.php',
            type: "post",
            dataType: 'json',
            data: {
              id: id,
              action: "orderlist"
            },
            success: function(data) {
              tdh.parents('tr').hide();
            }
          });
          Swal.fire({
            title: "Deleted!",
            text: "Your Order bucket has been deleted.",
            icon: "success"
          });
        }
      });


    });
  });

  
  function orderlist_list(index) {

var id = index;
$.ajax({
  url: 'fill_selection.php',
  method: 'POST',
  data: {
    id: id,
    action: 'orderlist_model'
  },
  success: function(data) {
    var json = JSON.parse(data);
    var html = '';
    var row = json.data;
    // console.log(row);
    for (var j = 0; j < row.length; j++) {
      html += '<div class="row ">';
      html += '   <div class="mb-4 col-sm-2 ">';
      html += '       <label class="form-label" for="ramount"><h5>Product</h5></label></br>';
      html += '       <span>' + row[j].product_name + '</span>';
      html += '   </div>';
      html += '   <div class="mb-4 col-sm-2 ">';
      html += '       <label class="form-label" for="ramount"><h5>QTY</h5></label></br>';
      html += '       <span>' + row[j].qty + '</span>';
      html += '   </div>';
      html += '   <div class="mb-4 col-sm-2 ">';
      html += '       <label class="form-label" for="ramount"><h5>Rate</h5></label></br>';
      html += '       <span>' + row[j].rate + '</span>';
      html += '   </div>';
      html += '   <div class="mb-4 col-sm-2 ">';
      html += '       <label class="form-label" for="ramount"><h5>Total Amount</h5></label></br>';
      html += '       <span>' + row[j].saleprice + '</span>';
      html += '   </div>';
      html += '</div>';
    }
    $("#comman_ListModal #comman_list_model_div").html(html);

  }
});
$("#comman_ListModal").modal('show');
$("#comman_ListModal .comman_list_model_header").html('Order List Details');
}
</script>


<script>
  $(document).ready(function() {
    $('#table_orderlist').DataTable({
      "order": [
        [0, "desc"]
      ] //for latest order sorting on page
    });
  });
</script>