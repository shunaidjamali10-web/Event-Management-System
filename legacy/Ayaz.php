<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Project</title>
	<link rel="stylesheet" type="text/css" href="css\bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      	<style>
		*{
	box-sizing: border-box;
	font-family: poppins, sans-serif;
}
body{

	background-color: #a9acbbff;
}
 label , input {
	color: rgba(12, 12, 12, 1);
	font-size: 1.5em;
	z-index: 1;

}

button{
    margin-top:10px;
	width: 20%;
	font-size:1.5em;
    background-color: rgba(95, 107, 110, 1);

}
button:hover{
    background-color: rgba(208, 210, 211, 1);

	color: #f0f3fdff;
}
h1{
  background-color:brown;
}


	</style>

</head>
<body>
  <center>
         <div class="col-md-12">
            <h1>SHIEKH AYAZ MELA TICKET REGISTRATION</h1>
</div>
  <div class="container-fluid">
     <div class="Container" class="p-2">
        <div class="row" >   
    <form method="Post" action="MNNAWAB.php">
        <label for="ID">USER ID: </label>
        <br>
        <input type="text" name="txtid" placeholder="Enter User ID"><br>
        <label for="username">USERNAME: </label>
        <br>
        <input type="text" name="txtname" placeholder="Enter User Name"><br>
        <label for="Ticket">NUMBER OF TICKET: </label>
        <br>
        <input type="text" name="txtticket" placeholder="Enter number of Ticket"><br>
       <label for="Contact">CONTACTNO: </label>
        <br>
        <input type="text" name="txtContact" placeholder="Enter User Contact Number"><br>
        <button class name="btnsubmit">SUBMIT</button>

</form>
</div>
</div>
</div>
</center>
<?php
if(isset($_POST['btnsubmit'])) 
{
  
$AID=$_POST['txtid'];
$ANAME=$_POST['txtname'];
$ATNO=$_POST['txtticket'];
$AContact=$_POST['txtContact'];
  $mycon=mysqli_connect("localhost","root","","event_lahoti");
      echo"Connection Successfully";
 $sql="INSERT INTO ayaz_data VALUES(?,?,?,?)";
    $ps= $mycon->prepare($sql);
    $ps->bind_param("isis",$AID,$ANAME,$ATNO,$AContact);
 $ps->execute();
     echo"Record inserted Successfully";
}
?>
</body>
</html>