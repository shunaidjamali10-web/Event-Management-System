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
	font-family:serif sans-serif;
}
body{

	background-color: #787a86ff;
}
 label , input {
	color: rgba(7, 7, 7, 1);
	text-shadow: 1px 1px grey;
	font-size: 1.5em;
	z-index: 1;

}

button{
    margin-top:10px;
	width: 20%;
	font-size:1.5em;
    background-color: rgba(57, 75, 80, 1);

}
button:hover{
    background-color: rgba(208, 210, 211, 1);

	color: #f0f3fdff;
}
h1{
    background-color:orange;
}

	</style>
      

</head>
<body>
    <center>
        <div class="col-md-12">
            <h1> LAHOTI MELA TICKET REGISTRATION</h1>
</div>
    <div class="container-fluid">
     <div class="Container" class="p-2">
        <div class="row" >   
    <form method="Post" action="Lahoti.php">
        <label for="ID">USER ID: </label>
        <br>
        <input type="text" name="USERID" placeholder="Enter User ID"><br>
        <label for="username">USERNAME: </label>
        <br>
        <input type="text" name="TEXTUSER" placeholder="Enter User Name"><br>
        <label for="Ticket">NUMBER OF TICKET: </label>
        <br>
        <input type="text" name="Ticket_no" placeholder="Enter number of Ticket" min="1" max="5"><br>
       <label for="Contact">CONTACTNO: </label>
        <br>
        <input type="text" name="Contact_no" placeholder="Enter User Contact Number"><br>
        <button class name="Btnsubmit">SUBMIT</button>

</form>
</div>
</div>
</div>

    <?php
    if(isset($_POST['Btnsubmit']))
    {
    $UID=$_POST['USERID'];
    $UNAME=$_POST['TEXTUSER'];
    $UTNO=$_POST['Ticket_no'];
    $UContact=$_POST['Contact_no'];
   $mycon=mysqli_connect("localhost","root","","event_lahoti");
      echo"Connection Successfully";

      $sql="INSERT INTO user_data VALUES(?,?,?,?)";
     $ps= $mycon->prepare($sql);
     $ps->bind_param("isii",$UID,$UNAME,$UTNO,$UContact);
     $ps->execute();
     echo"Record inserted Successfully";
    }
    ?>    
</center>
</body>
</html>