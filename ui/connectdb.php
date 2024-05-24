<?php
/* $pdo = mysqli_connect('localhost', 'root', '', 'pos_barcode');

if (!$pdo) {
    die('Connection Failed'); 
} */
$servername='localhost';
$username='root';
$dbname='pos_barcode';
$password='';
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully";
  } catch(PDOException $e) {
    //echo "Connection failed: " . $e->getMessage();
  }

$admin="Admin";
$amploye="User";
$warehouse="manager";


function getautoid($tblname){
    global $conn;

    $query="SELECT max(id) as id FROM $tblname ";
    $row=execute($query);
    $id=$row['id']+1;

   return $id;
} 
function execute($query)
{
    global $conn;
    $output = array();
    try {
        global $gh, $last_query;
        $query = str_replace("\0", "", $query);
        $last_query = $query;
        // $result = array();
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->execute();
            
            // $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
           $output = $stmt->fetchAll(PDO::FETCH_ASSOC);
                // $output[] = $row;
            
            // $stmt->close();
        } else {
            // $conn->error($conn->_mysqli->error);
        }
    } catch (Exception $e) {
       
        $gh->Log($e);
    }

    return $output;
}
?>