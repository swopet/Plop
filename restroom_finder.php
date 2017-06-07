<?php
    session_start();
    include "database_connection.php";
	include "header.php";
    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (!$connection) {
        echo "could not connect :(";
    }
?>
<header>
<?php
	include ("Nav.php");
?>
</header>
<head>Restroom Locations Closest to You</head><br>
<form action = "restroom_finder.php" id = "location_entry">
<input type = "hidden" name = "action" value = "set_location">
<label>Enter Your Location: </label><input type = "text" name = "my_address">
<input type = "submit">
</form>

<?php
    if ($_GET['action'] == 'set_location'){
        $_SESSION['coords'] = geocode($_GET['my_address']);
        if (!$_SESSION['coords']){
            echo "could not find your location: \"".$_GET['my_address']."\"<br>";
        }
    }
    if (isset($_SESSION['coords']) && $_SESSION['coords']){
        $bathrooms = get_bathrooms($connection,deg2rad($_SESSION['coords'][0]),deg2rad($_SESSION['coords'][1]));
        foreach ($bathrooms as $bathroom){
            $query = "SELECT RestroomID, AvgRating FROM Restrooms WHERE LocationID = " . $bathroom['LocationID'];
            $result = mysqli_query($connection,$query);
            if ($row = mysqli_fetch_row($result)){
                echo "~".$bathroom['distance'] . "km: " . $bathroom['Name'] . "<br>";
                echo "<a href = \"restroom_info.php?restroom_id=".$row[0]."\">Restroom ".$row[0]."</a> (".(($row[1] == 0) ? "unrated" : (number_format($row[1],1)."/10")).")<br>";
                while($row = mysqli_fetch_row($result)){
                    echo "<a href = \"restroom_info.php?restroom_id=".$row[0]."\">Restroom ".$row[0]."</a> (".(($row[1] == 0) ? "unrated" : (number_format($row[1],1)."/10")).")<br>";
                }
                echo "<br>";
            }
            
        }
    }
    function geocode($address){
        $address = urlencode($address);
        $url = "http://maps.google.com/maps/api/geocode/json?address={$address}"; //call to Google Maps API
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
    function distance($lat1,$long1,$lat2,$long2){ //Haversine formula
        $radius = 6371e3; //radius of the earth in meters
        $delta_lat = ($lat2-$lat1);
        $delta_long = ($long2-$long1);
        $a = sin($delta_lat/2)*sin($delta_lat/2)+cos($lat1)*cos($lat2)*sin($delta_long/2)*sin($delta_long/2);
        $c = 2 * atan2(sqrt($a),sqrt(1-$a));
        $d = $radius * $c;
        return number_format($d / 1000 , 2);
    }
    function dist_sort($item1,$item2){
        if ($item1['distance'] == $item2['distance']) return 0;
        return ($item1['distance'] > $item2['distance']) ? 1 : -1;
    }
    function get_bathrooms($connection,$my_lat,$my_long){
        $return_arr = array();
        $query = "SELECT Latitude, Longitude, AddressID FROM Addresses";
        $result = mysqli_query($connection,$query);
        $locations = array();
        while ($row = mysqli_fetch_row($result)){    
            $lat = deg2rad($row[0]);
            $long = deg2rad($row[1]);
            $new_array = array("id"=>$row[2],"distance"=>distance($my_lat,$my_long,$lat,$long));
            array_push($locations,$new_array);
        }
        usort($locations,'dist_sort');
        foreach ($locations as $location){
            $query = "SELECT LocationID, Name, AddressID, StreetAddress, City, State, Zip FROM Locations NATURAL JOIN Addresses WHERE AddressID = " . $location['id'];
            $result = mysqli_query($connection,$query);
            while ($row = mysqli_fetch_assoc($result)){
                $row['distance'] = $location['distance'];
                array_push($return_arr,$row);
            }
        }
        return $return_arr;
    }
?>