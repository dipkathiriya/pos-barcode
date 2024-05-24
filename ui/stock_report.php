<?php 
include_once 'connectdb.php';
session_start();

if ( $_SESSION['role'] == $amploye AND  $warehouse or  $_SESSION['username'] == "") {
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
                    <h1 class="m-0">Stock Report</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Stock Report</li>
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
                        <form id="stock_report" name="stock_report" method="post">
                            <div class="card-header">
                                <h5 class="m-0">Stock Report</h5>
                            </div>
                            <div class="panel mt-3 mx-3 ">
                                <div class="row">
                                    <div class="mb-3 col-sm-4">
                                        <label class="form-label" for="category">Category</label>
                                        <select class="form-control" id="categoryid" name="categoryid" >

                                        </select>
                                    </div>
                                    <div class="mb-3 col-sm-4">
                                        <label class="form-label" for="productid">Product</label>
                                        <select class="form-control" id="productid" name="productid" >

                                        </select>
                                    </div>
                                    <div class="mb-3 col-sm-4">
                                        <label class="form-label" for="stocktype">Stock Type</label>
                                        <select class="form-control" name="stocktype" id="stocktype">
                                            <option value="%">All Stock</option>
                                            <option value="0">Mines Stock</option>
                                            <option value="1">Plus Stock</option>
                                        </select>
                                    </div>
                                </div>
                            </div>  
                            <div class="row"> 
                                <div class="col-sm-12 mb-5 text-end mt-4 ">
                                    <button class="btn btn-primary offset-sm-3" type="submit">Genrate Report</button>
                                    <button class="btn btn-primary offset-sm-3"  id="printbtn" >Print</button> 
                                </div>
                            </div>
                            <div class="container-fluid">
                                 <div class="row"  style="height: 580px;overflow-y: scroll;">
                                    <div class="table-responsive mt-3 " id="reportdiv">
        
                                    </div>
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
    $(document).ready(function() {
        fillcategory();
    })
    
    $("#printbtn").click(function () {
        $('#reportdiv').printThis({
            header: "<h3>Stock  Report</h3>",
            importCSS: true,            // import parent page css
            importStyle: true,          // import style tags
        });
    });
    
    $("#stock_report").on('submit', function (e) {
        e.preventDefault();
        get_report_data();
    });

    function fillcategory() {
        $.ajax({
            url: "fill_selection.php",
            type: "POST",
            dataType: 'json',
            data: {
                action: "fill_category"
            },
            success: function(data) {
                var myJSON = JSON.stringify(data);
                var json = JSON.parse(myJSON);
                if (json.status === 1) {
                    var op_product = '<option value="%">All Category</option>';
                    var row = json.data;
                    for (var j = 0; j < row.length; j++) {
                        op_product += '<option value="' + row[j].catid + '">' + row[j].category + '</option>'
                    }
                    $('#categoryid').html(op_product);
                    product();
                } else {
                    $('#categoryid').html();
                }
            }
        })
    }

    $('#categoryid').change(function() {
        product();
    });

    function product() {
        var category_id = $('#categoryid').val();
        $.ajax({
            url: 'fill_selection.php',
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'fill_product',
                category_id: category_id
            },
            success: function(data) {
                var myJSON = JSON.stringify(data);
                var json = JSON.parse(myJSON);
                if (json.status === 1) {
                    var op_product = '<option value="%">All Product</option>';;
                    var row = json.data;
                    for (var j = 0; j < row.length; j++) {
                        op_product += '<option value="' + row[j].pid + '">' + row[j].product + '</option>'
                    }
                    $('#productid').html(op_product);
                } else {
                    $('#productid').html();
                }
            }
        });
    }
    
function get_report_data(){
    var category_id = $('#categoryid').val();
    var productid = $('#productid').val();
    var stocktype = $('#stocktype').val();
        $.ajax({
            url: 'fill_selection.php',
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'fill_stock_report',
                category_id: category_id,
                productid: productid,
                stocktype: stocktype
            },
            success: function(data) {
                var myJSON = JSON.stringify(data);
                var json = JSON.parse(myJSON);
                var op_html = '';
                if (json.status === 1) {
                    var row=data.subdata;
                    op_html+='<div class="table-responsive">';
                    op_html+='   <table class="table ">';
                    op_html+='       <thead>';
                    op_html+='           <tr class="table-primary" >';
                    op_html+='              <th scope="row">Sr. No</th>';
                    op_html+='              <th scope="row">Product Name</th>';
                    op_html+='              <th scope="row">Category</th>';
                    op_html+='              <th scope="row">Minstockqty</th>';
                    op_html+='              <th scope="row">Stock</th>';
                    op_html+='           </tr>';
                    op_html+='        </thead>';
                    op_html+='        <tbody>';
                    for(var j=0;j< row.length;j++)
                    {
                        
                        op_html+='           <tr style="background-color:'+row[j].color+'">';
                        op_html+='             <td >'+row[j].pid+'</td>';
                        op_html+='             <td >'+row[j].product+'</td>';
                        op_html+='             <td >'+row[j].category+'</td>';
                        op_html+='             <td >'+row[j].stock+'</td>';
                        op_html+='             <td >'+row[j].cstock+'</td>';
                        op_html+='           </tr>';
                    } 
                    op_html+='        </tbody>';
                    op_html+='   </table>';
                    op_html+='</div>';
                    $('#reportdiv').html(op_html);
                }else{
                    op_html+='<div class="table-responsive">';
                    op_html+='   <table class="table ">';
                    op_html+='       <thead>';
                    op_html+='           <tr class="table-success" >';
                    op_html+='              <th scope="row">Sr. No</th>';
                    op_html+='              <th scope="row">Product Name</th>';
                    op_html+='              <th scope="row">Category</th>';
                    op_html+='              <th scope="row">Minstockqty</th>';
                    op_html+='              <th scope="row">Stock</th>';
                    op_html+='           </tr>';
                    op_html+='        </thead>';
                    op_html+='        <tbody>';
                    op_html+='            <tr class="">';
                    op_html+='                <td colspan="4"class="text-center"><b>No Data Found</b></td>';
                    op_html+='            </tr>';
                    op_html+='        </tbody>';
                    op_html+='   </table>';
                    op_html+='</div>';
                }
            }
        });
}

</script>