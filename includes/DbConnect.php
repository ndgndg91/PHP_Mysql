<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

	class DbConnect{
		private $con;


		function __construct(){

		}

		function connect(){
			include_once dirname(__FILE__).'/Constants.php';
			$this->con = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
			mysqli_set_charset($this->con,"utf8");
			if(mysqli_connect_errno()){
				echo "Failed to connect with database".mysqli_connect_err();
			}

			return $this->con;
		}
	}