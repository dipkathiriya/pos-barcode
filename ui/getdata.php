<?php 
include_once "connectdb.php";
session_start();
$action =$_POST['action'];
global $outputjson,$conn;
if($action){
    if($action=="purchase_master"){
        $data =$_POST['data'];
        $table =$_POST['purchase_table'];
        parse_str($data, $values);
        
        $venderid = $values['venderid'];
        $billdate = $values['billdate'];
        $billno = $values['billno'];
        $pmdate = $values['pmdate'];
        $des = $values['des'];
        $tamount = $values['tamount'];
        $tqty = $values['tqty'];
        $ramount = $values['ramount'];
        $famount = $values['famount'];
        $role=$_SESSION['username'];
        $date= date('Y-m-d');

        if($billno or $billdate != ''){

            $query = "SELECT * FROM tbl_purchase_master where bill_no='$billno'";
            $filtered_count =execute($query);
            if(sizeof($filtered_count) > 0)
            {
                $outputjson['message']  = "Bill No Already Exist";
                $outputjson['status'] =0;
            }else {
                $query = "INSERT INTO tbl_purchase_master (venderid,billdate,bill_no,date,description,tamount,tqty,ramount, famount,entry_by,entry_date) VALUES ('$venderid','$billdate','$billno','$pmdate','$des','$tamount','$tqty','$ramount ','$famount','$role','$date') ";
                $row=execute($query);
                
                $id = $conn->lastInsertId();

                foreach ($table as $item) {

                    $category_id = $item['tbl_categoryid'];
                    $category = $item['tbl_category'];
                    $product_id = $item['tbl_productid'];
                    $product = $item['tbl_product'];
                    $qty = $item['tbl_qty'];
                    $rate = $item['tbl_rate'];
                    $total_amount = $item['tbl_totalamount'];

                        $query = "INSERT INTO tbl_subpurchase (pid,categoryid,category,productid, product,qty,rate,totalamount) VALUES ('$id','$category_id','$category','$product_id','$product ','$qty','$rate','$total_amount') ";
                        $row = execute($query);
    
                        $outputjson['message']='Data Inserted';
                        $outputjson['status']=1;
                   
                }
            }
        }else{
            $outputjson['message'] = "Fill * Fields";
            $outputjson['status'] = 0;
        }
    }else if($action=="product_stock"){
    
        $date =date('Y-m-d');
        $table =$_POST['purchase_table'];
        $role=$_SESSION['username'];

        
        $query = "INSERT INTO tbl_product_request (date,role) VALUES ('$date','$role') ";
        $row=execute($query);
        
        $id = $conn->lastInsertId();
        for ($i = 0;$i < sizeof($table);$i++) {
            $this_item = $table[$i];

            $category_id = $this_item['tbl_categoryid'];
            $category = $this_item['tbl_category'];
            $product_id = $this_item['tbl_productid'];
            $product = $this_item['tbl_product'];
            $qty = $this_item['tbl_qty'];

            $query = "INSERT INTO tbl_productrequest_detail (pid,categoryid,category,productid, product,qty) VALUES ('$id','$category_id','$category','$product_id','$product ','$qty') ";
            $row = execute($query);
        }

        $outputjson['message']='Data Inserted';
        $outputjson['status']=1;
    }
    else if($action=="edit_product_stock"){
    
        $data =$_POST['data'];
        $table =$_POST['purchase_table'];
        $role=$_SESSION['username'];
        parse_str($data, $values);

        $date = $values['pmdate'];
        $tbl_id = $values['tbl_id'];
        
       $query = "UPDATE tbl_product_request
        SET date = '$date',role = '$role'
        WHERE id= $tbl_id";
        $row=execute($query);

        
        $delete ="DELETE FROM tbl_productrequest_detail where pid =$tbl_id";
        $row=execute($delete);

        for ($i = 0;$i < sizeof($table);$i++) {
            $this_item = $table[$i];

            $category_id = $this_item['tbl_categoryid'];
            $category = $this_item['tbl_category'];
            $product_id = $this_item['tbl_productid'];
            $product = $this_item['tbl_product'];
            $qty = $this_item['tbl_qty'];

            $query = "INSERT INTO tbl_productrequest_detail (pid,categoryid,category,productid, product,qty) VALUES ('$tbl_id','$category_id','$category','$product_id','$product ','$qty') ";
            $row = execute($query);
        }

        $outputjson['message']='Data Updated';
        $outputjson['status']=1;
        
    }
    else if($action=="edit_purchase_master"){
    
        $data =$_POST['data'];
        $table =$_POST['purchase_table'];
        $role=$_SESSION['username'];
        parse_str($data, $values);

        $venderid = $values['venderid'];
        $tbl_id = $values['tbl_id'];
        $billdate = $values['billdate'];
        $billno = $values['billno'];
        $pmdate = $values['pmdate'];
        $des = $values['des'];
        $tamount = $values['tamount'];
        $tqty = $values['tqty'];
        $ramount = $values['ramount'];
        $famount = $values['famount'];
        $date = date("Y-m-d");

        if($billno or $billdate  != ''){
            $query = "SELECT * FROM tbl_purchase_master where bill_no='$billno'AND id <> '$tbl_id'";
            $filtered_count =execute($query);
            if(sizeof($filtered_count) > 0)
            {
                $_SESSION['status'] =0;
                $_SESSION['message']  = "Bill No Already Exist";
            }else {
                $query = "UPDATE tbl_purchase_master
                SET venderid = '$venderid',billdate = '$billdate',bill_no='$billno',date='$pmdate',description='$des',tamount='$tamount',tqty='$tqty',ramount='$ramount',famount='$famount',update_by='$role',update_date='$date'
                WHERE id= $tbl_id";
                $row=execute($query);

                
                $delete ="DELETE FROM tbl_subpurchase where pid =$tbl_id";
                $row=execute($delete);

                for ($i = 0;$i < sizeof($table);$i++) {
                    $this_item = $table[$i];

                    $category_id = $this_item['tbl_categoryid'];
                    $category = $this_item['tbl_category'];
                    $product_id = $this_item['tbl_productid'];
                    $product = $this_item['tbl_product'];
                    $qty = $this_item['tbl_qty'];
                    $rate = $this_item['tbl_rate'];
                    $total = $this_item['tbl_totalamount'];

                    $query = "INSERT INTO tbl_subpurchase (pid,categoryid,category,productid, product,qty,rate,totalamount) VALUES ('$tbl_id','$category_id','$category','$product_id','$product ','$qty','$rate','$total') ";
                    $row = execute($query);
                }

                $outputjson['message']='Data Updated';
                $outputjson['status']=1;
            }
        }else{
            $_SESSION['message'] = "Fill * Fields";
            $_SESSION['status'] = 0;
        }   
    }
    else if($action=="edite_pos_list"){
    
        $data =$_POST['data'];
        if($_POST['pos_table'] !=''){
            $table =$_POST['pos_table'];
            $role=$_SESSION['username'];
            parse_str($data, $values);

            $tbl_id = $values['tbl_id'];
            $subtotal = $values['txtsubtotal_id'];
            $txttotal = $values['txttxttotal'];
            $payment_type = $values['r3'];
            $ramount = $values['ramount'];
            $txtpaid = $values['txtpaid'];
            $date     = date('Y-m-d');
            if($txtpaid != 0 && $txtpaid !='' ){

                $query = "UPDATE tbl_invoice
                SET subtotal = '$subtotal',total = '$txttotal',payment_type='$payment_type',ramount='$ramount',paid='$txtpaid',update_by='$role',update_date='$date' WHERE invoice_id= $tbl_id";
                $row=execute($query);

                
                $delete ="DELETE FROM tbl_invoice_details where invoice_id =$tbl_id";
                $row=execute($delete);

                for ($i = 0;$i < sizeof($table);$i++) {
                    $this_item = $table[$i];

                    $tbl_barcode =$this_item['tbl_barcode'];
                    $tbl_productid =$this_item['tbl_productid'];
                    $tbl_product =$this_item['tbl_product'];
                    $tbl_stock = $this_item['tbl_stock'];
                    $tbl_saleprice = $this_item['tbl_saleprice'];
                    $tbl_qty = $this_item['tbl_qty'];
                    $tbl_totalamount = $this_item['tbl_totalamount'];

                    $query = "INSERT INTO tbl_invoice_details (invoice_id,barcode,product_id,product_name, qty,rate,saleprice,order_date) VALUES ('$tbl_id','$tbl_barcode','$tbl_productid','$tbl_product','$tbl_qty ','$tbl_saleprice','$tbl_totalamount','$date') ";
                    $row = execute($query);
                }

                $outputjson['message']='Data Updated';
                $outputjson['status']=1;
            }else{
                $outputjson['message']='POS Table NOT Updated';
                $outputjson['status']=0;
            } 
        }
    }
    else if($action=="opning_stock"){
        $table =$_POST['product_stock'];
        $date =$_POST['data'];
        
        if($date){
            $query = "SELECT * FROM tbl_opningstock where ( date='$date')";
            $filtered_count =execute($query);
            if(sizeof($filtered_count) > 0)
            {
                $_SESSION['status'] ="0";
                $_SESSION['message']  = "Date Already Exist";
            }else {
                $query = "INSERT INTO tbl_opningstock (date) VALUES ('$date')";
                $row = execute($query);
                $osid = $conn->lastInsertId();
                for ($i = 0;$i < sizeof($table);$i++){
                    $this_item = $table[$i];
                    
                        $tbl_categoryid =$this_item['tbl_categoryid'];
                        $tbl_category =$this_item['tbl_category'];
                        $tbl_productid =$this_item['tbl_productid'];
                        $tbl_product =$this_item['tbl_product'];
                        $tbl_stock = $this_item['tbl_stock'];
        
                        $query = "INSERT INTO tbl_opningstock_detail (osid,categoryid ,category,productid,product, stock) VALUES ('$osid','$tbl_categoryid','$tbl_category','$tbl_productid','$tbl_product','$tbl_stock ')";
                        $row = execute($query);
                        
                        $outputjson['message']='Stock Data Inserted';
                        $outputjson['status']=1;
                }
            }
        }
    }
    else if($action=="store_opning_stock"){
        $table =$_POST['store_stock'];
        $date =$_POST['data'];

        if($date){
            $query = "SELECT * FROM tbl_store_opningstock where ( date='$date')";
            $filtered_count =execute($query);
            if(sizeof($filtered_count) > 0)
            {
                $_SESSION['status'] ="0";
                $_SESSION['message']  = "Date Already Exist";
            }else {
                $query = "INSERT INTO tbl_store_opningstock (date) VALUES ('$date')";
                $row = execute($query);
                $soid = $conn->lastInsertId();
                for ($i = 0;$i < sizeof($table);$i++){
                    $this_item = $table[$i];
                    
                        $tbl_categoryid =$this_item['tbl_categoryid'];
                        $tbl_category =$this_item['tbl_category'];
                        $tbl_productid =$this_item['tbl_productid'];
                        $tbl_product =$this_item['tbl_product'];
                        $tbl_stock = $this_item['tbl_stock'];
        
                        $query = "INSERT INTO tbl_store_opningstock_detail (soid,categoryid ,category,productid,product, stock) VALUES ('$soid','$tbl_categoryid','$tbl_category','$tbl_productid','$tbl_product','$tbl_stock ')";
                        $row = execute($query);
                        
                        $outputjson['message']='Stock Data Inserted';
                        $outputjson['status']=1;
                }
            }
        }
    }
    else if($action=="pos_master"){

        $data =$_POST['data'];
        if($_POST['purchase_table'] !=''){
        
            $table =$_POST['purchase_table'];
            parse_str($data, $values);

            $subtotal = $values['txtsubtotal_id'];
            $txttotal = $values['txttxttotal'];
            $payment_type = $values['r3'];
            $ramount = $values['ramount'];
            $txtpaid = $values['txtpaid'];
            $orderdate     = date('Y-m-d');
            
            if($txtpaid != 0 && $txtpaid !='' ){
            
                $query = "INSERT INTO tbl_invoice (order_date,subtotal,total,payment_type,ramount,paid) VALUES ('$orderdate','$subtotal','$txttotal','$payment_type','$ramount','$txtpaid') ";
                $row=execute($query);
                
                $id = $conn->lastInsertId();

                for ($i = 0;$i < sizeof($table);$i++){
                    $this_item = $table[$i];
                    if($this_item['tbl_stock'] > $this_item['tbl_qty'] )
                    {
                        $tbl_productid =$this_item['tbl_productid'];
                        $tbl_barcode =$this_item['tbl_barcode'];
                        $tbl_product =$this_item['tbl_product'];
                        $tbl_stock = $this_item['tbl_stock'];
                        $tbl_saleprice = $this_item['tbl_saleprice'];
                        $tbl_qty = $this_item['tbl_qty'];
                        $tbl_totalamount = $this_item['tbl_totalamount'];
                        
                        $query = "INSERT INTO tbl_invoice_details (invoice_id,barcode,product_id,product_name, qty,rate,saleprice,order_date) VALUES ('$id','$tbl_barcode','$tbl_productid','$tbl_product','$tbl_qty ','$tbl_saleprice','$tbl_totalamount','$orderdate')";
                        $row = execute($query);

                        $outputjson['message']='POS Table Data Inserted';
                        $outputjson['status']=1;
                        
                    }else{
                        $outputjson['message']='Enter Stock & Qty';
                        $outputjson['status']=0;
                    }
                }
                $outputjson['message']='POS Data Inserted';
                $outputjson['status']=1;
            }else{
                $outputjson['message']='Enter Stock & Qty';
                $outputjson['status']=0;
            }  
        }  
    }else if($action=="approval_stock"){
        
        $id =$_POST['id'];
        if($id ){
            $role=$_SESSION['username'];
            $approvedate= date('Y-m-d');

            $query ="SELECT p.id,p.date,ps.qty,ps.id as pid
                ,ifnull((select sum(s.stock) from warehouse_stock s where s.productid=ps.productid),0) as stock
                FROM tbl_product_request p 
                INNER JOIN tbl_productrequest_detail ps ON ps.pid=p.id 
                WHERE ps.pid=$id";
            $row = execute($query);
            // print_r($row);
            $ischeck=1;
            for ($i=0; $i <sizeof($row); $i++) { 
                $this_item = $row[$i];
                $id =$this_item['id'];
                $qty =$this_item['qty'];
                $stock =$this_item['stock'];
                if($qty > $stock)
                {
                    $ischeck=0;
                }
            }
            if($ischeck == 1){
                $query = "UPDATE tbl_product_request SET is_approv = 1,approv_by = '$role',approv_date='$approvedate'  WHERE id= $id";
                $row=execute($query);

                $outputjson['message']='Stock Approved';
                $outputjson['status']=1;
            }else{
                $outputjson['message']='Stock Not Available';
                $outputjson['status']=0;
            }
        }else{
            $outputjson['message']='Somthing Went Wrong!';
            $outputjson['status']=0;
        }
        
    }
    else if($action=="request_delete"){
        
        $id =$_POST['id'];
        $role=$_SESSION['username'];
        $canceldate= date('Y-m-d');
        if($id){
            $query = "UPDATE tbl_product_request SET is_approv = 2,approv_by = '$role',approv_date='$canceldate'  WHERE id= $id";
            $row=execute($query);
        }
            
        $outputjson['message']='Stock request Cancel';
        $outputjson['status']=1;
    }
    else if($action=="Change_pass"){
        $data =$_POST['data'];
        parse_str($data, $values);

        $id = $values['id'];
        $newpass = $values['txt_newpassword'];
        $renewpass = $values['txt_rnewpassword'];

        if($newpass === $renewpass){
            $query = "UPDATE tbl_users SET password ='$newpass' WHERE id= $id";
            $row=execute($query);

            $outputjson['message']='Password Is Change';
            $outputjson['status']=1;
        }else{
            $outputjson['message']='Password Is Not Match!';
            $outputjson['status']=0;
        }
            
    }
    else if($action=="vender_master_add"){
        $data =$_POST['data'];
        parse_str($data, $values);

        $name = $values['name'];
        $contact = $values['contact'];
        $email = $values['email'];
        $address = $values['address'];

        if($name && $contact){

            $query = "INSERT INTO tbl_vendermaster (name,contect,email,address) VALUES ('$name','$contact','$email','$address') ";
            $row=execute($query);

            $outputjson['message']='Data Inserted';
            $outputjson['status']=1;
        }else{
            $outputjson['message']='Data Not Inserted';
            $outputjson['status']=0;
        }
            
    }
    else if($action=="vender_update"){
        $data =$_POST['data'];
        parse_str($data, $values);

        $id = $values['id'];
        $name = $values['name'];
        $contact = $values['contact'];
        $email = $values['email'];
        $address = $values['address'];

        if($id){

            $query = "UPDATE tbl_vendermaster SET name ='$name' ,contect = '$contact' ,email ='$email',address=' $address' WHERE id= $id ";
            $row=execute($query);

            $outputjson['message']='Data Updated';
            $outputjson['status']=1;
        }else{
            $outputjson['message']='Data Not Updated';
            $outputjson['status']=0;
        }
            
    }
    else if($action=="Username"){
        $data =$_POST['data'];
        parse_str($data, $values);

        $id = $values['id'];
        $email = $values['email'];

        if($id){

            $query = "UPDATE tbl_users SET email ='$email' WHERE id= $id ";
            $row=execute($query);

            $outputjson['message']='Data Updated';
            $outputjson['status']=1;
        }else{
            $outputjson['message']='Data Not Updated';
            $outputjson['status']=0;
        }
            
    }
}
echo json_encode($outputjson);
?>