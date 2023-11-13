<?php session_start();
if (!isset($_SESSION["employee_id"]) || !isset($_SESSION["role"]) ||
$_SESSION["employee_id"] == '' || $_SESSION["role"] != 'ADMIN') {
    exit("SESSIONEXPIRED"); 
}

    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $SERVER_DATABASE = "final_activity_kahitsino";
    
    $MySQLConnection = mysqli_connect($servername, $username, $password, $SERVER_DATABASE);

    $thedata = $_POST['wholedata'];
    $decodethejson = json_decode($thedata);
    
    $leaveId = $MySQLConnection->real_escape_string($decodethejson->leaveId);
    $thecontent = $MySQLConnection->real_escape_string($decodethejson->thecontent);
    
    // Assuming $thecontent is the new status value (e.g., 'APPROVED')
    // and $employeeId is the employee ID you want to update.
    
    $stmt2 = $MySQLConnection->prepare("UPDATE `employee_leave_filing` SET `status` = ? WHERE `leave_id` = ?");
    $stmt2->bind_param("ss", $thecontent, $leaveId); // Use "si" for string and integer data types
    
    if ($stmt2->execute()) {
        $stmt2->close();
        exit("success");
    } else {
        $stmt2->close();
        exit("NOTSUCCESS");
    }


?>