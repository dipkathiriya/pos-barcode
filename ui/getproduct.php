<?php

    include_once 'connectdb.php';

    global $outputjson,$conn;

    $action =$_GET['action'];
    
   if($action){
       if($action=="get_product")
       {
            $pid = $_GET["id"];
            $barcode = $_GET["id"];
            $query = "SELECT p.*
            ,ifnull((select sum(op.stock) from store_stock op where op.productid=p.pid),0) as current_stock
            FROM tbl_product p
            WHERE p.pid = $pid OR p.barcode = $barcode";
            $row=execute($query);
            // print_r($row);
            if(sizeof($row)>0){
                $outputjson['data']=$row;
                $outputjson['message']='Data Found';
                $outputjson['status']=1;
            }else{
                $outputjson['message']='Data Not Found';
                $outputjson['status']=0;
            }
       
       }
    }
	echo json_encode($outputjson);
?>