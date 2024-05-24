<?php
include_once 'connectdb.php';
global $outputjson,$conn;
$action =$_POST['action'];
	if($action){
		if($action=="productlist")
		{
            $id=$_POST['id'];
            $sql="DELETE FROM tbl_product WHERE pid =$id";

            $delete=$conn->prepare($sql);

            if($delete->execute()){
				$outputjson['message']='Data Deleted';
				$outputjson['status']=1;
			}else{
				$outputjson['message']='Data Not Deleted';
				$outputjson['status']=0;
			}
        }
		else if($action=="productstock_list")
		{
            $id=$_POST['id'];
            $sql="DELETE FROM tbl_product_request WHERE id =$id";
            $query="DELETE FROM tbl_productrequest_detail WHERE pid =$id";

            $delete=$conn->prepare($sql);
            $result=$conn->prepare($query);

            if($delete->execute() AND $result->execute() ){
				$outputjson['message']='Data Deleted';
				$outputjson['status']=1;
			}else{
				$outputjson['message']='Data Not Deleted';
				$outputjson['status']=0;
			}
        }
		else if($action=="purchase_master")
		{
            $id=$_POST['id'];
            $sql="DELETE FROM tbl_purchase_master WHERE id =$id";
            $query="DELETE FROM tbl_subpurchase WHERE pid =$id";

            $delete=$conn->prepare($sql);
            $result=$conn->prepare($query);

            if($delete->execute() AND $result->execute() ){
				$outputjson['message']='Data Deleted';
				$outputjson['status']=1;
			}else{
				$outputjson['message']='Data Not Deleted';
				$outputjson['status']=0;
			}
        }
		else if($action=="orderlist")
		{
            $id=$_POST['id'];
            $sql="DELETE FROM tbl_invoice WHERE invoice_id =$id";
            $query="DELETE FROM tbl_invoice_details WHERE invoice_id =$id";

            $delete=$conn->prepare($sql);
            $result=$conn->prepare($query);

            if($delete->execute() AND $result->execute() ){
				$outputjson['message']='Data Deleted';
				$outputjson['status']=1;
			}else{
				$outputjson['message']='Data Not Deleted';
				$outputjson['status']=0;
			}
        }
		else if($action=="vender_master")
		{
            $id=$_POST['id'];
            $sql="DELETE FROM tbl_vendermaster WHERE id =$id";
            $delete=$conn->prepare($sql);
            if($delete->execute() ){
				$outputjson['message']='Data Deleted';
				$outputjson['status']=1;
			}else{
				$outputjson['message']='Data Not Deleted';
				$outputjson['status']=0;
			}
        }
    }
	echo json_encode($outputjson);
?>