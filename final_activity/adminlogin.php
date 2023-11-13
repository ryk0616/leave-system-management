<?php session_start();

if(!empty($_SESSION["role"]))
{
  if($_SESSION["role"] == 'ADMIN')
  {
    header("Location: https://".$_SERVER['SERVER_NAME']."/final_activity/admin/homepage.php");
  }
  else 
  {
    header("Location: https://".$_SERVER['SERVER_NAME']."/final_activity/employee/homepage.php");
  }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN LOGIN FORM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <!--SWEET ALERT-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center mb-5">
          <div class="col-lg-6 col-md-8">
            <div class="card shadow">
              <div class="card-header">
                <h4>Admin Login Form</h4>
              </div>
              <div class="card-body">
                <form id="theform"> 
            
                  <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username">
                  </div>

                  <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password">
                  </div>

                  <button type="submit" class="btn btn-primary">Login</button>

                </form>
              </div>
            </div>
          </div>
        </div>
      </div>   
      
      <script type="application/javascript">
        const theformtosubmit = document.getElementById('theform');
            theformtosubmit.addEventListener('submit', function(event) {
                event.preventDefault();
                const username = document.getElementById('username').value; 
                const password = document.getElementById('password').value;
              
              
                if(username == '' || password == '')
                {
                      Swal.fire(
                    'Input Incomplete!',
                    'Please complete the form',
                    'warning');
                }
                else 
                {
                  
                    let thedata = {
                    username: username,
                    password: password
                    };
            
                    let FDa = new FormData();
                    FDa.append("wholedata", JSON.stringify(thedata));                
                    let xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                       
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                        {
                          
                          if(this.responseText == "loginsuccess")
                          {
                            window.location.href = 'admin/homepage.php';
                          }

                          else 
                          {
                              Swal.fire(
                            'No user Exist!',
                            'Wrong username or password',
                            'error');
                          }
                      
                        }
                    };
                    xmlhttp.open("POST", "adminloginajax.php?PH=" + Date.now(), true);
                    xmlhttp.send(FDa);
                }
              
             
            });
      </script>
</body>
</html>