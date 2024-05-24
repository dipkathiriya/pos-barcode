<?php
include_once 'connectdb.php';
session_start();
if ($_SESSION['role'] == $amploye or $_SESSION['role'] == $warehouse or $_SESSION['username'] == "") {
    header('location:logout.php');
}
if ($_SESSION['role'] == $admin) {
    include_once "header.php";
}
$id = $_GET['id'];


if (isset($_POST['btneditproduct'])) {

    // $barcode_txt       =$_POST['txtbarcode'];
    $product_txt = $_POST['txtproductname'];
    $category_txt = $_POST['txtselect_option'];
    $description_txt = $_POST['txtdescription'];
    $stock_txt = $_POST['txtstock'];
    $saleprice_txt = $_POST['txtsaleprice'];

    //Image Code or File Code Start Here..
    $f_name = $_FILES['myfile']['name'];
    if($product_txt or $stock_txt or $saleprice_txt != ''){
        if (!empty($f_name)) {

            $f_tmp = $_FILES['myfile']['tmp_name'];
            $f_size = $_FILES['myfile']['size'];
            $f_extension = explode('.', $f_name);
            $f_extension = strtolower(end($f_extension));
            $f_newfile = uniqid() . '.' . $f_extension;

            $store = "productimages/" . $f_newfile;
            
            $query = "SELECT * FROM tbl_product where product='$product_txt'AND pid <> '$id'";
            $filtered_count =execute($query);
            if(sizeof($filtered_count) > 0)
            {
                $outputjson['success'] = 0;
                $outputjson['message'] = "Data Already Exist";
            }else{
                if ($f_extension == 'jpg' || $f_extension == 'jpeg' || $f_extension == 'png' || $f_extension == 'gif') {

                    if ($f_size >= 1000000) {
                        $_SESSION['status'] = "Max file should be 1MB";
                        $_SESSION['status_code'] = "warning";
                    } else {

                        if (move_uploaded_file($f_tmp, $store)) {

                            $f_newfile;

                            $update = $conn->prepare("update tbl_product set product=:product , categoryid=:category , description=:description , stock=:stock , saleprice=:sprice , image=:image where pid=$id");

                            $update->bindParam(':product', $product_txt);
                            $update->bindParam(':category', $category_txt);
                            $update->bindParam(':description', $description_txt);
                            $update->bindParam(':stock', $stock_txt);
                            $update->bindParam(':sprice', $saleprice_txt);
                            $update->bindParam(':image', $f_newfile);

                            if ($update->execute()) {

                                $_SESSION['status'] = "Product Updated Successfully With New Image";
                                $_SESSION['status_code'] = "success";
                            } else {
                                $_SESSION['status'] = "Product Update Failed";
                                $_SESSION['status_code'] = "error";
                            }
                        }
                    }
                }
            }
        } else {

            $update = $conn->prepare("update tbl_product set product=:product , categoryid=:category , description=:description , stock=:stock , saleprice=:sprice , image=:image where pid=$id");

            $update->bindParam(':product', $product_txt);
            $update->bindParam(':category', $category_txt);
            $update->bindParam(':description', $description_txt);
            $update->bindParam(':stock', $stock_txt);
            $update->bindParam(':sprice', $saleprice_txt);
            $update->bindParam(':image', $image_db);

            if ($update->execute()) {

                $_SESSION['status'] = "Product Updated Successfully";
                $_SESSION['status_code'] = "success";
            } else {
                $_SESSION['status'] = "Product Update Failed";
                $_SESSION['status_code'] = "error";
            }
        }
    }else{
        $_SESSION['status'] = "Fill * Fields";
        $_SESSION['status_code'] = "error";
    }
}

$select = $conn->prepare("select * from tbl_product where pid=$id");
$select->execute();

$row = $select->fetch(PDO::FETCH_ASSOC);

$id_db = $row['pid'];

$barcode_db = $row['barcode'];
$product_db = $row['product'];
$category_db = $row['categoryid'];
$description_db = $row['description'];
$stock_db = $row['stock'];
$saleprice_db = $row['saleprice'];
$image_db = $row['image'];
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- <h1 class="m-0">Admin Dashboard</h1> -->
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="productlist.php">Product List</a></li>
                        <li class="breadcrumb-item active">Edit Product</li>
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

                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h5 class="m-0">Edit Product</h5>
                        </div>

                        <form action="" method="post" name="formeditproduct" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label>Barcode</label>
                                            <input type="text" class="form-control" value="<?php echo $barcode_db; ?>"
                                                placeholder="Enter Barcode" name="txtbarcode" autocomplete="off"
                                                disabled>
                                        </div>

                                        <div class="form-group">
                                            <label>Product Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" value="<?php echo $product_db; ?>"
                                                placeholder="Enter Name" name="txtproductname" autocomplete="off"
                                                required>
                                        </div>

                                        <div class="form-group">
                                            <label>Category <span class="text-danger">*</span></label>
                                            <select class="form-control" name="txtselect_option" required>
                                                <option value="" disabled selected>Select Category</option>

                                                <?php
                                                $select = $conn->prepare("select * from tbl_category order by catid desc");
                                                $select->execute();

                                                while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                                                    extract($row);

                                                    ?>
                                                    <option <?php if ($row['catid'] == $category_db) { ?> selected="selected"
                                                        <?php } ?> value=<?php echo $row['catid']; ?>><?php echo $row['category']; ?></option>

                                                    <?php
                                                }
                                                ?>

                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea class="form-control" placeholder="Enter Description"
                                                name="txtdescription" rows="4"
                                                required><?php echo $description_db; ?> </textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label>Minimum Stock Quantity <span class="text-danger">*</span></label>
                                            <input type="number" min="1" step="any" class="form-control"
                                                value="<?php echo $stock_db; ?>" placeholder="Enter Stock"
                                                name="txtstock" autocomplete="off" required>
                                        </div>


                                        <div class="form-group">
                                            <label>Sale Price <span class="text-danger">*</span></label>
                                            <input type="number" min="1" step="any" class="form-control"
                                                value="<?php echo $saleprice_db; ?>" placeholder="Enter Stock"
                                                name="txtsaleprice" autocomplete="off" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Product image <span class="text-danger">*</span></label><br />
                                            <image src="productimages/<?php echo $image_db; ?>" class="img-rounded"
                                                width="50px" height="50px/">

                                                <input type="file" class="input-group" name="myfile">
                                                <p>Upload image</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success" name="btneditproduct">Update
                                        Product</button>
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