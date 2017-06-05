<?php
    include "database_connection.php";
    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (!$connection) {
        echo "could not connect :(";
    }
?>
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
?>