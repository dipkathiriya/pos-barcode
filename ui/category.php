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

    $category = $_POST['txtcategory'];
    $role=$_SESSION['username'];
    $date= date('Y-m-d');

    $query = "SELECT * FROM tbl_category where category='$category' ";
    $filtered_count =execute($query);
    if(sizeof($filtered_count) > 0)
    {
        $_SESSION['status_code'] ="warning";
        $_SESSION['status']  = "Data Already Exist";
    }else{
        if (empty($category)) {

            $_SESSION['status'] = "Category Feild is Empty";
            $_SESSION['status_code'] = "warning";
        } else {
            $insert=$conn->prepare("insert into tbl_category (category,entry_by,entry_date) values(:cat,:entry_by,:entry_date)");
            $insert->bindParam(':cat',$category);
            $insert->bindParam(':entry_by',$role);
            $insert->bindParam(':entry_date',$date);

            if ($insert->execute()) {
                $_SESSION['status'] = "Category Added successfully";
                $_SESSION['status_code'] = "success";
            } else {

                $_SESSION['status'] = "Category Added Failed";
                $_SESSION['status_code'] = "warning";
            }
        }
    }
}

if (isset($_POST['btnupdate'])) {

    $category = $_POST['txtcategory'];
    $id = $_POST['txtcatid'];
    $role=$_SESSION['username'];
    $date= date('Y-m-d');
    
    $query = "SELECT * FROM tbl_category where category='$category' ";
    $filtered_count =execute($query);
    if(sizeof($filtered_count) > 0)
    {
        $_SESSION['status_code'] ="warning";
        $_SESSION['status']  = "Data Already Exist";
    }else{
        if (empty($category)) {

            $_SESSION['status'] = "Category Feild is Empty";
            $_SESSION['status_code'] = "warning";
        } else {
            $update=$conn->prepare("update tbl_category set category=:cat ,update_by=:role,update_date=:date where catid=".$id);
            $update->bindParam(':cat',$category);
            $update->bindParam(':role',$role);
            $update->bindParam(':date',$date);

            if ($update->execute()) {
                $_SESSION['status'] = "Category Update successfully";
                $_SESSION['status_code'] = "success";
            } else {

                $_SESSION['status'] = "Category Update Failed";
                $_SESSION['status_code'] = "warning";
            }
        }
    }
}


if (isset($_POST['btndelete'])) {
    $delete=$conn->prepare("delete from tbl_category where catid=".$_POST['btndelete']); 

    if ($delete->execute()) {
        $_SESSION['status'] = "Deleted";
        $_SESSION['status_code'] = "success";
    } else {
        $_SESSION['status'] = "Delete Failed";
        $_SESSION['status_code'] = "warning";
    }
} else {
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Category</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
            <li class="breadcrumb-item active">Category</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h5 class="m-0">Category Form</h5>
                </div>
                <form action="" method="post">
                    <div class="card-body">
                        <div class="row">
                            <?php
                            if (isset($_POST['btnedit'])) {
                                $select=$conn->prepare("select * from tbl_category where catid =".$_POST['btnedit']);
                                $select->execute();
                                
                                if($select){
                                $row=$select->fetch(PDO::FETCH_OBJ);

                                    echo '<div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Category</label>
                                                <input type="hidden" class="form-control" placeholder="Enter Category"  value="' . $row->catid . '" name="txtcatid" >
                                                <input type="text" class="form-control" placeholder="Enter Category"  value="' . $row->category . '" name="txtcategory" >
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" class="btn btn-info" name="btnupdate">Update</button>
                                            </div>
                                          </div>';
                                }
                            } else {
                                echo '<div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Category</label>
                                            <input type="text" class="form-control" placeholder="Enter Category"  name="txtcategory" >
                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-warning" name="btnsave">Save</button>
                                        </div>
                                      </div>';
                            }
                            ?>
                            <div class="col-md-8">
                                <table id="table_category" class="table table-striped table-hover ">
                                    <thead>
                                        <tr>
                                            <td>Category</td>
                                            <td>Entry By</td>
                                            <td>Entry Date</td>
                                            <td>Edit</td>
                                            <td>Delete</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $select = $conn->prepare("select * from tbl_category order by catid ASC");
                                        $select->execute();
                                        
                                        while($row=$select->fetch(PDO::FETCH_OBJ))
                                        {
                                            echo '
                                            <tr>
                                               
                                                <td>' . $row->category . '</td>
                                                <td>' . $row->entry_by . '</td>
                                                <td>' . $row->entry_date . '</td>
                                                <td>
                                                    <button type="submit" class="btn btn-primary" value="' . $row->catid . '" name="btnedit">Edit</button>
                                                </td>
                                                <td>
                                                    <button type="submit" class="btn btn-danger" value="' . $row->catid . '" name="btndelete">Delete</button>
                                                </td>
                                            </tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
include_once "footer.php";
?>
<!-- <script>
$('#category_master').validate({
    rules: {
      txtcategory: {
        required: true
      }
    },
    messages: {
      txtcategory: {
        required: "Category Is Required"
      }
    },
    submitHandler: function (form) {
      var values = $('#category_master').serialize();
      $.ajax({
        type: "POST",
        url: "getdata.php",
        dataType: 'json',
        data: {
          action: "category_add",
          data: values
        },
        success: function (data) {
          var myJSON = JSON.stringify(data);
          var json = JSON.parse(myJSON);
          if (json.status === 1) {
            showMessage(json.message);
            $('#txtcategory').val('');
          }else{
            showError(json.message);
          }
        }
      });
    }
});
</script> -->
<script>
    $(document).ready(function() {
        // location.reload();
        $('#table_category').DataTable();
    });
</script>
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