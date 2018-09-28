<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once '../includes/DbOperations.php';


$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
	if(isset($_POST['email']) and isset($_POST['password']) ){
		$db = new DbOperations();

		if($db->userLogin($_POST['email'], $_POST['password']) ){
			$user = $db->getUserByEmail($_POST['email']);
			$response['error'] = false;
			$response['email'] = $user['email'];
			$response['name'] = $user['name'];
			$response['birthday'] = $user['birthday'];
			$response['exp'] = $user['exp'];
			$response['level'] = $user['level'];
			$response['Verify'] = $user['Verify'];
			$response['userimg'] = $user['userimg'];
		}else{
			$response['error'] = true;
			$response['message'] = "Invalid username or password";
		}
	}else{
		$response['error'] = true;
		$response['message'] = "Required fields are missing";
	}
}

echo json_encode($response);