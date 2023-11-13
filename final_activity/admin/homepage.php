<?php  session_start();
if (!isset($_SESSION["employee_id"]) || !isset($_SESSION["role"]) 
|| $_SESSION["employee_id"] == '' || $_SESSION["role"] != 'ADMIN') 
{   
    header("Location:../adminlogin.php");
    exit(); 
}

include '../network.php';

$stmt = $MySQLConnection->prepare("SELECT firstname,middlename,lastname
 FROM admin");

$stmt->execute();
$stmt->bind_result($firstname,$middlename,$lastname);
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
    <div style="font-size:20px;">Hello, <?php ECHO $firstname.' '; ECHO $middlename.' '; ECHO $lastname;?></div>
</div>


</body>
</html>

