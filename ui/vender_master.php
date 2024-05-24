<?php 
include_once 'connectdb.php';
session_start();

if ( $_SESSION['role'] == $amploye AND  $amploye or  $_SESSION['username'] == "") {
    header('location:logout.php');
}

if ($_SESSION['role'] == $warehouse) {
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
                    <h1 class="m-0">Vender Master</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="warehouse.php">Home</a></li>
            <li class="breadcrumb-item active">Vender Master</li>
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
                                    <div class="mb-3 col-sm-3">
                                        <label class="form-label" for="name"> Vender Name<span class="text-danger">*</span> </label>
                                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter name" required />
                                    </div>
                                    <div class="mb-3 col-sm-3">
                                        <label class="form-label" for="Contact">Contact <span class="text-danger">*</span> </label><span class="ml-3" id="errorcon" style="color: red;"></span>
                                        <input type="text" id="contact" name="contact" class="form-control" placeholder="Enter Contact" onkeyup="numonly('contact')" required />
                                    </div>
                                    <div class="mb-3 col-sm-3">
                                        <label class="form-label" for="email">Email <span class="text-danger">*</span> </label>
                                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter Email" required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-sm-3">
                                        <label class="form-label" for="address">Address <span class="text-danger">*</span> </label>
                                        <input type="text" id="address" name="address" class="form-control" placeholder="Enter Address" required />
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3 my-5">
                                <div class="col-sm-3 offset-9 text-end">
                                    <button class="btn btn-primary offset-sm-3" type="submit" id="save" name="save">Submit</button>
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
include_once "footer.php";
?>
<script src="assets/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="assets/plugins/jquery/pnotify.custom.js"></script>
<script>
    $("body").on("input", '#contact', function () {
        var phoneNumber = $(this).val();
        var pattern = /^\d{10}$/; // Matches a 10-digit phone number
        
        if (pattern.test(phoneNumber)) {
            $('#errorcon').text('');// Clear error message if valid
        } else {
            $('#errorcon').text(' Enter 10-digit phone number');
        }
    });

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
            var CheckContect = $('#contact').val();
            if(CheckContect.length == 10){
                $.ajax({
                    type: "POST",
                    url: "getdata.php",
                    data: {
                        action: "vender_master_add",
                        data: values
                    },
                    success: function(data) {
                        var json = JSON.parse(data);
                        $("#vender_master")[0].reset();
                        showMessage(json.message);
                    }
                }); 
            }else{
                showError("Please Check Contect No.");
            }
        }
    });
</script>