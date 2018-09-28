<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
header("Content-Type:text/html;charset=utf-8");

require_once '../includes/DbOperations.php';





$response = array();

	if($_SERVER['REQUEST_METHOD']=='POST'){

		if(isset($_POST['writing_no']) ){

			//operate the data further

			$db = new DbOperations();

			$db->replyInfoByWriting_no($_POST['writing_no']);

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

	