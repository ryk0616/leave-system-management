<?php session_start();
if (!isset($_SESSION["employee_id"]) || !isset($_SESSION["role"]) ||
$_SESSION["employee_id"] == '' || $_SESSION["role"] != 'EMPLOYEE') {
    exit("SESSIONEXPIRED"); 
}

include '../network.php';
$thedata = $_POST['wholedata'];
$decodethejson = json_decode($thedata);
   
$thedate = $MySQLConnection->real_escape_string($decodethejson->thedate);
$theremarks = $MySQLConnection->real_escape_string($decodethejson->theremarks);

//INSERT DATA   

$date = new DateTime("now", new DateTimeZone('Asia/Manila') );
$date_now = $date->format('Y-m-d');
$time_now = $date->format('H:i:s');
$employeeid = $_SESSION["employee_id"];

$stmt2 = $MySQLConnection->prepare("INSERT INTO `employee_leave_filing` (`leave_id`,`employee_id`,`reason`,`status`,`date_of_leave`,`date_filed`) VALUES (?, ?, ?, ?, ?, ?)");
$leaveid = uniqid() . mt_rand();
$leavestatus = 'PENDING';
$stmt2->bind_param("sissss",$leaveid,$employeeid,$theremarks,$leavestatus,$thedate,$date_now);

if($stmt2->execute()) 
{
    $stmt2->close();
    exit("success");
} 
else 
{
    $stmt2->close();
   exit ("NOTSUCCESS");
}