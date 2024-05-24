<?php
include_once "connectdb.php";
session_start();

if (isset($_POST['btn_login'])) {


  $useremail = $_POST['txt_email'];
  $password = $_POST['txt_password'];

  if($useremail AND $password !=''){
    $query = "SELECT * FROM tbl_users WHERE  username='$useremail' AND password='$password'";

    // print_r($row); 
    if ($row=execute($query)) {
      $row=$row[0];
      if ($row['username'] == $useremail and $row['password'] == $password and $row['role'] == $admin) {
        $_SESSION['status'] = "Login Success By Admin";
        $_SESSION['status_code'] = "success";

        header('refresh: 2;dashboard.php');

        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['role'] = $row['role'];
      } else if ($row['username'] == $useremail and $row['password'] == $password and $row['role'] == $amploye) {
        $_SESSION['status'] = "Login Success By User";
        $_SESSION['status_code'] = "success";

        header('refresh: 3;user.php');

        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['role'] = $row['role'];
      }
      else if ($row['username'] == $useremail and $row['password'] == $password and $row['role'] == $warehouse) {
        $_SESSION['status'] = "Login Success By Purchase Manager";
        $_SESSION['status_code'] = "success";

        header('refresh: 2;warehouse.php');

        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['role'] = $row['role'];
      }
    } else {
      $_SESSION['status'] = "Wrong Email or Password";
      $_SESSION['status_code'] = "error";
    }
  }else {
    $_SESSION['status'] = "Fill Both Credentials";
    $_SESSION['status_code'] = "error";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.jpg" sizes="16x16">
  <title>POS | SYSTEM</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css"> -->

  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">

  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">


</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="#" class="h1"><b>POS</b>BARCODE</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <form action="" method="post">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Username" name="txt_email" >
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Password" name="txt_password" >
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <!-- /.col -->
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block" name="btn_login">Login</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
        <!-- /.social-auth-links -->
        <p class="mb-1">
        </p>
        <p class="mb-0">
        </p>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.login-box -->
</body>

</html>
<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="assets/plugins/toastr/toastr.min.js"></script>
<!-- AdminLTE App -->

<script src="assets/dist/js/adminlte.min.js"></script>
<?php
if (isset($_SESSION['status']) && $_SESSION['status'] != '') {

?>
  <script>
    $(function() {
      var Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 5000
      });


      Toast.fire({
        icon: '<?php echo $_SESSION['status_code']; ?>',
        title: '<?php echo $_SESSION['status']; ?>'
      })
    });
  </script>
<?php
  unset($_SESSION['status']);
}
?>