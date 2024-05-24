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
            <li class="breadcrumb-item active">Vender List</li>
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
              <h5 class="m-0">Vender List :</h5>
            </div>
            <div class="card-body">


              <table class="table table-striped table-hover" id="table_product">
                <thead>
                  <tr>
                    <td>Vender</td>
                    <td>Contect</td>
                    <td>Email</td>
                    <td>Address</td>
                    <td>Action</td>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $select = $conn->prepare("SELECT v.* FROM tbl_vendermaster v ORDER BY v.id ASC");
                    $select->execute();
                  while ($row = $select->fetch(PDO::FETCH_ASSOC)) {

                    echo '
                          <tr>
                          <td>' . $row['name'] . '</td>
                          <td>' . $row['contect'] . '</td>
                          <td>' . $row['email'] . '</td>
                          <td>' . $row['address'] . '</td>
                          <td>
                          <div class="btn-group">
                            <a href="edit_vender_master.php?id=' . $row['id'] . '" class="btn btn-success btn-xs" role="button"><span class="fa fa-edit" style="color:#ffffff" data-toggle="tooltip" title="Edit Vender"</span></a>
                            <button id=' . $row['id'] . ' class="btn btn-danger btn-xs btndelete" value="'. $row['id'] .'"><span class="fa fa-trash" style="color:#ffffff" data-toggle="tooltip" title="Delete Vender"></span></button>
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
                action:"vender_master"
              },
              success: function(data){
                tdh.parents('tr').hide();
              }
            });
          Swal.fire({
            title: "Deleted!",
            text: "vender has been deleted.",
            icon: "success"
          });
        }
      });  
    });
  });

</script>