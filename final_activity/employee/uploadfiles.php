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

<div class="p-4">
    <div><strong>Employee ID:</strong> <?php ECHO $employeeid; ?></div>
    <div class="mb-5"><strong>Employee:</strong> <?php ECHO $firstname.' '; ECHO $middlename.' '; ECHO $lastname; ?></div>
    <div class="row">
     
        <div class="col-md-6 text-center" style="border-right:solid 1px;height:300px;"> 
             <div class="text-center"><strong>ADD YOUR NEW FILES HERE!</strong></div>
             
            <input class="mt-4" type="file" id="fileToUpload" multiple>

            <div id="progressWrapper" style="display: none;">
                <div id="progressBars"></div>
            </div>
            <div id="result"></div>
        </div>


        <div class="col-md-6"> 
             <div class="text-center"><strong>YOUR CURRENT FILES</strong></div>
             <div id="thecontent"></div>
        </div>
    </div>
</div>

<script type="application/javascript">
        const fileInput = document.getElementById("fileToUpload");
        const progressWrapper = document.getElementById("progressWrapper");
        const progressBars = document.getElementById("progressBars");
        const result = document.getElementById("result");

        fileInput.addEventListener("change", function() {

            const files = fileInput.files;
            if (files.length > 0) {
                progressWrapper.style.display = "block";
                progressBars.innerHTML = ""; 

                const thelenght = files.length;
                let startofcursive = 0;

                function thecursiveupload(thestarter,theender)
                {
                   
                    if(thestarter < theender) 
                    {
                    const file = files[thestarter];

                    const progressBarHTML = `<div class="text-start">File ${thestarter}</div><div class="progress mt-2">
                            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>`;  

                    progressBars.innerHTML += progressBarHTML;

                    const formData = new FormData();
                    formData.append("fileToUpload", file);
                    const xhr = new XMLHttpRequest();


                    xhr.upload.addEventListener("progress", function(event) {
                    if (event.lengthComputable) {
                        const percent = (event.loaded / event.total) * 100;
                        const lastProgress = document.getElementsByClassName('progress-bar')[thestarter];

                       lastProgress.style.width = percent + "%";
                        lastProgress.setAttribute("aria-valuenow", percent);
                    }
                });

                    xhr.onreadystatechange = function() 
                    {                 
                        if (xhr.readyState == 4 && xhr.status == 200) 
                        { 
                            if(this.responseText == "SESSIONEXPIRED")
                            {location.reload();}

                              result.innerHTML = xhr.responseText; 
                              startofcursive++;
                              thecursiveupload(startofcursive,thelenght);
                              loadallfiles();
                        } 
                        else {result.innerHTML = "Error uploading file.";}
                    };

                    xhr.open("POST", "uploadfilesajax.php?PH=" + Date.now(), true);
                    xhr.send(formData);
                    }
                    
                }
                thecursiveupload(startofcursive,thelenght);
            }
        });


    loadallfiles = () => 
    {
         document.getElementById('thecontent').innerHTML = '';
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                    if(this.responseText == "SESSIONEXPIRED")
                            {location.reload();}

                    let results_object = JSON.parse(this.responseText);
                    results_object.forEach((res) => {

                    document.getElementById('thecontent').innerHTML += `
                        <a href="${res.file_path}" download target="_blank">
                           <div class="border p-2 mt-1 rounded">${res.file_name}</div>
                        </a>`;
                     
                });
                }
            };
            xmlhttp.open("POST", "uploadfilesloadajax.php?PH=" + Date.now(), true);
            xmlhttp.send();
    }

    window.addEventListener("load", () => {
    <?php if($_SESSION["employee_id"] == '')
    {header("Location:../login.html");}
    if($_SESSION["role"] != 'EMPLOYEE')
    {header("Location:../login.html");}?>
     
     loadallfiles();
    });
    </script>

</body>
</html>
