<?php
	session_start();
    include "database_connection.php";
	include ("header.php");
    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (!$connection) {
        echo "could not connect :(";
    } ?>
	<header>
<?php
	include ("Nav.php");
?>
</header>
<body>
	
	
	<div id="pic1">
		<h1> Jake Jeffory </h1>
		<br>
		<p> Jake grew up a modest and kind gentle boy. Today he enjoys longs walks on the beach and prancing through fields of flowers</p>
		<p> He is a extraordinary programmer who helped program this amazing website. </p>
	</div>
	
	
	<div id="pic3">
		<h1> Cade Raichart </h1>
		<br>
		<p> Cade lived a simple life, in a small farming town. He learned there that the word programming ment nothing</p>
		<p> In search of this greater power, programming, cade traveled far to the town Corvallis</p>
		<p> During this time cade used his new found skills to help his team make this website. </p>
	</div>
	<div id="pic3">
		<h1> Trevor Swope </h1>
		<br>
		<p> Born on the planet omicron persei 8, trevor found it easy to infiltrate the humans plant. </p>
		<p> In search of this greater power, he fell upon the great power of programming</p>
		<p> Using his higher intellegence, trevor finally was able to make his port-a-potty yelp website.</p>
	</div>
	<div id="picSet1">
			<img src="jake.png">
	</div>
	<div id="picSet2">
		<img src="trevor.png">
	</div>
	<div id="picSet3">
		
	<img src="cade.png">
	</div>

	
	
</body>