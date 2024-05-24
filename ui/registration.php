<?php
include_once 'connectdb.php';
session_start();

if ($_SESSION['role'] == $amploye or $_SESSION['role'] == $warehouse or $_SESSION['username'] == "") {
    header('location:logout.php');
}
if ($_SESSION['role'] == $admin) {
    include_once "header.php";
}

error_reporting(0);

$id = $_GET['id'];
if (isset($id)) {
    $delete = $conn->prepare("delete from tbl_users where id=" . $id);
    if ($delete->execute()) {
        $_SESSION['status'] = "Account deleted seccessfully";
        $_SESSION['status_code'] = "success";
        // header('Location: registration.php');
    } else {
        $_SESSION['status'] = "Account not deleted ramount to erro";
        $_SESSION['status_code'] = "warning";
    }
}

if (isset($_POST['btnsave'])) {

    $username = $_POST['txtname'];
    $useremail = $_POST['txtemail'];
    $userpassword = $_POST['txtpassword'];
    $userrole = $_POST['txtselect_option'];

    if (!empty($username && $useremail && $userpassword && $userrole)) {
        $select = $conn->prepare("SELECT * FROM tbl_users WHERE (email='$useremail' AND username='$username' )");
        $select->execute();

        if ($select->rowcount() > 0) {
            $_SESSION['status'] = "Email already exists";
            $_SESSION['status_code'] = "warning";
        } else {
            $insert = $conn->prepare("insert into tbl_users (username,email,password,role) values(:name,:email,:password,:role)");

            $insert->bindParam(':name', $username);
            $insert->bindParam(':email', $useremail);
            $insert->bindParam(':password', $userpassword);
            $insert->bindParam(':role', $userrole);

            if ($insert->execute()) {
                $_SESSION['status'] = "Inserted Successfully";
                $_SESSION['status_code'] = "success";
            } else {
                $_SESSION['status'] = "Error Inserting User";
                $_SESSION['status_code'] = "error";
            }
        }
    } else {
        $_SESSION['status'] = "Fill * Fields";
        $_SESSION['status_code'] = "error";
    }
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



            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="m-0">Registration</h5>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-4">

                            <form action="" method="post">

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Name <span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" placeholder="Enter name" name="txtname">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email address <span class="text-danger">*</span> </label>
                                    <input type="email" class="form-control" placeholder="Enter email" name="txtemail">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Password <span class="text-danger">*</span> </label>
                                    <input type="password" class="form-control" placeholder="Password" name="txtpassword">
                                </div>
                                <div class="form-group">
                                    <label>Select <span class="text-danger">*</span> </label>
                                    <select class="form-control" name="txtselect_option">
                                        <option value="" disabled selected>Select Role</option>
                                        <option>Admin</option>
                                        <option>Manager</option>
                                        <option>User</option>
                                    </select>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary" name="btnsave">Save</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <td>ID</td>
                                        <td>Name</td>
                                        <td>Email</td>
                                        <td>Password</td>
                                        <td>Role</td>
                                        <td>Delete</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    $select = $conn->prepare("select * from tbl_users order by id");
                                    $select->execute();
                                    while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                                        echo '
                                            <tr>
                                            <td>' . $row->id . '</td>
                                            <td>' . $row->username . '</td>
                                            <td>' . $row->email . '</td>
                                            <td>' . $row->password . '</td>
                                            <td>' . $row->role . '</td>
                                            <td>
                                            <a href="registration.php?id=' . $row->id . '" class="btn btn-danger""><i class="fa fa-trash-alt"></i></a>
                                            </td>
                                            </tr>
                                            ';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<?php
include_once "footer.php";
?>

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
    unset($_SESSION['status'],$_SESSION['status_code']);
}
?>