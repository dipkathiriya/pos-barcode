<?php

include_once 'connectdb.php';
session_start();
if ($_SESSION['role'] == $amploye or $_SESSION['role'] == $warehouse or $_SESSION['username'] == "") {
  header('location:logout.php');
}
if($_SESSION['role'] == $admin){
  include_once "header.php";
}

?>

<!-- daterange picker -->
<link rel="stylesheet" href="./assets/plugins/daterangepicker/daterangepicker.css">

<!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="./assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Table Report</h1>
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


          <div class="card card-warning card-outline">
            <div class="card-header">
              <!-- <h5 class="m-0">Featured</h5> -->
            </div>


            <form action="" method="post" name="">
              <div class="card-body">

                <div class="row">


                  <div class="col-md-5">
                    <div class="form-group">
                      <!-- <label>Date:</label> -->
                      <div class="input-group date" id="date_1" data-target-input="nearest">
                        <input type="text" class="form-control date_1" data-target="#date_1" name="date_1" />
                        <div class="input-group-append" data-target="#date_1" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-5">
                    <div class="form-group">
                      <!-- <label>Date:</label> -->
                      <div class="input-group date" id="date_2" data-target-input="nearest">
                        <input type="text" class="form-control date_2" data-target="#date_2" name="date_2" />
                        <div class="input-group-append" data-target="#date_2" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-2">

                    <div class="text-center">
                      <button type="submit" class="btn btn-warning" name="btnfilter">Filter Records</button>
                    </div>

                  </div>

                </div>

              </div>

              <div class="card-body">
                <table class="table table-striped table-hover " id="table_report">
                  <thead>
                    <tr>
                      <td>Invoice ID</td>
                      <td>Order Date</td>
                      <td>Sub Total</td>
                      <td>Total</td>
                      <td>Payment Type</td>
                      <td>Remaining Amount</td>
                      <td>Paid</td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $select = $conn->prepare("select * from tbl_invoice where order_date between :fromdate AND :todate");
                    $select->bindParam(':fromdate', $_POST['date_1']);
                    $select->bindParam(':todate', $_POST['date_2']);
                    $select->execute();
                    while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                      echo '
                  <tr>

                  <td>' . $row->invoice_id   . '</td>
                  <td>' . $row->order_date   . '</td>
                  <td>' . $row->subtotal     . '</td>
                  <td>' . $row->total        . '</td>';
                      if ($row->payment_type == "Cash") {
                        echo '<td><span class="badge badge-warning">' . $row->payment_type . '</td></span></td>';
                      } else if ($row->payment_type == "Card") {
                        echo '<td><span class="badge badge-success">' . $row->payment_type . '</td></span></td>';
                      } else {
                        echo '<td><span class="badge badge-danger">' . $row->payment_type . '</td></span></td>';
                      }
                      echo ' <td>' . $row->ramount          . '</td>
                      <td>' . $row->paid         . '</td>';
                    }
                    ?>
                  </tbody>
                </table>

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

<!-- InputMask -->
<script src="./assets/plugins/moment/moment.min.js"></script>
<!-- date-range-picker -->
<script src="./assets/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="./assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<script>
  //Date picker
  $('#date_1').datetimepicker({
    format: 'YYYY-MM-DD'
  });
  $('#date_2').datetimepicker({
    format: 'YYYY-MM-DD'

  });
</script>

<script>
  $(document).ready(function() {
    $('#table_report').DataTable({

      "order": [
        [0, "desc"]
      ]
    });
  });
</script>