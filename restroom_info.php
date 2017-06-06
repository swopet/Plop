<?php
    include "database_connection.php";
	include "header.php";
    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (!$connection) {
        echo "could not connect :(";
    }
    // $restroom = null; //CHANGE THese
    $restroomID = $_GET['restroom_id'];
?>

<header>
<?php
	include ("Nav.php");
?>
</header>

<br><head> <b>Restroom Summary</b> <br> </head> <br>

<?php
    $query = "SELECT Name, Description, AvgRating, Public, Stalls FROM Restrooms NATURAL JOIN Locations WHERE RestroomID = '$restroomID'";
    $result = mysqli_query($connection,$query);
    if (!$result) {
        $error = "server error<br>";
    }
    else {
        $review= mysqli_fetch_row($result);
        while ($review){
            $i=0;
            foreach ($review as $key=>$value){
              if ($i == 0) {
                  echo "Location: " . $value . "<br>";
              } elseif ($i == 1) {
                  echo "Description: " . $value . "<br>";
              } elseif ($i == 2) {
                  echo "Average Rating: " . $value . "<br>";
              } elseif ($i == 3) {
                  if ($value == 1){
                    echo "Status: Public <br>";
                  } else {
                    echo "Status: Private <br>";
                  }
              } elseif ($i == 4) {
                  echo "Number of stalls: " . $value . "<br>";
              }
              $i++;

            }
            $review = mysqli_fetch_row($result);
        }
    }

    echo "<br><br><head> <b>Restroom Reviews</b></head> <br>";
    $query = "SELECT Rating, Cleanliness, Supply, ReviewText FROM Restrooms NATURAL JOIN Locations NATURAL JOIN Reviews WHERE RestroomID = '$restroomID'";
    $result = mysqli_query($connection,$query);
    if (!$result) {
        $error = "server error<br>";
    }
    else {
        $review= mysqli_fetch_row($result);
        while ($review){
            echo "<br>";
            $i=0;
            foreach ($review as $key=>$value){
              if ($i == 0) {
                  echo "Rating(1-10): " . $value . "<br>";
              } elseif ($i == 1) {
                  echo "Cleanliness(1-10): " . $value . "<br>";
              } elseif ($i == 2) {
                  echo "Supply: " . $value . "<br>";
              } elseif ($i == 3) {
                  echo "Rating: " . $value . "<br>";
              }
              $i++;

            }
            $review = mysqli_fetch_row($result);
        }
    }
?>
                                                                  
