<?php 
include_once 'connectdb.php';
session_start();

if ( $_SESSION['role'] == $amploye AND  $amploye or  $_SESSION['username'] == "") {
    header('location:logout.php');
}

if ($_SESSION['role'] == $warehouse) {
    include_once 'header.php';
}
$id = $_GET["id"];

$select = "SELECT * FROM tbl_vendermaster p WHERE p.id=$id";
$row = execute($select);
if (sizeof($row) > 0) {

  $outputjson['id'] = $row[0]['id'];
  $outputjson['name'] = $row[0]['name'];
  $outputjson['contect'] = $row[0]['contect'];
  $outputjson['email'] = $row[0]['email'];
  $outputjson['address'] = $row[0]['address'];

  $subpsid = $row[0]['id'];
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
                    <h1 class="m-0">Vender Master</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="warehouse.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="vender_list.php">Vender List</a></li>
            <li class="breadcrumb-item active">Vender Update</li>
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
                        <form id="vender_master" name="vender_master" method="post">
                            <div class="card-header">
                                <h5 class="m-0">Vender Master Form</h5>
                            </div>
                            <div class="panel mt-3 mx-3 ">
                                <div class="row">
                                    <input type="hidden" id="id" name="id" value=" <?php echo $subpsid ?>" />
                                    <div class="mb-3 col-sm-3">
                                        <label class="form-label" for="name"> Vender Name<span class="text-danger">*</span> </label>
                                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter name" value="<?php echo  $outputjson['name'] ?>" required />
                                    </div>
                                    <div class="mb-3 col-sm-3">
                                        <label class="form-label" for="Contact">Contact <span class="text-danger">*</span> </label>
                                        <input type="text" id="contact" name="contact" class="form-control" placeholder="Enter Contact" value="<?php echo  $outputjson['contect'] ?>" onkeyup="numonly('contact')" required />
                                    </div>
                                    <div class="mb-3 col-sm-3">
                                        <label class="form-label" for="email">Email <span class="text-danger">*</span> </label>
                                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter Email" value="<?php echo  $outputjson['email'] ?>" required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-sm-3">
                                        <label class="form-label" for="address">Address <span class="text-danger">*</span> </label>
                                        <input type="text" id="address" name="address" class="form-control" placeholder="Enter Address" value="<?php echo  $outputjson['address'] ?>" required />
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3 my-5">
                                <div class="col-sm-12 offset-8 text-end">
                                    <button class="btn btn-primary offset-sm-3" type="submit" id="save" name="save">Update</button>
                                </div>
                            </div>
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
}
include_once "footer.php";
?>
<script src="assets/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="assets/plugins/jquery/pnotify.custom.js"></script>
<script>
     $('#vender_master').validate({
        rules: {
            name: {
                required: true
            },
            contact: {
                required: true
            },
            email: {
                required: true
            },
            email: {
                required: true
            }
        },
        messages: {
            name: {
                required: "Vender Name is required"
            },
            contact: {
                required: "Contact is required"
            },
            email: {
                required: "Email is required"
            },
            address: {
                required: "Address is required"
            }
        },
        submitHandler: function(form) {
            var values = $('#vender_master').serialize();
            $.ajax({
                type: "POST",
                url: "getdata.php",
                data: {
                    action: "vender_update",
                    data: values
                },
                success: function(data) {
                    var json = JSON.parse(data);
                    if (json.status === 1) {
                    window.location.href ='vender_list.php';
                    }else{
                        showMessage(json.message);
                    }
                }
            });
        }
    });
</script>