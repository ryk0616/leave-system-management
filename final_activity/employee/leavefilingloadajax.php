<?php session_start();
if (!isset($_SESSION["employee_id"]) || !isset($_SESSION["role"]) ||
$_SESSION["employee_id"] == '' || $_SESSION["role"] != 'EMPLOYEE') {
    exit("SESSIONEXPIRED"); 
}

include '../network.php';
    
    
    $thecontent = [];
    $employeeid = $_SESSION["employee_id"];
    $stmt = $MySQLConnection->prepare("SELECT `reason`,`status`,`date_of_leave` FROM `employee_leave_filing` WHERE employee_id = ?  ORDER BY `date_of_leave` ASC");

    
    $stmt->bind_param("i", $employeeid);
    $stmt->execute();
    $stmt->bind_result($reason,$status,$date_of_leave);
    
    while($stmt->fetch()) 
    {
        $itemobject = new \stdClass();
        $itemobject->reason = $reason;
        $itemobject->status = $status;
        $itemobject->date_of_leave = $date_of_leave;

        $thecontent[] = $itemobject;
    }    


    $stmt->close();
    exit(json_encode($thecontent));
   
?>