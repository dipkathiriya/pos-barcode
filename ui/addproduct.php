<?php

include_once 'connectdb.php';
session_start();
if ( $_SESSION['role'] == $amploye or $_SESSION['role'] == $warehouse or  $_SESSION['username'] == "") {

    header('location:logout.php');
}

if ($_SESSION['role'] == $admin) {
    include_once 'header.php';
}

if (isset($_POST['btnsave'])) {

    $barcode       = $_POST['txtbarcode'];
    $product       = $_POST['txtproductname'];
    $category      = $_POST['categoryid'];
    $description   = $_POST['txtdescription'];
    $stock         = $_POST['txtstock'];
    $saleprice     = $_POST['txtsaleprice'];
    $role=$_SESSION['username'];
    $date= date('Y-m-d');

    //Image Code or File Code Start Here..
    $f_name        = $_FILES['myfile']['name'];
    $f_tmp         = $_FILES['myfile']['tmp_name'];
    $f_size        = $_FILES['myfile']['size'];
    $f_extension   = explode('.', $f_name);
    $f_extension   = strtolower(end($f_extension));
    $f_newfile     = uniqid() . '.' . $f_extension;

    $store = "productimages/" . $f_newfile;
    if(!empty($product && $stock && $saleprice && $category)){
        $query = "SELECT * FROM tbl_product where ( product='$product')";
        $filtered_count =execute($query);
        if(sizeof($filtered_count) > 0)
        {
            $_SESSION['status_code'] ="warning";
            $_SESSION['status']  = "Data Already Exist";
        }else {
            if($f_extension == 'jpg' || $f_extension == 'jpeg' ||   $f_extension == 'png' || $f_extension == 'gif'){

                if ($f_size >= 1000000) {
                    $_SESSION['status'] = "Max file should be 1MB";
                    $_SESSION['status_code'] = "warning";
                } else {
                    if (move_uploaded_file($f_tmp, $store)) {

                        $productimage = $f_newfile;

                        if (empty($barcode)) {
                            $insert = $conn->prepare("insert into tbl_product ( product,categoryid,description,stock,saleprice,image,entry_by,entry_date) 
                            values(:product,:category,:description,:stock,:saleprice,:img,:entry_by,:entry_date)");

                            $insert->bindParam(':product', $product);
                            $insert->bindParam(':category', $category);
                            $insert->bindParam(':description', $description);
                            $insert->bindParam(':stock', $stock);
                            $insert->bindParam(':saleprice', $saleprice);
                            $insert->bindParam(':img', $productimage);
                            $insert->bindParam(':entry_by', $role);
                            $insert->bindParam(':entry_date', $date);

                            $insert->execute();

                            $pid = $conn->lastInsertId();
                            date_default_timezone_set("Asia/Calcutta");
                            $newbarcode = $pid . date('his');

                            $update = $conn->prepare("update tbl_product SET barcode='$newbarcode' where pid='" . $pid . "'");

                            if ($update->execute()) {
                                $_SESSION['status'] = "Product Inserted Successfully";
                                $_SESSION['status_code'] = "success";
                            } else {
                                $_SESSION['status'] = "Product Inserted Failed";
                                $_SESSION['status_code'] = "error";
                            }
                        } else {

                            $insert = $conn->prepare("insert into tbl_product (barcode, product,categoryid,description,stock,saleprice,image,entry_by,entry_date) 
                            values(:barcode,:product,:category,:description,:stock,:saleprice,:img,:entry_by,:entry_date)");

                            $insert->bindParam(':barcode', $barcode);
                            $insert->bindParam(':product', $product);
                            $insert->bindParam(':category', $category);
                            $insert->bindParam(':description', $description);
                            $insert->bindParam(':stock', $stock);
                            $insert->bindParam(':saleprice', $saleprice);
                            $insert->bindParam(':img', $productimage);
                            $insert->bindParam(':entry_by', $role);
                            $insert->bindParam(':entry_date', $date);

                            if ($insert->execute()) {
                                $_SESSION['status'] = "Product Inserted Successfully!";
                                $_SESSION['status_code'] = "success";
                            } else {
                                $_SESSION['status'] = "Product Inserted Failed";
                                $_SESSION['status_code'] = "error";
                            }
                        }
                    }
                }
            }
        }  
    }else{
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
                    <h1 class="m-0">Add Product</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Product</li>
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
                            <h5 class="m-0">Product</h5>
                        </div>


                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label>Barcode</label>
                                            <input type="text" class="form-control" placeholder="Enter Barcode" id="txtbarcode" name="txtbarcode" autocomplete="off" onkeyup="numonly('txtbarcode')">
                                        </div>

                                        <div class="form-group">
                                            <label>Product Name <span class="text-danger">*</span> </label>
                                            <input type="text" class="form-control" placeholder="Enter Name" name="txtproductname" autocomplete="off"  >
                                        </div>

                                        <div class="form-group">
                                            <label>Category <span class="text-danger">*</span> </label>
                                            <select class="form-control" id="categoryid" name="categoryid" >
                                                <!-- <option value="" disabled selected>Select Category</option> -->
                                                <?php
                                                $select = $conn->prepare("SELECT * FROM tbl_category ORDER BY catid DESC");
                                                $select->execute();

                                                while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                                                ?>
                                                    <option value=<?php echo $row['catid'] ?>><?php echo $row['category'] ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea class="form-control" placeholder="Enter Description" name="txtdescription" rows="4" ></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Minimum Stock Quantity <span class="text-danger">*</span> </label>
                                            <input type="number" min="1" step="any" class="form-control" placeholder="Enter Stock" name="txtstock" autocomplete="off" >
                                        </div>
                                        <div class="form-group">
                                            <label>Sale Price <span class="text-danger">*</span> </label>
                                            <input type="number" min="1" step="any" class="form-control" placeholder="Enter Sale Price" name="txtsaleprice" autocomplete="off" >
                                        </div>
                                        <div class="form-group">
                                            <label>Product image <span class="text-danger">*</span> </label>
                                            <input type="file" class="input-group" name="myfile" >
                                            <p>Upload image</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary" name="btnsave">Save Product</button>
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