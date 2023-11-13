<?php   session_start();
include 'network.php';

$whole = $_POST['wholedata'];
$decodethejson = json_decode($whole);

$passwordinput = $MySQLConnection->real_escape_string($decodethejson->password);

$stmt = $MySQLConnection->prepare("SELECT `password` FROM `admin`");
$stmt->execute();
$stmt->bind_result($resultpassword);

if ($stmt->fetch()) 
{
   if(password_verify($passwordinput, $resultpassword))
   {
       $_SESSION["employee_id"] = 'ADMIN';
       $_SESSION["role"] = 'ADMIN';
       exit("loginsuccess");
   }
   else 
   {
       exit("nouserexist");
   }
} 
else 
{
    exit("nouserexist");
}
$stmt->close();
