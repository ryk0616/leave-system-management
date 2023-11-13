<?php 

include 'network.php';

$stmt2 = $MySQLConnection->prepare("INSERT INTO `admin` (`username`,`password`, `firstname`, `middlename`, `lastname`) VALUES (? ,?, ?, ?, ?)");

$username = 'admin';
$thepassword = '12345';
$thefirstname = 'Allen';
$themiddlename = 'Dacanay';
$thelastname = 'Young';

$hashedpassword = password_hash($thepassword,PASSWORD_DEFAULT);

$stmt2->bind_param("sssss", $username, $hashedpassword,$thefirstname, $themiddlename, $thelastname);

if ($stmt2->execute()) 
{
    $stmt2->close();
    ECHO("success");
}
else 
{
    $stmt2->close();
    ECHO("NOTSUCCESS");
}