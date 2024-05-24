<?php
include_once 'connectdb.php';

$id=$_GET['id'];



$select = $conn->prepare("select * from tbl_invoice_deatils a INNER JOIN tbl_product b ON a.product_id=b.pid where a.invoice_id=$id");
$select->execute();

$row_invoice_details=$select->fetchAll(PDO::FETCH_ASSOC);

$respons = $row_invoice_details;

header('Content-Type: application/json');

echo json_decode($respons);


?>