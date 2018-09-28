<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
header("Content-Type:text/html;charset=utf-8");

require_once '../includes/DbOperations.php';

$response = array();

	if($_SERVER['REQUEST_METHOD']=='POST'){

		if(isset($_POST['email'])){

			$db = new DbOperations();

			$db->alarmInfoByEmail($_POST['email']);


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