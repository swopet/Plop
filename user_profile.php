<?php
    session_start();
    if (!isset($_SESSION['user'])){
      header("location:log_in.php");
      die;
    }
  include "database_connection.php";
	include ("header.php");
    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (!$connection) {
        echo "could not connect :(";
    }
  
  
  $username = $_SESSION['user'];
    ?>

<header>
<?php
	include ("Nav.php");
?>
</header>
<div id="bodyUser">
<div id = "username"> <br>
  Your Username: <i><?php echo $username; ?></i>
</div>
<br><br>

<head><b>My Reviews</b></head><br>
<?php
    $query = "SELECT Name, Rating, Cleanliness, Supply, Timestamp, ReviewText FROM Reviews NATURAL JOIN Restrooms NATURAL JOIN Locations WHERE Username= '$username' ";
    $result = mysqli_query($connection,$query);
    if (!$result) {
        $error = "server error<br>";
    }
    else {
        $review = mysqli_fetch_row($result);
        while ($review){
            echo "<br>";
            $i=0;
            foreach ($review as $key=>$value){
              if ($i == 0) {
                  echo "Location: " . $value . "<br>";
              } else if ($i == 1) {
                  echo "Rating(1-10): " . $value . "<br>";
              } elseif ($i == 2) {
                  echo "Cleanliness(1-10): " . $value . "<br>";
              } elseif ($i == 3) {
                  echo "Supply: " . $value . "<br>";
              } elseif ($i == 4) {
                  echo "Timestamp: " . $value . "<br>";
              } elseif ($i == 5) {
                  echo "Review: " . $value . "<br>";
              }
              $i++;
            }
            $review = mysqli_fetch_row($result);
        }
    }
?>
</div>