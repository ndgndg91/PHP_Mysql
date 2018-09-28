<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
header("Content-Type:text/html;charset=utf-8");

require_once '../includes/DbOperations.php';

$response = array();

	if($_SERVER['REQUEST_METHOD']=='POST'){

		if(  isset($_POST['writing_no']) ){


			$db = new DbOperations();

			$result = $db->deleteWriting($_POST['writing_no']);

			if($result == 1){
				$response['error'] = false;
				$response['message'] = "글이 성공적으로 삭제 되었습니다.";
				echo json_encode($response,JSON_UNESCAPED_UNICODE);
			}elseif($result ==2){
				$response['error'] = true;
				$response['message'] = "어떤 문제가 발생했어요! 다시 시도 해보세요!";
				echo json_encode($response,JSON_UNESCAPED_UNICODE);
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
	
