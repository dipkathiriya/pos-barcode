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


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">

        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="warehouse.php">Home</a></li>
            <li class="breadcrumb-item active">Purchase List</li>
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
              <h5 class="m-0">Purchase List :</h5>
            </div>
            <div class="card-body">


              <table class="table table-striped table-hover" id="table_product">
                <thead>
                  <tr>
                    <td>Vender Name</td>
                    <td>Bill Date</td>
                    <td>Bill No</td>
                    <td>Purchase Date</td>
                    <td>Entry By</td>
                    <td>Entry Date</td>
                    <td>Action</td>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $select = $conn->prepare("SELECT ps.*,v.name FROM tbl_purchase_master ps 
                    INNER JOIN tbl_vendermaster v ON v.id=ps.venderid
                     ORDER BY ps.id ASC");
                    $select->execute();
                  while ($row = $select->fetch(PDO::FETCH_ASSOC)) {

                    echo '
                          <tr>
                          <td>' . $row['name'] . '</td>
                          <td>' . $row['billdate'] . '</td>
                          <td>' . $row['bill_no'] . '</td>
                          <td>' . $row['date'] . '</td>
                          <td>' . $row['entry_by'] . '</td>
                          <td>' . $row['entry_date'] . '</td>
                          <td>
                          <div class="btn-group">
                            <button class="btn btn-warning btn-xs tbl-btn" onclick="view_PurchaseList(' . $row['id']. ')"><span class="fa fa-layer-group" style="color:#ffffff" data-toggle="tooltip" title="Sub Purchase Master List"</span></button>
                            <a href="edite_purchase_master.php?id=' . $row['id'] . '" class="btn btn-success btn-xs" role="button"><span class="fa fa-edit" style="color:#ffffff" data-toggle="tooltip" title="Edit Product"</span></a>
                            <button id=' . $row['id'] . ' class="btn btn-danger btn-xs btndelete" value="'. $row['id'] .'"><span class="fa fa-trash" style="color:#ffffff" data-toggle="tooltip" title="Delete Product"></span></button>
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
        $('#table_product').DataTable();
    });
</script>

<script>
    $(document).ready(function() {
        $('[data-togle="tooltip"]').tooltip();
    });
</script>

<script>
    $(document).ready(function() {
    $('.btndelete').click(function(){
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
              url : 'productdelete.php',
              type:"post",
              dataType: 'json',
              data: {
                id : id,
                action:"purchase_master"
              },
              success: function(data){
                tdh.parents('tr').hide();
              }
            });
          Swal.fire({
            title: "Deleted!",
            text: "Your Purchase Iteam has been deleted.",
            icon: "success"
          });
        }
      });  
    });
  });

  function view_PurchaseList(id){
    var id= id;
    $.ajax({
      url: 'fill_selection.php',
      method: 'POST',
      data: {
        id: id,
        action: 'purchase_list'
      },
      success: function(data) {
        var json = JSON.parse(data);
        var row = json.data;
        // console.log(row);
        var current_index = $("#rate_list .index-rows").length;
        var html = '';
        for (var j = 0; j < row.length; j++) {
         
          html += '<table class="table table-striped">';
          html += '<thead>';
          html += '<th>Category</th>';
          html += '<th>Product</th>';
          html += '<th>Qty</th>';
          html += '<th>Rate</th>';
          html += '<th>Total Amount</th>';
          html += '</thead>';
          html += '<tbody>';
          html += `<tr class="index-rows" id="row${current_index}">
                      <td>${row[j].category}</td>
                      <td>${row[j].product }</td>
                      <td>${row[j].qty}</td>
                      <td>${row[j].rate}</td>
                      <td>${row[j].totalamount}</td>
                  </tr>`;
          html += '</tbody></table>';
        }
        $("#comman_ListModal #comman_list_model_div").html(html);
      }
    });
    $("#comman_ListModal").modal('show');
    $("#comman_ListModal .comman_list_model_header").html('Purchase Master Details');
  }
</script>