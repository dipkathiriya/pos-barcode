<?php
include_once 'connectdb.php';
session_start();

if ($_SESSION['role'] ==  $amploye or $_SESSION['role'] == $warehouse or $_SESSION['username'] == "") {
  header('location:logout.php');
}
if ($_SESSION['role'] == $admin) {
  include_once 'header.php';
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
        

        <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">User Profile</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" id="Username" method="post">
                <div class="card-body">
                  <div class="row ">
                    <div class=" mb-3 col-sm-5">
                      <label for="username" class="col-sm-2 col-form-label">User Name</label>
                      <input type="text" class="form-control" id="username" name="username" readonly >
                      <input type="hidden" class="form-control" id="id" name="id">
                    </div>
                  </div>  
                  <div class="row ">
                    <div class=" mb-3 col-sm-5">
                      <label for="email" class="col-sm-2 col-form-label">Email</label>
                      <input type="email" class="form-control" id="email" name="email"  >
                    </div>
                  </div>
                  <div class="row ">
                    <div class=" mb-3 col-sm-5">
                      <label for="password" class="col-sm-2 col-form-label">Password</label>
                      <input type="password" class="form-control" id="password" name="password" readonly >
                    </div>
                  </div>
                </div> 
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-info" name="btnsave">Save</button>
                </div>
                <!-- /.card-footer -->
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

<?php
include_once "footer.php";
?>
<script src="assets/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="assets/plugins/jquery/pnotify.custom.js"></script>
<script>
$(document).ready(function() {
  fill_fields();
});

function fill_fields(){
  var username = $('#username').val();
  $.ajax({
      url: 'fill_selection.php',
      method: 'POST',
      dataType: 'json',
      data: {
          action: 'fill_profile'
      },
      success: function(data) {
          var myJSON = JSON.stringify(data);
          var json = JSON.parse(myJSON);
          if (json.status === 1) {
            var row = json.data;
              $('#id').val(row[0].id);
              $('#username').val(row[0].username);
              $('#email').val(row[0].email);
              $('#password').val(row[0].password);
          } else {
              showMessage(json.message);
          }
      }
  });
}
$('#Username').validate({
        rules: {
            email: {
                required: true
            }
        },
        messages: {
            email: {
                required: "Email Is Required"
            }
        },
        submitHandler: function(form) {
            var values = $('#Username').serialize();
            $.ajax({
                type: "POST",
                url: "getdata.php",
                data: {
                    action: "Username",
                    data: values
                },
                success: function(data) {
                    var json = JSON.parse(data);
                    showMessage(json.message);
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
