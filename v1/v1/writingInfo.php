<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
header("Content-Type:text/html;charset=utf-8");

require_once '../includes/DbOperations.php';

$db = new DbOperations();

$db->writingInfo();


// //This script is designed by Android-Examples.com
// //Define your host here.
// $servername = "localhost";
// //Define your database username here.
// $username = "root";
// //Define your database password here.
// $password = "tlrtm6tpstm";
// //Define your database name here.
// $dbname = "ddatalk";

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);
// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// } 
// mysqli_set_charset($conn,"utf8");
// $sql = "SELECT * FROM writing";
// $result = $conn->query($sql);

// if ($result->num_rows > 0) {

//     // output data of each row
//     while($row[] = $result->fetch_assoc()) {

//        $json = json_encode($row,JSON_UNESCAPED_UNICODE);


//     }
// } else {
//     echo "0 results";
// }
// echo $json;
// $conn->close(); 
// 
