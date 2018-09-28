<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);



require_once '../includes/DbOperations.php';
//houk add email_verify issue
include "Sendmail.php";
$sendmail = new Sendmail();

	//houk add email verify issue
	$email = $_POST['email'];

	$response = array();

	if($_SERVER['REQUEST_METHOD']=='POST'){

		if(isset($_POST['email']) and isset($_POST['password'])
			and isset($_POST['name']) and isset($_POST['birthday'])){

			//operate the data further

			$db = new DbOperations();


			$result = $db->createUser($_POST['email'],
				$_POST['name'],
				$_POST['birthday'],
				$_POST['password']);

			if($result == 1){
				$response['error'] = false;
				$response['message'] = "User registerd successfully";

			//houk add email_verify issue
			$to="$email";
			$from="DDatalk_Master";
			$subject="따릉이톡 인증 이메일 입니다.";
			$body="http://ndgndg91.synology.me/~ndgndg91/php/v1/verify.php?email=$email";
			
			$cc_mail="";
			$bcc_mail="";

			//houk add data
			$sendmail->send_mail($to, $from, $subject, $body, $cc_mail, $bcc_mail);

			}elseif($result ==2){
				$response['error'] = true;
				$response['message'] = "Some error occurred please try again";
			}elseif($result==0){
				$response['error'] = true;
				$response['message'] = "It seems you are already registerd, please choose a different email and username";
			}




		}else{

			$response['error'] = true;
			$response['message'] = "Required fields are missing";
		}

	}else{
		$response['error'] = true;
		$response['message'] = "Invalid Request";
	}

	echo json_encode($response);
