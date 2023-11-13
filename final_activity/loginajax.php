<?php   session_start();

$servername = "localhost";
$username = "root";
$password = "";
$SERVER_DATABASE = "final_activity_kahitsino";

$MySQLConnection = mysqli_connect($servername, $username, $password, $SERVER_DATABASE);

$whole = $_POST['wholedata'];
$decodethejson = json_decode($whole);


 $username = $MySQLConnection->real_escape_string($decodethejson->username);
 $passwordinput = $MySQLConnection->real_escape_string($decodethejson->password);

 $stmt = $MySQLConnection->prepare("SELECT `employee_id`,`password` FROM `employee` WHERE username = ?");
 $stmt->bind_param("s", $username);
 $stmt->execute();
 $stmt->bind_result($employeeid,$resultpassword);
 
 if ($stmt->fetch()) 
 {
    if(password_verify($passwordinput, $resultpassword))
    {
        $_SESSION["employee_id"] = $employeeid;
        $_SESSION["role"] = 'EMPLOYEE';
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


?>
  