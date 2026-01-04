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
h2 , label , input {
	color: rgba(2, 17, 20, 1);
	font-size: 3.5em;
	z-index: 1;

}

#light{
	width: 25%;
	font-size:1.5em;

}
#light:hover{
	color: #30385cff;
}

	</style>

</head>
<body>
	<center>

<div class="container p-2">
	
		<div class="row">
			<div class="col-md-8  p-2">
				<div class="box">
				<h2> <i class="fa fa-user">Login</i></h2>
				<hr>
				<form action="mainpage.php" method="post">
					<div class="form-group mb-2">
						<label><i class="fa fa-user-circle">username</i></label><br>
						<input type="text" name="username" class="form-control">
						

					</div>


					<div class="form-group mb-2">
						<label><i class="fa fa-key">Password</i></label><br>
						<input type="Password" name="Password" class="form-control">
						

					</div>
					<button id="light" class="btn btn-primary" value="log" name="logbtn">LOGIN</button>

				</form>
				<?php
				if(isset($_POST['logbtn']))
				{
   $user= $_POST['username']; 
   $pass= $_POST['Password'];
if((strcmp($user,"Shunaid")==0) and (strcasecmp($pass,"Baloch1234")==0))

	   echo "<font color=green size=8>You are successfully login</font>";

	
     else
		echo "<font color=Red size=8>please enter correct username and password!</font>";
	}

				?>

			</div>
		



			</div>
			
		</div>
		
	</div>
</center>
</body>
<script src="js\bootstrap.bundle.min.js"></script>


</html>