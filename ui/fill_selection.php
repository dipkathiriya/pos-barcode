<?php 
include_once 'connectdb.php';
session_start();
	global $outputjson,$conn;
	$action =$_POST['action'];
	if($action){
		if($action=="fill_category")
		{
			$query = "SELECT * FROM tbl_category ORDER BY catid DESC";
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
		
		}else if($action=='fill_product'){
			$category_id = $_POST['category_id'];
			$query = "SELECT * FROM tbl_product WHERE categoryid like '$category_id'";
			$row=execute($query);
			// print_r($output);
			if(sizeof($row)>0){
				$outputjson['data']=$row;
				$outputjson['message']='Data Found';
				$outputjson['status']=1;
			}else{
				$outputjson['data']=[];
				$outputjson['message']='Data Not Found';
				$outputjson['status']=0;
			}
			
		}else if($action=='fill_productstock'){
			
			$query = "SELECT p.pid AS id ,p.product,c.category,c.catid AS cid  
			,ifnull((select sum(op.stock) from warehouse_stock op where op.productid=p.pid),0) as opning_stock
			FROM  tbl_product as p 
			INNER JOIN tbl_category c ON c.catid=p.categoryid
			where p.pid like '%' ";
			$row=execute($query);
			// print_r($output);
			if(sizeof($row)>0){
				$outputjson['data']=$row;
				$outputjson['message']='Data Found';
				$outputjson['status']=1;
			}else{
				$outputjson['data']=[];
				$outputjson['message']='Data Not Found';
				$outputjson['status']=0;
			}
		}
		else if($action=='fill_storestock'){
			
			$query = "SELECT p.pid AS id ,p.product,c.category,c.catid AS cid 
			,ifnull((select sum(op.stock) from store_stock op where op.productid=p.pid),0) as opning_stock
			FROM  tbl_product as p 
			INNER JOIN tbl_category c ON c.catid=p.categoryid
			where   p.pid like '%' ";
			$row=execute($query);
			// print_r($output);
			if(sizeof($row)>0){
				$outputjson['data']=$row;
				$outputjson['message']='Data Found';
				$outputjson['status']=1;
			}else{
				$outputjson['data']=[];
				$outputjson['message']='Data Not Found';
				$outputjson['status']=0;
			}
		}
		else if($action=='productstock_model'){

			$id = $_POST['id'];
			$query = "SELECT ps.* 
			,ifnull((select sum(s.stock) from warehouse_stock s where s.productid=ps.productid),0) as stock
			FROM tbl_productrequest_detail  ps
			WHERE ps.pid = '$id' ";
			$row=execute($query);
			// print_r($output);
			if(sizeof($row)>0){
				$outputjson['data']=$row;
				$outputjson['message']='Data Found';
				$outputjson['status']=1;
			}else{
				$outputjson['data']=[];
				$outputjson['message']='Data Not Found';
				$outputjson['status']=0;
			}
		}
		else if($action=='orderlist_model'){

			$id = $_POST['id'];
			$query = "SELECT ps.*,ind.* 
			FROM tbl_invoice  ps
			INNER JOIN tbl_invoice_details ind ON ind.invoice_id=ps.invoice_id
			WHERE ps.invoice_id = '$id' ";
			$row=execute($query);
			// print_r($output);
			if(sizeof($row)>0){
				$outputjson['data']=$row;
				$outputjson['message']='Data Found';
				$outputjson['status']=1;
			}else{
				$outputjson['data']=[];
				$outputjson['message']='Data Not Found';
				$outputjson['status']=0;
			}
		}
		else if($action=='purchase_list'){

			$id = $_POST['id'];
			$query = "SELECT * FROM tbl_subpurchase WHERE pid = '$id' ";
			$row=execute($query);
			// print_r($output);
			if(sizeof($row)>0){
				$outputjson['data']=$row;
				$outputjson['message']='Data Found';
				$outputjson['status']=1;
			}else{
				$outputjson['data']=[];
				$outputjson['message']='Data Not Found';
				$outputjson['status']=0;
			}
		}
		else if($action=='fill_list'){

			$query = "SELECT p.* from tbl_product_request p  ";
			$row=execute($query);
			// print_r($output);
			if(sizeof($row)>0){
				$outputjson['data']=$row;
				$outputjson['message']='Data Found';
				$outputjson['status']=1;
			}else{
				$outputjson['data']=[];
				$outputjson['message']='Data Not Found';
				$outputjson['status']=0;
			}
		}
		else if($action=='fill_username'){

			$query = "SELECT p.* from tbl_users p  ";
			$row=execute($query);
			// print_r($output);
			if(sizeof($row)>0){
				$outputjson['data']=$row;
				$outputjson['message']='Data Found';
				$outputjson['status']=1;
			}else{
				$outputjson['data']=[];
				$outputjson['message']='Data Not Found';
				$outputjson['status']=0;
			}
		}
		else if($action=='fill_fields'){
			$username = $_POST['username'];
			$query = "SELECT p.* from tbl_users p WHERE p.id=$username ";
			$row=execute($query);
			// print_r($output);
			if(sizeof($row)>0){
				$outputjson['data']=$row;
				$outputjson['message']='Data Found';
				$outputjson['status']=1;
			}else{
				$outputjson['data']=[];
				$outputjson['message']='Data Not Found';
				$outputjson['status']=0;
			}
		}
		else if($action=='fill_profile'){

			$id =$_SESSION['id'];
			$query = "SELECT p.* from tbl_users p WHERE p.id=$id ";
			$row=execute($query);
			// print_r($output);
			if(sizeof($row)>0){
				$outputjson['data']=$row;
				$outputjson['message']='Data Found';
				$outputjson['status']=1;
			}else{
				$outputjson['data']=[];
				$outputjson['message']='Data Not Found';
				$outputjson['status']=0;
			}
		}
		else if($action=='check_prices'){

			$id =$_POST['id'];
			$query = "SELECT p.* from tbl_product p WHERE p.pid=$id ";
			$row=execute($query);
			
			if(sizeof($row)>0){
				$outputjson['data']=$row;
				$outputjson['message']='Data Found';
				$outputjson['status']=1;
			}else{
				$outputjson['data']=[];
				$outputjson['message']='Data Not Found';
				$outputjson['status']=0;
			}
			
		}
		else if($action=='fill_stock_report'){
			$categoryid = $_POST['category_id'];
			$productid = $_POST['productid'];
			$stocktype = $_POST['stocktype'];

			$strqty='';
			if($stocktype == 1)
			{
				$strqty=' AND stock < cstock';
			}else if($stocktype == 0)
			{
				$strqty=' AND stock > cstock';
			}

			$query = "SELECT *,case when (stock > cstock) then '#f63c091f' else '#60bb462b' end as tmpcolor 
				from (
				SELECT pm.pid,pm.product,pm.stock,c.category,
				ifnull((select sum(sv.stock) from store_stock sv WHERE sv.productid=pm.pid) ,0) as cstock
				FROM `tbl_product` pm
				INNER JOIN `tbl_category` c ON c.catid = pm.categoryid
				WHERE pm.pid like '$productid' and pm.categoryid like '$categoryid' order by pm.product ) as tmp where 1=1 $strqty ";

				$result=execute($query);

				$outputjson['subdata'] = $result;
            $j=1;
            for ($i=0; $i < sizeof($result); $i++) { 
                $subd=$result[$i];
                $outputjson['subdata'][$i]['pid'] = $j;
                $outputjson['subdata'][$i]['color'] = $subd['tmpcolor'];
                $outputjson['subdata'][$i]['category'] = $subd['category'];
                $outputjson['subdata'][$i]['product'] = $subd['product'];
                $outputjson['subdata'][$i]['stock'] = $subd['stock'];
                $outputjson['subdata'][$i]['cstock'] = $subd['cstock'];
                $j++;
            }

				$outputjson['message']='Data Found';
				$outputjson['status']=1;
		}
		else if($action=='fill_opning_stock_report'){
			$fromdate = $_POST['fromdate'];
			$todate = $_POST['todate'];

			$query="SELECT so.id,sod.category,sod.product,sod.stock 
				FROM tbl_store_opningstock so 
				INNER JOIN tbl_store_opningstock_detail sod on sod.soid=so.id
				WHERE so.date BETWEEN '$fromdate' AND '$todate'";
			$result=execute($query);

			$outputjson['subdata'] = $result;
            $j=1;
            for ($i=0; $i < sizeof($result); $i++) { 
                $subd=$result[$i];
                $outputjson['subdata'][$i]['pid'] = $j;
                $outputjson['subdata'][$i]['category'] = $subd['category'];
                $outputjson['subdata'][$i]['product'] = $subd['product'];
                $outputjson['subdata'][$i]['stock'] = $subd['stock'];
                $j++;
            }

				$outputjson['message']='Data Found';
				$outputjson['status']=1;
		}
		else if($action=='ware_opningstock_report'){
			$fromdate = $_POST['fromdate'];
			$todate = $_POST['todate'];

			$query="SELECT so.id,sod.category,sod.product,sod.stock 
				FROM tbl_opningstock so 
				INNER JOIN tbl_opningstock_detail sod on sod.osid=so.id
				WHERE so.date BETWEEN '$fromdate' AND '$todate'";
			$result=execute($query);

			$outputjson['subdata'] = $result;
            $j=1;
            for ($i=0; $i < sizeof($result); $i++) { 
                $subd=$result[$i];
                $outputjson['subdata'][$i]['pid'] = $j;
                $outputjson['subdata'][$i]['category'] = $subd['category'];
                $outputjson['subdata'][$i]['product'] = $subd['product'];
                $outputjson['subdata'][$i]['stock'] = $subd['stock'];
                $j++;
            }

				$outputjson['message']='Data Found';
				$outputjson['status']=1;
		}
	}
	echo json_encode($outputjson);
	?>