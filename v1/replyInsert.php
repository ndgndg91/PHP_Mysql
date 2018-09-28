<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
header("Content-Type:text/html;charset=utf-8");

require_once '../includes/DbOperations.php';

$response = array();

	if($_SERVER['REQUEST_METHOD']=='POST'){

		if(isset($_POST['content']) and isset($_POST['email']) and isset($_POST['writing_no']) and isset($_POST['emailOfWriting']) ){

			//operate the data further

			$db = new DbOperations();

			$result = $db->insertToReply($_POST['content'],$_POST['email'],$_POST['writing_no'],$_POST['emailOfWriting']);

			if($result == 1){
				$reply_no = $db->getReply_No($_POST['content'],$_POST['email'],$_POST['writing_no']);
				$response['reply_no'] = $reply_no['reply_no'];
				$response['error'] = false;
				$response['message'] = "댓글이 성공적으로 작성 되었습니다.";
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