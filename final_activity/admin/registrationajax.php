<?php 
session_start();
if (!isset($_SESSION["employee_id"]) || !isset($_SESSION["role"]) ||
$_SESSION["employee_id"] == '' || $_SESSION["role"] != 'ADMIN') {
    exit("SESSIONEXPIRED"); 
}

include '../network.php';

$thedata = $_POST['wholedata'];
$decodethejson = json_decode($thedata);

$firstname = $MySQLConnection->real_escape_string($decodethejson->firstname);
$middlename = $MySQLConnection->real_escape_string($decodethejson->middlename);
$lastname = $MySQLConnection->real_escape_string($decodethejson->lastname);
$address = $MySQLConnection->real_escape_string($decodethejson->address);
$status = $MySQLConnection->real_escape_string($decodethejson->status);
$username = $MySQLConnection->real_escape_string($decodethejson->username);
$thepassword = $MySQLConnection->real_escape_string($decodethejson->password);
$salary = $MySQLConnection->real_escape_string($decodethejson->salary);
$leaves = $MySQLConnection->real_escape_string($decodethejson->leaves);

//INCREMENTAL EMPLOYEE ID
$thenewemployee;
$stmt = $MySQLConnection->prepare("SELECT `employee_id` FROM `employee` ORDER BY `employee_id` DESC limit 1");
$stmt->execute();
$stmt->bind_result($employeeid);

if ($stmt->fetch()) 
{
   $thenewemployee = $employeeid + 1;
} 
else 
{
    $thenewemployee = 1;
}
$stmt->close();



    //INSERT FILE 
    if ($_FILES["fileToUpload"]["error"] === UPLOAD_ERR_OK) 
    {
        $targetDir = "uploads/";
        
        // Check if the target directory exists, create it if not
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);  // Recursive directory creation
        }
    
       

        $targetFileName = basename($_FILES["fileToUpload"]["name"]);
        $targetFilePath = $targetDir . $targetFileName;
    
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFilePath)) {
            //MANUNALY ADJUST THE PATH
            $fullFilePath = 'https://'.$_SERVER['SERVER_NAME'] . '/finalactivity/' . $targetFilePath;
        } else {
            echo "Error uploading file.";
        }
    } 
    else {
        echo "Error: " . $_FILES["fileToUpload"]["error"];
    }


    $date = new DateTime("now", new DateTimeZone('Asia/Manila') );
    $date_now = $date->format('Y-m-d');
    $time_now = $date->format('H:i:s');

    $hashedpassword = password_hash($thepassword,PASSWORD_DEFAULT);

    $stmt2 = $MySQLConnection->prepare("INSERT INTO `employee` (`employee_id`,`firstname`, `middlename`, `lastname`, `address`, `status`, `date_hired`,`username`,`password`,`picturepath`) VALUES (? ,?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt2->bind_param("isssssssss", $thenewemployee,$firstname, $middlename, $lastname, $address, $status, $date_now, $username, $hashedpassword,$fullFilePath);
    
    if ($stmt2->execute()) 
    {
        $stmt2->close();
        $stmt3 = $MySQLConnection->prepare("INSERT INTO `employee_benefits` (`salary`,`leaves`,`employee_id`) VALUES (? ,?, ?)");

        $stmt3->bind_param("ssi", $salary,$leaves, $thenewemployee);
        if ($stmt3->execute()) 
        {
            exit("success");
        }
        else 
        {
            exit("NOTSUCCESS");
        }
    } 
    else 
    {
        $stmt2->close();
       exit("NOTSUCCESS");
    }