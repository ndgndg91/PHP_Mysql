<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

	class DbOperations{

		private $con;

		function __construct(){

			require_once dirname(__FILE__).'/DbConnect.php';

			$db = new DbConnect();

			$this->con = $db->connect();
		}

		/*CRUD -> C -> CREATE */
		public function createUser($email,$name,$birthday,$pass){
			if($this->isUserExist($name,$email)){
				return 0;
			}else{
				$password =md5($pass);
				$stmt = $this->con->prepare("INSERT INTO `user` (`email`, `name`, `birthday`,`exp`,`level`, `passwd`) VALUES (?, ?, ?,0,1, ?);");
				$stmt->bind_param("ssss",$email,$name,$birthday,$password);

				if($stmt->execute()){
					return 1;
				}else{
					return 2;
				}
			}
		}

		public function userLogin($email,$pass){
			$password = md5($pass);
			$stmt = $this->con->prepare("SELECT email FROM user WHERE email = ? AND passwd = ?");
			$stmt->bind_param("ss",$email,$password);
			$stmt->execute();
			$stmt->store_result();
			return $stmt->num_rows > 0;
		}

		//로그인시 세션 유저정보들
		public function getUserByEmail($email){
			$stmt = $this->con->prepare("SELECT * FROM user WHERE email =?");
			$stmt->bind_param("s",$email);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc();
		}


		private function isUserExist($name, $email){
			$stmt = $this->con->prepare("SELECT email FROM user WHERE name = ? OR email = ?");
			$stmt->bind_param("ss",$name,$email);
			$stmt->execute();
			$stmt->store_result();
			return $stmt->num_rows > 0;
		}


		// 메인 페이지 리스트뷰에 뿌려주기위함
		public function writingInfo(){
			$sql = "SELECT * FROM writing AS w INNER JOIN user AS u WHERE w.email=u.email ORDER BY writing_no DESC";
			$result = $this->con->query($sql);

			if ($result->num_rows > 0) {

   			// output data of each row
				while($row[] = $result->fetch_assoc()) {

       				$json = json_encode($row,JSON_UNESCAPED_UNICODE);

    			}
			} else {
    			echo "0 results";
			}
				echo $json;
		}

		//함께타요 버튼 관련 기능
		public function isExistRow($writing_no,$email){
			$stmt = $this->con->prepare("SELECT * FROM isChecked_with WHERE writing_no = ? AND email = ?");
			$stmt->bind_param("ss",$writing_no,$email);
			$stmt->execute();
			$stmt->store_result();
			return $stmt->num_rows > 0;

		}

		public function isChecked_withInfo($writing_no,$email,$emailOfWriting){
			if($this->isExistRow($writing_no,$email)){
				$stmt = $this->con->prepare("DELETE FROM `isChecked_with` WHERE `isChecked_with`.`writing_no` = ? AND `isChecked_with`.`email` = ?;");
				$stmt->bind_param("ss",$writing_no,$email);
				$stmt->execute();
				$this->writingDecreaseByOne($writing_no);
				$this->alarmDelete($writing_no,$email,$emailOfWriting);
				return 0;
			}else{
				$stmt = $this->con->prepare("INSERT INTO `isChecked_with` (`writing_no`, `email`, `isChecked`) VALUES (?, ?, 1);");
				$stmt->bind_param("ss",$writing_no,$email);

				if($stmt->execute()){
					$this->writingIncreaseByOne($writing_no);
					$this->alarmInsert($writing_no,$email,$emailOfWriting);
					return 1;
				}else{
					return 2;
				}
			}
		}
		public function alarmInsert($writing_no,$email,$emailOfWriting){
			$stmt = $this->con->prepare("INSERT INTO `alarm` (`alarm_no`, `content`, `date`, `triger_email`, `writing_no`,`email`) VALUES (NULL, '함께타요를 눌렀습니다.', TIMESTAMPADD(HOUR, 9, CURRENT_TIMESTAMP), ?, ?,?);");
			$stmt->bind_param("sss",$email,$writing_no,$emailOfWriting);
			$stmt->execute();

		}
		public function alarmDelete($writing_no,$email,$emailOfWriting){
			$stmt = $this->con->prepare("DELETE FROM `alarm` WHERE `writing_no`=? AND `triger_email` =? AND
			`email` = ? ;");
			$stmt->bind_param("sss",$writing_no,$email,$emailOfWriting);
			$stmt->execute();
		}

		public function writingIncreaseByOne($writing_no){
			$stmt = $this->con->prepare("UPDATE `writing` SET with_cnt=with_cnt+1 WHERE `writing_no`=?");
			$stmt->bind_param("s",$writing_no);
			$stmt->execute();
			return 1;
		}
		public function writingDecreaseByOne($writing_no){
			$stmt = $this->con->prepare("UPDATE `writing` SET with_cnt=with_cnt-1 WHERE `writing_no`=?");
			$stmt->bind_param("s",$writing_no);
			$stmt->execute();
			return 1;
		}

		public function getWith_cnt($writing_no){
			$stmt = $this->con->prepare("SELECT with_cnt FROM writing WHERE writing_no =?");
			$stmt->bind_param("s",$writing_no);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc();
		}

		//댓글을 뿌려주자
		public function replyInfoByWriting_no($writing_no){
			$sql = "SELECT * FROM reply WHERE writing_no = '$writing_no'";
			$result = $this->con->query($sql);

			if ($result->num_rows > 0) {

   			// output data of each row
    			while($row[] = $result->fetch_assoc()) {

       				$json = json_encode($row,JSON_UNESCAPED_UNICODE);


    			}
    			echo $json;
			} else {
    			echo "0 results";
			}
				
		}
		//댓글 받아서 insert하기
		public function insertToReply($content,$email,$writing_no,$emailOfWriting){
			$stmt = $this->con->prepare("INSERT INTO `reply` (`reply_no`, `content`, `date`, `email`, `writing_no`) VALUES (NULL, ?, TIMESTAMPADD(HOUR, 9, CURRENT_TIMESTAMP), ?, ?);");
			$stmt->bind_param("sss",$content,$email,$writing_no);
			if($stmt->execute()){
					$stmt2 = $this->con->prepare("UPDATE `writing` SET reply_cnt=reply_cnt+1 WHERE `writing_no`=?");
					$stmt2->bind_param("s",$writing_no);
					if($stmt2->execute()){
						$stmt3=$this->con->prepare("INSERT INTO `alarm` (`alarm_no`, `content`, `date`, `triger_email`, `writing_no`,`email`) VALUES (NULL, '댓글을 달았습니다.', TIMESTAMPADD(HOUR, 9, CURRENT_TIMESTAMP), ?, ?,?);");
						$stmt3->bind_param("sss",$email,$writing_no,$emailOfWriting);
						if($stmt3->execute()){
							return 1;
						}else{
							return 2;
						}							
					}else{
						return 2;
					}
			}else{
				return 2;
			}

		}
		//댓글 키값 받기
		public function getReply_No($content,$email,$writing_no){
			$stmt = $this->con->prepare("SELECT reply_no FROM reply WHERE content =? AND
							email =? AND writing_no =?");
			$stmt->bind_param("sss",$content,$email,$writing_no);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc();
		}

		//댓글 삭제하기
		public function deleteReplyByReplyNo($reply_no,$writing_no){
			$stmt = $this->con->prepare("DELETE FROM `reply` WHERE `reply`.`reply_no` = ?");
			$stmt->bind_param("s",$reply_no);

			if($stmt->execute()){
				$stmt2 = $this->con->prepare("UPDATE `writing` SET reply_cnt=reply_cnt-1 WHERE `writing_no`=?");
				$stmt2->bind_param("s",$writing_no);
				if($stmt2->execute()){
					$stmt3 = $this->con->prepare("DELETE FROM `alarm` WHERE `alarm`.`writing_no`=?");
					$stmt3->bind_param("s",$writing_no);
					if($stmt3->execute()){
						return 1;
					}else{
						return 2;
					}
											
				}else{
					return 2;
				}						
			}else{
				return 2;
			}
		}

		//email 인증
		public function verifyEmail($email){
			$stmt = $this->con->prepare("UPDATE `user` SET `Verify` = 'Y' WHERE `user`.`email` = ?");
			$stmt->bind_param("s",$email);
			if($stmt->execute()){
				return 1;
			}else{
				return 2;
			}
		}

		//글 삭제하기
		public function deleteWriting($writing_no){
			$stmt = $this->con->prepare("DELETE FROM `writing` WHERE `writing`.`writing_no` = ?");
			$stmt->bind_param("s",$writing_no);
			if($stmt->execute()){
				return 1;
			}else{
				return 2;
			}
		}

		//알람 정보 가져오기
		public function alarmInfoByEmail($email){
			$sql = "SELECT * FROM alarm AS a INNER JOIN user AS b WHERE a.triger_email =b.email AND a.email = '$email'";
			$result = $this->con->query($sql);

			if ($result->num_rows > 0) {

   			// output data of each row
    			while($row[] = $result->fetch_assoc()) {

       				$json = json_encode($row,JSON_UNESCAPED_UNICODE);


    			}
    			echo $json;
			} else {
    			echo "0 results";
			}
		}

	}