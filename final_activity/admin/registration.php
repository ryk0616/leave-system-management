<?php   session_start();
if (!isset($_SESSION["employee_id"]) || !isset($_SESSION["role"]) ||
$_SESSION["employee_id"] == '' || $_SESSION["role"] != 'ADMIN') {
  header("Location:../adminlogin.php");
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMPLOYEE REGISTRATION FORM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <!--SWEET ALERT-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php include 'navbar.php' ?>
    <div class="container mt-5">
        <div class="row justify-content-center mb-5">
          <div class="col-lg-6 col-md-8">
            <div class="card">
              <div class="card-header">
                <h4>Employee Registration Form</h4>
              </div>
              <div class="card-body">
                <form id="theform">
                    <div class="mb-3">
                        <img class="border" id="filetopreview" style="height:150px;width: 150px;display:block;">
                        <input class="mt-2" type="file" id="fileToUpload">
                    </div>

                  <div class="mb-3">
                    <label for="firstname" class="form-label">Firstname</label>
                    <input type="text" class="form-control" id="firstname">
                  </div>

                  <div class="mb-3">
                    <label for="middlename" class="form-label">Middlename</label>
                    <input type="text" class="form-control" id="middlename">
                  </div>

                  <div class="mb-3">
                    <label for="lastname" class="form-label">Lastname</label>
                    <input type="text" class="form-control" id="lastname">
                  </div>
                  
                  <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea id="address" class="form-control" style="resize:none;"></textarea>
                  </div>

                  <div class="mb-3">
                    <label for="status" class="form-label">Employee Status</label>
                   <select id="status" class="form-select">
                        <option value="regular">Regular</option>
                        <option value="Probationary">Probationary</option>
                        <option value="Contractual">Contractual</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username">
                  </div>

                  <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password">
                  </div>

                  <hr>
                  <div><strong>BENEFITS</strong></div>
                  <div class="mb-3">
                    <label for="salary" class="form-label">Salary</label>
                    <input type="number" class="form-control" id="salary">
                  </div>

                  <div class="mb-3">
                    <label for="leaves" class="form-label">Leaves</label>
                    <input type="number" class="form-control" id="leaves">
                  </div>

                  <button type="submit" class="btn btn-primary">Register</button>

                  <div id="progressWrapper">
                    <progress id="progressBar" max="100" value="0"></progress>
                    <p id="progressStatus">0%</p>
                </div>
                <div id="result"></div>

                </form>
              </div>
            </div>
          </div>
        </div>
      </div>    


      <script type="application/javascript">
        const fileInput = document.getElementById('fileToUpload');
        const previewImage = document.getElementById('filetopreview');

        fileInput.addEventListener('change', function() {
                const file = fileInput.files[0];
                if (file) 
                {
                    
                  //https://developer.mozilla.org/en-US/docs/Web/API/FileReader
                    const reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = function() {
                        previewImage.src = reader.result;
                    };
                    
                } 
                else {
            
                    previewImage.src = '';
                }
            });


            const progressBar = document.getElementById("progressBar");
            const progressStatus = document.getElementById("progressStatus");
            const result = document.getElementById("result");

            const theformtosubmit = document.getElementById('theform');
            theformtosubmit.addEventListener('submit', function(event) {
                event.preventDefault();
                const firstname = document.getElementById('firstname').value; 
                const middlename = document.getElementById('middlename').value;
                const lastname = document.getElementById('lastname').value;
                const address = document.getElementById('address').value;
                const status = document.getElementById('status').value;
                const username = document.getElementById('username').value;
                const password = document.getElementById('password').value;
                const fileToUpload = document.getElementById('fileToUpload').value;
                const file = document.getElementById('fileToUpload').files[0];

                const salary = document.getElementById('salary').value;
                const leaves = document.getElementById('leaves').value;
              
                if(firstname == '' || middlename == '' || lastname == '' || address == '' || username == '' || password == '' || fileToUpload == '')
                {
                      Swal.fire(
                    'Input Incomplete!',
                    'Please complete the form',
                    'warning')
                }
                else 
                {
                  
                    let thedata = {
                    firstname: firstname,
                    middlename: middlename,
                    lastname: lastname,
                    address: address,
                    status: status,
                    username: username,
                    password: password,
                    salary: salary, 
                    leaves: leaves
                    };
            
                    let FDa = new FormData();
                    FDa.append("wholedata", JSON.stringify(thedata));
                    FDa.append("fileToUpload", file);
                    let xmlhttp = new XMLHttpRequest();


                        //PROGRESS BAR
                      xmlhttp.upload.addEventListener("progress", function(event) {
                        if(this.responseText == "SESSIONEXPIRED")
                            {location.reload();}
                        if (event.lengthComputable) {
                            var percent = (event.loaded / event.total) * 100;
                            progressBar.value = percent;
                            progressStatus.textContent = percent + "%";
                        }
                     })



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
                          'success')
                          }

                          else 
                          {
                              Swal.fire(
                            'Something is error!',
                            'Please contact system administrator',
                            'error')
                          }
                      
                        }
                    };
                    xmlhttp.open("POST", "registrationajax.php?PH=" + Date.now(), true);
                    xmlhttp.send(FDa);
                }
              
             
            });

      </script>

</body>
</html>