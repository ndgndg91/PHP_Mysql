<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
header("Content-Type:text/html;charset=utf-8");

require_once '../includes/DbOperations.php';


$response = array();

	if($_SERVER['REQUEST_METHOD']=='POST'){

		if(isset($_POST['writing_no']) and isset($_POST['email']) and isset($_POST['emailOfWriting']) ){

			//operate the data further

			$db = new DbOperations();

			$result = $db->isChecked_withInfo($_POST['writing_no'],$_POST['email'],$_POST['emailOfWriting']);

			if($result == 1){
				$result_writing_no = $db->getWith_cnt($_POST['writing_no']);

				$response['with_cnt']=$result_writing_no['with_cnt'];
				$response['error'] = false;
				$response['message'] = "처음 누르는 거네요.";
			}elseif($result ==2){
				$response['error'] = true;
				$response['message'] = "Some error occurred please try again";
			}elseif($result==0){
				$result_writing_no = $db->getWith_cnt($_POST['writing_no']);

				$response['with_cnt']=$result_writing_no['with_cnt'];
				$response['error'] = true;
				$response['message'] = "It seems you are already Clicked";
			}




		}else{

			$response['error'] = true;
			$response['message'] = "Required fields are missing";
		}

	}else{
		$response['error'] = true;
		$response['message'] = "Invalid Request";
	}

	echo json_encode($response,JSON_UNESCAPED_UNICODE);