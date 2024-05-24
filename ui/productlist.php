<?php
include_once 'connectdb.php';
session_start();

if ($_SESSION['role'] == $amploye or $_SESSION['role'] == $warehouse or $_SESSION['username'] == "") {
  header('location:logout.php');
}
if($_SESSION['role']== $admin){
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

        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
            <li class="breadcrumb-item active">Product List</li>
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
              <h5 class="m-0">Product List :</h5>
            </div>
            <div class="card-body">


              <table class="table table-striped table-hover" id="table_product">
                <thead>
                  <tr>
                    <td>Product</td>
                    <td>Category</td>
                    <td>Entry By</td>
                    <td>Entry Date</td>
                    <td>Image</td>
                    <td>ActionIcons</td>
                  </tr>
                </thead>
                <tbody>
                  <?php


                    $select = $conn->prepare("SELECT ps.*,c.category 
                    ,ifnull((select sum(s.stock) from store_stock s where s.productid=ps.pid),0) as total_stock
                    FROM tbl_product ps
                    INNER JOIN tbl_category c ON  c.catid=ps.categoryid
                     ORDER BY ps.entry_date desc");
                    $select->execute();
                  while ($row = $select->fetch(PDO::FETCH_ASSOC)) {

                    echo '
                          <tr>
                          <td>' . $row['product'] . '</td>
                          <td>' . $row['category'] . '</td>
                          <td>' . $row['entry_by'] . '</td>
                          <td>' . $row['entry_date'] . '</td>
                          <td><image src="productimages/' . $row['image'] . '" class="img-rounded" width="40px" height="40px/"</td>
                          <td>
                          <div class="btn-group">
                          <a href="printbarcode.php?id=' . $row['pid'] . '" class="btn btn-dark btn-xs" role="button"><span class="fa fa-barcode" style="color:#ffffff" data-toggle="tooltip" title="Print Barcode"</span></a>
                          <a href="viewproduct.php?id=' . $row['pid'] . '" class="btn btn-warning btn-xs" role="button"><span class="fa fa-eye" style="color:#ffffff" data-toggle="tooltip" title="View Product"</span></a>
                          <a href="editproduct.php?id=' . $row['pid'] . '" class="btn btn-success btn-xs" role="button"><span class="fa fa-edit" style="color:#ffffff" data-toggle="tooltip" title="Edit Product"</span></a>
                          <button id=' . $row['pid'] . ' class="btn btn-danger btn-xs btndelete" value="'. $row['pid'] .'"><span class="fa fa-trash" style="color:#ffffff" data-toggle="tooltip" title="Delete Product"></span></button>
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
          action:"productlist"
        },
        success: function(data){
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


</script>