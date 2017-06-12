<!DOCTYPE html>
<html>
<div id="styleChange">

</div>
<head lan="en">
<?php
	
    include "database_connection.php";
	include ("header.php");
    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (!$connection) {
        echo "could not connect :(";
    }
?>
<title id ="picture"> Plop </title>




<header>
<?php
	include ("Nav.php");
?>
</header>
</head>
<body>
<div id="bodyAdd1">
	<div id="makeFloat1">
		<div id ="SelLoc">
<?php
    if (!isset($_GET['step']) || $_GET['step'] == "select_location"){
		
        echo "<head>Select Location</head>";
        ?>
		
		</div>
        <form action = "add_restroom.php" id = "location_form">
        <input type = "hidden" name = "step" value = "add_restroom">
        <?php
        $query = "SELECT LocationID, Name FROM Locations";
        $result = mysqli_query($connection,$query);
        if (!$result) {
            $error = "server error<br>";
        }
        else {
            echo "<select name = \"location_id\" form = \"location_form\">";
            echo "<option style='background:black; opacity:.8;' value = -1>[NEW]</option>";
            $location = mysqli_fetch_row($result);
            while ($location){
                echo "<option value = ".$location[0] . ">" . $location[1] . "</option>";
                $location = mysqli_fetch_row($result);
            }
            echo "</select>";
        }
        ?>
		
        <input type = "submit" name="submitAdd">
	</div>
    </form>

</div>


        <?php
    }
    if ($_GET['step'] == "add_restroom"){
		$styleSheet=1;
        ?>
<div id="bodyAdd2">
		<div id="addTitle1">
			<head> Add New Restroom </head><br>
		</div>
		
        <form action = "add_restroom.php" id = "restroom_form">
        <input type = "hidden" name = "step" value = "submit_restroom">
        <input type = "hidden" name = "location_id" value = <?php echo $_GET['location_id']; ?>>
        <?php
        if ($_GET['location_id'] == "-1"){
            ?>
			<div id="makeFloat">
			<div id="addLoc">
			
            <head name="title"> Add New Location </head>
			</div>
			
            <input type = "text" name = "name" placeholder="Loacation Name"> </input><br>
            <input type = "text" name = "address" placeholder="Street Address"> </input><br>
            <input type = "text" name = "city" placeholder="City"> </input><br>
            <input type = "text" name = "state" placeholder="State"> </input><br>
            <input type = "text" name = "zip" placeholder="zip"> </input><br>
            
            <?php
        }
        else{
            $query = "SELECT Name FROM Locations WHERE LocationID = " . $_GET['location_id'];
            $result = mysqli_query($connection,$query);
            if (!$result) {
                $error = "server error<br>";
                echo $error;
            }
            else {
                $location = mysqli_fetch_row($result);
                echo "Location: ".$location[0]."<br>";
            }
        }
        ?>
        
        <input type = "text" name = "description" placeholder="description"> </input><br>
		<input type = "text" name = "stalls" placeholder="Stalls"> </input><br>
		
        <label>Gender Neutral <input type = "checkbox" name = "gender"></input><br>
        <label>Handicap Accessible<input type = "checkbox" name = "handicap"> </input><br>
        <label>Public<input type = "checkbox" name = "public"> </input><br>
        
        
        <input type = "submit" onclick="document.getElementById('bodyAdd').setAttribute('id','blank')">
		</div>
        </form>
</div>


<script>
	var div = document.getElementById("bodyAdd");

	function style() {
		div.setAttribute("id","blank");
	}

</script>   
 <?php
    }
    else if ($_GET['step'] == "submit_restroom"){
        if ($_GET['location_id'] == "-1"){
            $query = "SELECT MAX(AddressID) FROM Addresses";
            $result = mysqli_query($connection,$query);
            if (!$result){
                echo "server error<br>";
            }
            $address_id = mysqli_fetch_row($result)[0] + 1;
            $full_address = $_GET['address'] . " " . $_GET['city'] . " " .$_GET['state']. " " .$_GET['zip'];
            $coords = geocode($full_address);
            if ($coords){
                $latitude = $coords[0];
                $longitude = $coords[1];
            }
            $query = "INSERT INTO `Addresses` (`AddressID`, `StreetAddress`, `City`, `State`, `Zip`, `Latitude`, `Longitude`) VALUES ('" . $address_id . "', '".$_GET['address']."', '".$_GET['city']."', '".$_GET['state']."', '".$_GET['zip']."', '".$latitude."','".$longitude."')";
            $result = mysqli_query($connection,$query);
            if (!$result){
                echo "server error<br>";
            }
            $query = "SELECT MAX(LocationID) FROM Locations";
            $result = mysqli_query($connection,$query);
            if (!$result){
                echo "server error<br>";
            }
            $location_id = mysqli_fetch_row($result)[0] + 1;
            $query = "INSERT INTO `Locations` (`LocationID`, `Name`, `Approved`, `AddressID`) VALUES ('".$location_id."', '".$_GET['name']."', '0', '".$address_id."')";
            $result = mysqli_query($connection,$query);
            if (!$result){
                echo "server error on Locations<br>";
            }
        }
        else {
            $location_id = $_GET['location_id'];
        }
        $query = "SELECT MAX(RestroomID) FROM Restrooms";
        $result = mysqli_query($connection,$query);
        if (!$result){
            echo "server error on Restrooms<br>";
        }
        $restroom_id = mysqli_fetch_row($result)[0] + 1;
        $gender = ($_GET['gender'] == 'on') ? 1 : 0;
        $handicap = ($_GET['handicap'] == 'on') ? 1 : 0;
        $public = ($_GET['public'] == 'on') ? 1 : 0;
        $query = "INSERT INTO `Restrooms` (`RestroomID`, `Approved`, `Gender`, `HandicapAccessible`, `Public`, `Stalls`, `Description`, `LocationID`, `AvgRating`) VALUES ('".$restroom_id."', '1', '".$gender."', '".$handicap."', '".$public."', '".$_GET['stalls']."','".$connection->real_escape_string($_GET['description'])."', '".$location_id."', '0')";
        $result = mysqli_query($connection,$query);
        if (!$result){
            echo "server error on Submission<br>";
        }
        else echo "submitted";
    }
    function geocode($address){
        $address = urlencode($address);
        $url = "http://maps.google.com/maps/api/geocode/json?address={$address}";
        $resp_json = file_get_contents($url);
        $response = json_decode($resp_json, true); 
        if($response['status']=='OK'){
            $latitude = $response['results'][0]['geometry']['location']['lat'];
            $longitude = $response['results'][0]['geometry']['location']['lng'];
            if($latitude && $longitude){
                $coords = array();            
                array_push(
                    $coords, 
                        $latitude, 
                        $longitude
                    );
                return $coords; 
            }else{
                return false;
            }  
        }else{
            return false;
        }
    }
?>

</body>
</html>