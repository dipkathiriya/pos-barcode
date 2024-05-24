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
            <div class="card-header">
              <h5 class="m-0">Product Stock Request List</h5>
            </div>
            <div class="card-body">


              <table class="table table-striped table-hover" id="table_product">
                <thead>
                  <tr>
                    <td>Date</td>
                    <td>Entry By</td>
                    <td>ActionIcons</td>
                  </tr>
                </thead>
                <tbody>
                  <?php


                  $select = $conn->prepare("SELECT p.* from tbl_product_request p ");
                  $select->execute();
                  while ($row = $select->fetch(PDO::FETCH_ASSOC)) {

                    echo '
                          <tr>
                            <td><button class="btn btn-info rounded-pill tbl-btn" onclick="product_list(' . $row['id'] . ')">' . $row['date'] . '</button></td>
                            <td>' . $row['role'] . '</td>
                            <td>';
                    if ($row['is_approv'] == 0) {
                      echo  '  <div class="btn-group">
                              <a href="editeproduct_stock_request.php?id=' . $row['id'] . '" class="btn btn-success btn-xs" role="button"><span class="fa fa-edit" style="color:#ffffff" data-toggle="tooltip" title="Edit Product"</span></a>
                              <button id=' . $row['id'] . ' class="btn btn-danger btn-xs btndelete mx-3 " value="' . $row['id'] . '"><span class="fa fa-trash" style="color:#ffffff" data-toggle="tooltip" title="Delete Product"></span></button>
                            </div>';
                    }else if($row['is_approv'] == 1){
                      echo '<span class="fa fa-check mt-2" style="color:black" data-toggle="tooltip" title="Approve Stock Request"><label class="form-label mx-2">Stock Approved By Warehouse</label></span>';
                    }else{
                      echo '<span class="fa fa-times mt-2" style="color:black" data-toggle="tooltip" title="Cancel Stock Request"><label class="form-label mx-2">Stock Cancel By Warehouse</label></span>';
                    }
                    echo ' </td>
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
    $('.btndelete').click(function() {
      var tdh = $(this);
      var id = $(this).val();

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
              action: "productstock_list"
            },
            success: function(data) {
              tdh.parents('tr').hide();
            }
          });
          Swal.fire({
            title: "Deleted!",
            text: "Your Product has been deleted.",
            icon: "success"
          });
        }
      });


    });
  });


  function product_list(index) {

    var id = index;
    $.ajax({
      url: 'fill_selection.php',
      method: 'POST',
      data: {
        id: id,
        action: 'productstock_model'
      },
      success: function(data) {
        var json = JSON.parse(data);
        var html = '';
        var row = json.data;
        // console.log(row);
        var op_html = '';

        op_html+='<div class="table-responsive">';
        op_html+='   <table class="table ">';
        op_html+='       <thead>';
        op_html+='           <tr class="table-primary" >';
        op_html+='              <th scope="row">Category</th>';
        op_html+='              <th scope="row">Product</th>';
        op_html+='              <th scope="row">Qty</th>';
        op_html+='           </tr>';
        op_html+='        </thead>';
        op_html+='        <tbody>';
        for (var j = 0; j < row.length; j++) {
         
          op_html +='<tr class="index-rows">'; 
          op_html += ' <td>'+row[j].category+'</td>';        
          op_html +=' <td>'+row[j].product +'</td>';         
          op_html +=' <td>'+row[j].qty+'</td>';         
          op_html += '</tr>';     
        }
        op_html+='        </tbody>';
        op_html+='   </table>';
        op_html+='</div>';
        $("#comman_ListModal #comman_list_model_div").html(op_html);

      }
    });
    $("#comman_ListModal").modal('show');
    $("#comman_ListModal .comman_list_model_header").html('Product Stock Details');
  }
</script>