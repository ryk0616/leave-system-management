<?php session_start();
if (!isset($_SESSION["employee_id"]) || !isset($_SESSION["role"]) ||
$_SESSION["employee_id"] == '' || $_SESSION["role"] != 'ADMIN') {
    exit("SESSIONEXPIRED"); 
}

    $employeeid = $_SESSION["employee_id"];
    $servername = "localhost";
    $username = "root";
    $password = "";
    $SERVER_DATABASE = "final_activity_kahitsino";
    $MySQLConnection = mysqli_connect($servername, $username, $password, $SERVER_DATABASE);
    
    
    $thecontent = [];

    $stmt = $MySQLConnection->prepare("SELECT employee.firstname, employee.middlename, employee.lastname, employee_leave_filing.reason, employee_leave_filing.date_of_leave, employee_leave_filing.date_filed, employee_leave_filing.employee_id,employee_leave_filing.leave_id
    FROM employee_leave_filing 
    INNER JOIN employee ON employee.employee_id = employee_leave_filing.employee_id
    WHERE employee_leave_filing.status = ? 
    ORDER BY employee_leave_filing.date_of_leave ASC");

    $thestatus = 'PENDING';
    $stmt->bind_param("s", $thestatus);
    $stmt->execute();
    $stmt->bind_result($firstname, $middlename, $lastname,$reason,$date_of_leave,$date_filed,$employeeid,$leaveid);
    
    while($stmt->fetch()) 
    {
        $itemobject = new \stdClass();


        $itemobject->firstname = $firstname;
        $itemobject->middlename = $middlename;
        $itemobject->lastname = $lastname;
        $itemobject->reason = $reason;
        $itemobject->date_of_leave = $date_of_leave;
        $itemobject->date_filed = $date_filed;
        $itemobject->employeeid = $employeeid;
        $itemobject->leaveid = $leaveid;

        $thecontent[] = $itemobject;
    }    
    $stmt->close();
    exit(json_encode($thecontent));
   
?>