<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);


require_once '../includes/Sendmail.php';


$sendmail = new Sendmail();

$to="ndgndg91@gmail.com"; 
$from="Master";
$subject="메일 제목입니다.";
$body="메일 내용입니다.";
$cc_mail="";
$bcc_mail="";
 
/* 메일 보내기 */
$sendmail->send_mail($to, $from, $subject, $body,$cc_mail,$bcc_mail)
?>


