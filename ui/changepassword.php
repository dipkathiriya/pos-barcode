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
                <h3 class="card-title">Change Password</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" id="change_pass" method="post">
                <div class="card-body">
                  <div class="row mb-5">
                    <div class="col-sm-2">
                    <input type="hidden" class="form-control" id="id"  name="id" >

                      <label class="form-label" for="Username">Select Username <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-3">
                        <select class="form-control" id="username" name="username" >
                        </select>
                    </div>
                  </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Old password</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="oldpass" placeholder="Old Password" name="txt_oldpassword" readonly >
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">New password <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="newpass" placeholder="New Password" name="txt_newpassword" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label"> Repeat new password <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="renewpass" placeholder="Repeat New Password" name="txt_rnewpassword" required>
                    </div>
                  </div>
                 
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-info" name="btnupdate">Update Password</button>
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
  Fill_username();
});
function Fill_username() {
    $.ajax({
        url: 'fill_selection.php',
        method: 'POST',
        dataType: 'json',
        data: {
            action: 'fill_username'
        },
        success: function(data) {
            var myJSON = JSON.stringify(data);
            var json = JSON.parse(myJSON);
            if (json.status === 1) {
                var op_product = '<option value="">Select Username</option>';;
                var row = json.data;
                for (var j = 0; j < row.length; j++) {
                    op_product += '<option value="' + row[j].id + '">' + row[j].username + '</option>'
                }
                $('#username').html(op_product);
            } else {
                $('#username').html();
            }
        }
    });
}
$('#username').change(function() {
  fill_fields();
});

function fill_fields(){
  var username = $('#username').val();
  $.ajax({
      url: 'fill_selection.php',
      method: 'POST',
      dataType: 'json',
      data: {
          action: 'fill_fields',
          username: username
      },
      success: function(data) {
          var myJSON = JSON.stringify(data);
          var json = JSON.parse(myJSON);
          if (json.status === 1) {
            var row = json.data;
              $('#oldpass').val(row[0].password);
              $('#id').val(row[0].id);
          } else {
              showMessage(json.message);
          }
      }
  });
}
$('#change_pass').validate({
        rules: {
            txt_newpassword: {
                required: true
            },
            txt_rnewpassword: {
                required: true
            },
            username: {
                required: true
            }
        },
        messages: {
            txt_newpassword: {
                required: "New Password Is Required"
            },
            txt_rnewpassword: {
                required: "Repeat New Password Is Required"
            },
            username: {
                required: "Select Username Is Required"
            }
        },
        submitHandler: function(form) {
            var values = $('#change_pass').serialize();
            $.ajax({
                type: "POST",
                url: "getdata.php",
                data: {
                    action: "Change_pass",
                    data: values
                },
                success: function(data) {
                    var json = JSON.parse(data);
                    $("#change_pass")[0].reset();
                    showMessage(json.message);
                }
            });
        }
    });

</script>