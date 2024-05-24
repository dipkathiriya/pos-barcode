<?php
include_once 'connectdb.php';
session_start();
if ($_SESSION['role'] == $amploye or $_SESSION['role'] == $warehouse or $_SESSION['username'] == "") {
    header('location:logout.php');
  }
if($_SESSION['role']==$admin){
    include_once "header.php";
    include 'barcode/barcode128.php';
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
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="productlist.php">Product List</a></li>
            <li class="breadcrumb-item active">View Product</li>
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


                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h5 class="m-0">View Product</h5>
                        </div>
                        <div class="card-body">
                            <?php
                                $id = $_GET['id'];
                                $select = $conn->prepare("SELECT p.* ,c.category
                                ,ifnull((select sum(s.stock) from store_stock s where s.productid=p.pid),0) as total_stock
                                FROM tbl_product p
                                INNER JOIN tbl_category c ON c.catid=p.categoryid
                                WHERE pid = $id");
                                $select->execute();

                                while($row=$select->fetch(PDO::FETCH_ASSOC)){
                                echo'
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="list-group">

                                        <center><p class="list-group-item list-group-item-info"><b>PRODUCT DETAILS</b></p></center>   
                                            <li class="list-group-item"><b>Barcode </b><span class="badge badge-light float-right">'.bar128($row['barcode']).'</span></li>
                                            <li class="list-group-item"><b>Product Name </b><span class="badge badge-warning float-right">'.$row['product'].'</span></li>
                                            <li class="list-group-item"><b>Category </b><span class="badge badge-success float-right">'.$row['category'].'</span></li>
                                            <li class="list-group-item"><b>Minimum Stock </b><span class="badge badge-danger float-right">'.$row['stock'].'</span></li>
                                            <li class="list-group-item"><b>Available_Stock </b><span class="badge badge-danger float-right">'.$row['total_stock'].'</span></li>
                                            <li class="list-group-item"><b>Sale Price</b> <span class="badge badge-dark float-right">'.$row['saleprice'].'</span></li>
                                            <li class="list-group-item"><b>Description</b> <textarea class="badge float-right"  wrap="soft" cols="30" rows="5"  style="resize:none; width: 100%;min-height: 50px;" readonly>'.$row['description'].'</textarea></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-group">
                                            <center><p class="list-group-item list-group-item-info"><b>PRODUCT IMAGE</b></p></center>   
                                            <img src="productimages/'.$row['image'].'" class="img-responsive"/>
                                        </ul>
                                    </div>
                                </div>
                                ';
                            }
                            ?>
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