<?php session_start();
if (!isset($_SESSION["employee_id"]) || !isset($_SESSION["role"]) ||
$_SESSION["employee_id"] == '' || $_SESSION["role"] != 'EMPLOYEE') {
    exit("SESSIONEXPIRED"); 
}



if ($_FILES["fileToUpload"]["error"] === UPLOAD_ERR_OK) 
{
    $subdirectory = "employeefiles/".$_SESSION["employee_id"]."/";
    if (!file_exists($subdirectory)) {
        mkdir($subdirectory, 0777, true);  // Recursive directory creation
    }
    $targetFile = $subdirectory.uniqid().basename($_FILES["fileToUpload"]["name"]);

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) 
    {
                $date = new DateTime("now", new DateTimeZone('Asia/Manila') );
                $date_now = $date->format('Y-m-d');
                $time_now = $date->format('H:i:s');

                $servername = "localhost";
                $username = "root";
                $password = "";
                $SERVER_DATABASE = "final_activity_kahitsino";

                $MySQLConnection = mysqli_connect($servername, $username, $password, $SERVER_DATABASE);

                $stmt2 = $MySQLConnection->prepare("INSERT INTO `employee_documents` (`employee_id`,`file_name`,`file_path`) VALUES (? ,?, ?)");

                 $employee_id = $_SESSION["employee_id"];   
                 $thefilename = $_FILES["fileToUpload"]["name"];

                 //CHECK THE PATH
                 $fullFilePath = 'https://'.$_SERVER['SERVER_NAME'] . '/kahitsinoprogrammer/finalactivity/employee/' . $targetFile;


                $stmt2->bind_param("iss", $employee_id,$thefilename,$fullFilePath);

                if ($stmt2->execute()) 
                {
                    $stmt2->close();
                    exit("SUCCESS");
                } 
                else 
                {
                    $stmt2->close();
                    exit("NOTSUCCESS");
                }
    } 
    else {
        exit("Error uploading file");
    }
}
 else 
 {
    exit("Error: " . $_FILES["fileToUpload"]["error"]);
 }





?>