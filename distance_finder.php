<?php
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
<?php
    $my_lat = deg2rad(40.7470914197085);
    $my_long = deg2rad(-73.98700443029149);
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
        echo $location['id'] . ": " . $location['distance'] . "km<br>";
    }
    function distance($lat1,$long1,$lat2,$long2){
        $radius = 6371e3;
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
?>