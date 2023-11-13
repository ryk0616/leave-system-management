<?php session_start();
if (!isset($_SESSION["employee_id"]) || !isset($_SESSION["role"]) ||
$_SESSION["employee_id"] == '' || $_SESSION["role"] != 'EMPLOYEE') {
    exit("SESSIONEXPIRED"); 
}

    $employeeid = $_SESSION["employee_id"];
    $servername = "localhost";
    $username = "root";
    $password = "";
    $SERVER_DATABASE = "final_activity_kahitsino";
    $MySQLConnection = mysqli_connect($servername, $username, $password, $SERVER_DATABASE);
    
    
    $thecontent = [];

    $stmt = $MySQLConnection->prepare("SELECT `file_name`,`file_path` FROM `employee_documents` WHERE employee_id = ?  ORDER BY `file_name` ASC");

    
    $stmt->bind_param("i", $employeeid);
    $stmt->execute();
    $stmt->bind_result($file_name,$file_path);
    
    while($stmt->fetch()) 
    {
        $itemobject = new \stdClass();
        $itemobject->file_name = $file_name;
        $itemobject->file_path = $file_path;
        

        $thecontent[] = $itemobject;
    }    


    $stmt->close();
    exit(json_encode($thecontent));
   
?>