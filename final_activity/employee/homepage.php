<?php   session_start();
if (!isset($_SESSION["employee_id"]) || !isset($_SESSION["role"]) ||
$_SESSION["employee_id"] == '' || $_SESSION["role"] != 'EMPLOYEE') {
    header("Location:../login.php");
    exit(); 
}

$employeeid = $_SESSION["employee_id"];
$servername = "localhost";
$username = "root";
$password = "";
$SERVER_DATABASE = "final_activity_kahitsino";
$MySQLConnection = mysqli_connect($servername, $username, $password, $SERVER_DATABASE);

$stmt = $MySQLConnection->prepare("SELECT `employee`.`firstname`, `employee`.`middlename`, `employee`.`lastname`, `employee`.`status`, `employee`.`date_hired`, `employee`.`picturepath`, `employee_benefits`.`leaves`, `employee_benefits`.`salary`
 FROM `employee`
 INNER JOIN `employee_benefits` ON `employee`.`employee_id` = `employee_benefits`.`employee_id`
 WHERE `employee`.`employee_id` = ?");

$stmt->bind_param("i", $employeeid);
$stmt->execute();
$stmt->bind_result($firstname,$middlename,$lastname,$status,$date_hired,$picturepath,$leaves,$salary);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Homepage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
    
<?php include 'navbar.php' ?>

<div class="p-3">
    <img style="height:150px;width: 150px;display:block;" src='<?php echo $picturepath ?>'>
    <div style="font-size:20px;">Hello, <?php ECHO $firstname.' '; ECHO $middlename.' '; ECHO $lastname;?></div>
    <div>Status: <?php echo $status; ?></div>
    <div>Date hired: <?php echo $date_hired; ?></div>
    <div>Leaves Remaining: <?php echo $leaves ?> </div>
    <div>Salary: <?php echo $salary?> </div>
</div>


</body>
</html>