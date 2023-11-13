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
      <!--SWEET ALERT-->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
<?php include 'navbar.php' ?>
<div class="p-4">
    <div><strong>Employee ID:</strong> <?php ECHO $employeeid; ?></div>
        <div><strong>Employee:</strong> <?php ECHO $firstname.' '; ECHO $middlename.' '; ECHO $lastname; ?></div>
        <div class="mb-5"><strong>Remaining Leave:</strong><span id="remainingleavespace"></span></div>
      
        <div class="row">
        
            <div class="col-md-6" style="border-right:solid 1px;height:300px;"> 
                <div class="text-center"><strong>FILE A LEAVE</strong></div>
                <label for="thedate" class="mt-4">DATE OF LEAVE</label>
                <input type="date" class="form-control" id="thedate">
                <label for="theremarks" class="mt-4">Remarks</label>
                <textarea id="theremarks" class="form-control" style="resize:none;"></textarea>
                <div class="text-end"><button id="submitthebutton" class="btn btn-primary mt-2" style="width:100px;">File</button></div>
            </div>


            <div class="col-md-6"> 
                <div class="text-center"><strong>LEAVES FILED</strong></div>

                <div><strong>PENDING</strong></div>
                <div id="spaceforpending" class="text-start"></div>
                <hr/>
                <div><strong>APPROVED</strong></div>
                <div id="spaceforapproved"></div>
                <hr/>
                <div><strong>REJECTED</strong></div>
                <div id="spaceforrejected"></div>
            </div>
        </div>
</div>

<script type="application/javascript">

            const thebutton = document.getElementById('submitthebutton');
            thebutton.addEventListener('click', function(event) {
               
                const thedate = document.getElementById('thedate').value; 
                const theremarks = document.getElementById('theremarks').value;
                
               
                
                if(thedate == '' || theremarks == '' )
                {
                      Swal.fire(
                    'Input Incomplete!',
                    'Please complete the form',
                    'warning')
                }
                else 
                {
                  
                    let thedata = {
                        thedate: thedate,
                        theremarks: theremarks};
            
                    let FDa = new FormData();
                    FDa.append("wholedata", JSON.stringify(thedata));
                    let xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                       
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                        {
                            if(this.responseText == "SESSIONEXPIRED")
                            {location.reload();}
                            
                          if(this.responseText == "success")
                          {
                            Swal.fire(
                          'Registration Success!',
                          '',
                          'success');
                          fetchfiledleave();
                          }

                          else 
                          {
                              Swal.fire(
                            'Something is error!',
                            'Please contact system administrator',
                            'error');
                          }
                      
                        }
                    };
                    xmlhttp.open("POST", "leavefilingajax.php?PH=" + Date.now(), true);
                    xmlhttp.send(FDa);
                }
              
             
            });





fetchfiledleave = () => 
{   
    document.getElementById('spaceforpending').innerHTML = '';
    document.getElementById('spaceforapproved').innerHTML = '';
    document.getElementById('spaceforrejected').innerHTML = '';
    let counterofapprove = 0;

            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
              
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    if(this.responseText == "SESSIONEXPIRED")
                            {location.reload();}
                    let results_object = JSON.parse(this.responseText);
                    results_object.forEach((res) => {

                     
                   
                    if(res.status == 'PENDING')    
                    {
                        document.getElementById('spaceforpending').innerHTML += `
                           <div class="text-secondary"><span>${res.date_of_leave}</span> - ${res.reason}</div>
                       `;
                    }

                    else if(res.status == 'APPROVE')
                    {
                        document.getElementById('spaceforapproved').innerHTML += `
                           <div class="text-secondary"><span>${res.date_of_leave}</span> - ${res.reason}</div>
                       `;
                        counterofapprove++;
                    }

                  else if(res.status == 'REJECT')
                    {
                        document.getElementById('spaceforrejected').innerHTML += `
                           <div class="text-secondary"><span>${res.date_of_leave}</span> - ${res.reason}</div>
                       `;

                    }
                   
                });
                        let remainingleave = <?php echo $leaves?> - counterofapprove;
                        document.getElementById('remainingleavespace').innerHTML = ' '+remainingleave;
                }
            };
            xmlhttp.open("POST", "leavefilingloadajax.php?PH=" + Date.now(), true);
            xmlhttp.send();
}
fetchfiledleave();
</script>

</body>
</html>