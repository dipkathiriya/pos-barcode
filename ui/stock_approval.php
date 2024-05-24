<?php
include_once 'connectdb.php';
session_start();

if ($_SESSION['role'] == $amploye or $_SESSION['role'] == $admin or $_SESSION['username'] == "") {
  header('location:logout.php');
}

if ($_SESSION['role'] == $warehouse) {
  include_once 'header.php';
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
            <li class="breadcrumb-item"><a href="warehouse.php">Home</a></li>
            <li class="breadcrumb-item active">WareHouse Stock Approval</li>
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
              <h5 class="m-0">Product Stock Request List</h5>
            </div>
            <div class="card-body">


              <table class="table table-striped table-hover" id="table_product">
                <thead>
                  <tr>
                    <td>Date</td>
                    <td>Entry By</td>
                    <td>ActionIcons</td>
                  </tr>
                </thead>
                <tbody id="tabledata">
                  
                </tbody>
              </table>
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
<script>
  $(document).ready(function() {
    listdetails();
    $('#table_product').DataTable();
    $('[data-togle="tooltip"]').tooltip();
  });

  function listdetails(){
    $.ajax({
        url: 'fill_selection.php',
        method: 'POST',
        dataType: 'json',
        data: { action:'fill_list'},
        success: function(data){
            var myJSON = JSON.stringify(data);
            var json=JSON.parse(myJSON);
            showMessage(json.message);
            var html='';
            var row=json.data;
            for(var j=0;j< row.length;j++)
            {
                html+='<tr>'
                html+='<td><button class="btn btn-info rounded-pill tbl-btn" onclick="product_list('+ row[j].id + ')">' + row[j].date + '</button></td>'
                html+='<td>' + row[j].role + '</td>'
                html+='<td>'
                if(row[j].is_approv == 0 ){
                  html+='<div class="btn-group" id="' + row[j].id + '">'
                  html+=' <button id='+ row[j].id +  ' class="btn btn-success btn-xl btnapprove" onclick="approval_stock('+ row[j].id + ') "><span class="fa fa-check" style="color:#ffffff" data-toggle="tooltip" title="Approv Stock Request"</span></button>'
                  html+=' <button id='+ row[j].id + ' class="btn btn-danger btn-xl mx-2 delete" value="'+ row[j].id +'" ><span class="fa fa-times" style="color:#ffffff" data-toggle="tooltip" title="Cancel Stock Request"></span></button>'
                  html+='</div>'
                }else if(row[j].is_approv == 1){
                  html+='<span class="fa fa-check mt-2" style="color:black" data-toggle="tooltip" title="Approve Stock Request"><label class="form-label mx-2">Stock Approved</label></span>'
                }else{
                  html +='<span class="fa fa-times mt-2" style="color:black" data-toggle="tooltip" title="Cancel Stock Request"><label class="form-label mx-2">Stock Cancel</label></span>'
                }
                html+='</td>'
                html+='</tr>'
            }
            $('#tabledata').html(html);
        }
    });
  }
</script>

<script>
  
  
  function approval_stock(id) {
    var id = id;
    $.ajax({
      url: 'getdata.php',
      method: 'POST',
      data: {
        id: id,
        action: 'approval_stock'
      },
      success: function(data) {
        var json=JSON.parse(data);
        if(json.status ==1){
          listdetails();
          showMessage(json.message);
        }else{
          showError(json.message);
        }
      }
    });
  }

  $( "body" ).on( "click", '.delete', function() {
      var tdh = $(this);
      var id = $(this).val();
      Swal.fire({
        title: "Do you want to Stock Request Cancel?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Cancel it!"
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: 'getdata.php',
            type: "post",
            dataType: 'json',
            data: {
              id: id,
              action:"request_delete"
            },
            success: function(data) {
              var myJSON = JSON.stringify(data);
              var json=JSON.parse(myJSON);
              listdetails();
              showMessage(json.message);
            }
          });
          Swal.fire({
            title: "Deleted!",
            text: "Stock Request Cancel.",
            icon: "success"
          });
        }
      });
    });

  function product_list(index) {

    var id = index;
    $.ajax({
      url: 'fill_selection.php',
      method: 'POST',
      data: {
        id: id,
        action: 'productstock_model'
      },
      success: function(data) {
        var json = JSON.parse(data);
        var html = '';
        var row = json.data;
        op_html='';
        op_html+='<div class="table-responsive">';
        op_html+='   <table class="table ">';
        op_html+='       <thead>';
        op_html+='           <tr class="table-primary" >';
        op_html+='              <th scope="row">Category</th>';
        op_html+='              <th scope="row">Product</th>';
        op_html+='              <th scope="row">Qty</th>';
        op_html+='              <th scope="row">Warehouse Stock</th>';
        op_html+='           </tr>';
        op_html+='        </thead>';
        op_html+='        <tbody>';
        for (var j = 0; j < row.length; j++) {
          op_html +='<tr class="index-rows">'; 
          op_html += ' <td>'+row[j].category+'</td>';        
          op_html +=' <td>'+row[j].product +'</td>';         
          op_html +=' <td>'+row[j].qty+'</td>';         
          op_html +=' <td>'+row[j].stock+'</td>';         
          op_html += '</tr>';     
        }
        op_html+='        </tbody>';
        op_html+='   </table>';
        op_html+='</div>';
        $("#comman_ListModal #comman_list_model_div").html(op_html);


      }
    });
    $("#comman_ListModal").modal('show');
    $("#comman_ListModal .comman_list_model_header").html('Product Stock Details');
  }
</script>