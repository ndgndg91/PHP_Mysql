<?php //verify.php
error_reporting(E_ALL);
ini_set("display_errors", 1);
header('content-type: text/html; charset=utf-8'); 
 
require_once '../includes/DbOperations.php';
$response = array();

echo $_GET['email'];
if($_SERVER['REQUEST_METHOD']=='GET'){
    if( isset($_GET['email']) ){
      $db = new DbOperations();
      $result = $db->verifyEmail($_GET['email']);
      if($result==1){
        echo "이메일이 인증 되었습니다!!";
      }else{
        echo "에러 발생!!";
      }
    }else{

      $response['error'] = true;
      $response['message'] = "Required fields are missing";
      echo json_encode($response,JSON_UNESCAPED_UNICODE);
    }

  }else{
    $response['error'] = true;
    $response['message'] = "Invalid Request";
    echo json_encode($response,JSON_UNESCAPED_UNICODE);
}

 


