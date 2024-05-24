<?php

include_once 'connectdb.php';
session_start();



if ($_SESSION['role'] == $amploye or $_SESSION['role'] ==  $admin or $_SESSION['username'] == "") {
    header('location:logout.php');
}
if($_SESSION['role']==$warehouse){
    include_once "header.php";
}


// $select =$conn->prepare("select sum(purchase) as gt , count(vendor_id) as vendor from tbl_invoice");
$select =$conn->prepare("select sum(tamount) as gt from tbl_purchase_master");
$select->execute();
$row=$select->fetch(PDO::FETCH_OBJ);
$grand_total=$row->gt;

$select =$conn->prepare("select count(id) as vendor from tbl_vendermaster");
$select->execute();
$row=$select->fetch(PDO::FETCH_OBJ);
$vendors=$row->vendor;

$select =$conn->prepare("select count(product) as pname from tbl_product");
$select->execute();
$row=$select->fetch(PDO::FETCH_OBJ);

$total_product=$row->pname;

$select =$conn->prepare("select count(category) as cate from tbl_category");
$select->execute();
$row=$select->fetch(PDO::FETCH_OBJ);

$total_category=$row->cate;


?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Warehouse Dashboard</h1>
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



                <div class="row">
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?php echo $vendors; ?></h3>

                                    <p>TOTAL VENDOR</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3><?php echo number_format($grand_total, 2); ?></h3>

                                    <p>TOTAL PURCHASE(INR)</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3><?php echo $total_product; ?></h3>

                                    <p>TOTAL PRODUCT</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3><?php echo $total_category; ?></h3>
                                    <p>TOTAL CATEGORY</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                            </div>
                        </div>
                        <!-- ./col -->
                    </div>



                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <!-- <h5 class="m-0">Featured</h5> -->
                        </div>
                        <div class="card-body">
                            
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