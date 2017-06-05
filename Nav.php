<?php 
$Home = "myButtons";
$About = "myButtons";
$SignUp = "myButtons";
$Login = "myButtons";
$Restrooms = "myButtons";
$Register = "myButtons";
$Add_restroom = "myButtons";


$Navid =basename($_SERVER['PHP_SELF'],".php");
	if ($Navid =="Home"){
		$Home = 'myActiveButton';
	} else if ($Navid =="About"){
		$About = 'myActiveButton';
	} else if ($Navid =="SignUp"){
		$SignUp = 'myActiveButton';
	} else if ($Navid =="Login"){
		$Login = 'myActiveButton';
	} else if ($Navid =="Restrooms"){
		$Restrooms = 'myActiveButton';
	} else if ($Navid =="Register"){
		$Register = 'myActiveButton';
	}else if ($Navid =="Add_restroom"){
		$Add_restroom = 'myActiveButton';
	}
?>	
	<h2> Plop: Find Your Perfect Restroom <?php echo $_SESSION['username']; ?> </h2>
	<ul class="one" id="one">
		<li><a class="<?php echo $Home; ?>" href="restroom_finder.php"> Home</a></li>
		<li><a class="<?php echo $About; ?>" href="about.php">About </a></li>
		<li><a class="<?php echo $Add_restroom; ?>" href="add_restroom.php">Add Restroom</a></li>
		<li><a class="<?php echo $Login; ?>" href="log_in.php">Login</a></li>
		<li><a class="<?php echo $Restrooms; ?>" href="restroom_finder.php">Leave a Reivew</a></li>
		<li><a class="<?php echo $Register; ?>" href="create_user.php">Sign-Up</a></li>
		
	</ul>
	
