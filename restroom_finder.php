<?php
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
<head>Restrooms in Corvallis</head>
<?php
    $query = "SELECT Name, Description FROM Restrooms NATURAL JOIN Locations";
    $result = mysqli_query($connection,$query);
    if (!$result) {
        $error = "server error<br>";
    }
    else {
        $restroom = mysqli_fetch_row($result);
        while ($restroom){
            echo "<br>";
            foreach ($restroom as $key=>$value){
                echo $value . "<br>";
            }
            $restroom = mysqli_fetch_row($result);
        }
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