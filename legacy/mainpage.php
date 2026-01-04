<htmL>
<head>
    <title>MainPage</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="css\bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
@import 
url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
*{
	margin: 20px;
	padding: 10px 10px;
	box-sizing: border-box;
	font-family: poppins, sans-serif;
}
body{
	display: flex;
	justify-content:center;
	-ms-align-items: center;
	align-items: center;
	min-height: 100vh;
	background-color: #0e1538;
}

.box{
	position: relative;
	width: 200px;
	height: 200px;
	display: flex;
	justify-content: center;
	-ms-align-items:center;
	align-items: center;
	background-color: rgba(0, 0, 0, 0.5);
	border-radius: 20px;
	overflow: hidden;

}
.box h2{
	color: rgb(226, 226, 226);
	text-shadow: 2px 2px black;
	font-size: 4.5em;
	z-index: 2;
}
.box::before{
	content: '';
	position: absolute;
	width: 170px;
	height:140% ;
	background: linear-gradient(#00ccff,#d500f9);
	animation: rotate 4s linear infinite;
}

.box::after{

	content: '';
	position: absolute;
	background-color: #0e1538;
	inset: 2px;
	border-radius: 16px;



}
h2{
}
h1{
	color: rgb(226, 226, 226);
	text-shadow: 2px 2px black;
	font-size: 2em;
	z-index: 2;
}
a{
	text-decoration:none;

}
a:hover{
	cursor: pointer;
	color:lightblue;
}



@keyframes rotate {

	from{
		transform:rotate(0deg);
	}
	to{
		transform: rotate(360deg);
	}
}
    </style>
</head>
<body>
	<div class="Container">
<div class="Container-fluid">
    <div class="row">

    <div class="box" class="col-md-3">
<h1><a href="Eventdata.php" > DATA ABOUT EVENT</a> </h1>

    </div>
	    <div class="box" class="col-md-3">
<h1><a href="Lahoti.php"> LAHOTI MELA</a> </h1>

    </div>

	    <div class="box" class="col-md-3">
<h1> <a href="Ayaz.php"> SHIEKH AYAZ MELA </a></h1>

    </div>

	    <div class="box" class="col-md-3">
<h1> <a href="MNNAWAB.php"> MUSICAL NIGHT SHOW </a></h1>

    </div>
</div>
</div>
</div>



</body>
</html>