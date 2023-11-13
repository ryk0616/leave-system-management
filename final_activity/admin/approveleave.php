<?php   session_start();
if (!isset($_SESSION["employee_id"]) || !isset($_SESSION["role"]) ||
$_SESSION["employee_id"] == '' || $_SESSION["role"] != 'ADMIN') {
    header("Location:../adminlogin.php");
    exit(); 
}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APPROVE LEAVE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
      <!--SWEET ALERT-->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php include 'navbar.php' ?>

<div class="p-5">
    <table class="table">
        <thead>
            <tr>
                <th>Date of Leave</th>
                <th>Fullname</th>
                <th>Reason</th>
                <th>Date Filed</th>
                <th></th>
            <tr>
        </thead>
        <tbody id="thespaceforcontent">
        </tbody>
    </table>
</div>
<script type="application/javascript">
    fetchfiledleave = () => 
{   
    document.getElementById('thespaceforcontent').innerHTML = '';

    let counterofapprove = 0;

            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                 
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    if(this.responseText == "SESSIONEXPIRED")
                            {location.reload();}
                        let results_object = JSON.parse(this.responseText);
                        results_object.forEach((res) => {
                            document.getElementById('thespaceforcontent').innerHTML += `
                
                            <tr>    
                                <td>${res.date_of_leave}</td>
                                <td>${res.lastname}, ${res.firstname} ${res.middlename}</td>
                                <td>${res.reason}</td>
                                <td>${res.date_filed}</td>
                                <td><button data-value="${res.leaveid}" class="btn btn-success btn-sm thebuttonapprove">APPROVE</button>
                                <button data-value="${res.leaveid}" class="btn btn-danger btn-sm thebuttonreject">REJECT</button></td>
                                
                            <tr>

                        `;
                    
                    });
                  //  createeventlistener();
                    
                }
            };
            xmlhttp.open("POST", "approveloadleaveajax.php?PH=" + Date.now(), true);
            xmlhttp.send();
}
fetchfiledleave();




document.getElementById('thespaceforcontent').addEventListener('click', function (event) {

  if(event.target.classList.contains('thebuttonapprove') || event.target.classList.contains('thebuttonreject')) 
  {
    let leaveId = event.target.getAttribute('data-value');
    let thecontent = event.target.innerHTML;
  //  let textContent = event.target.textContent;

                         
          
     let thedata = {leaveId: leaveId,thecontent: thecontent};
            
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
                            fetchfiledleave();
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
                    xmlhttp.open("POST", "approveleaveajax.php?PH=" + Date.now(), true);
                    xmlhttp.send(FDa);
                
    }
});


</script>
</body>
</html>