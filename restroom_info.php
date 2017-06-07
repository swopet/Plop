<?php
    session_start();
    if (!isset($_GET['restroom_id'])){
        header("location:restroom_finder.php");
        die;
    }
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
<div id="BodyInfo">
<fieldset style="border: 3px black solid">

<legend style="border: 2px black solid; margin-left: 1em; padding: 0.2em 0.8em">Restroom Summary</legend>


<?php
    if (isset($_GET['action'])){
        if ($_GET['action']=="submit_review"){
            $query = "SELECT MAX(ReviewID) FROM Reviews";
            $result = mysqli_query($connection,$query);
            $review_id = mysqli_fetch_row($result)[0]+1;
            $query = "INSERT INTO `Reviews` (`ReviewID`, `Username`, `Rating`, `Cleanliness`, `Supply`, `Timestamp`, `ReviewText`, `RestroomID`) VALUES ('".$review_id."','".$_GET['username']."','".$_GET['rating']."', '".$_GET['cleanliness']."', '".$_GET['supply_text']."', CURRENT_TIMESTAMP, '".$_GET['review_text']."', '".$_GET['restroom_id']."')";
            $result = mysqli_query($connection,$query);
            if (!$result){
                echo "Failed to submit review<br>";
            }
        }
        else if ($_GET['action']=="submit_comment"){
            $query = "SELECT MAX(CommentID) FROM Comments";
            $result = mysqli_query($connection,$query);
            $comment_id = mysqli_fetch_row($result)[0]+1;
            $query = "INSERT INTO `Comments` (`CommentID`, `Username`, `Comment`, `Timestamp`, `ReviewID`) VALUES ('".$comment_id."','".$_GET['username']."','".$_GET['comment_text']."', CURRENT_TIMESTAMP, '".$_GET['review_id']."')";
            $result = mysqli_query($connection,$query);
            if (!$result){
                echo "Failed to submit comment<br>";
            }
        }
    }
    $query = "SELECT Name, Description, AvgRating, Public, Stalls FROM Restrooms NATURAL JOIN Locations WHERE RestroomID = '$restroomID'";
    $result = mysqli_query($connection,$query);
    if (!$result) {
        $error = "server error<br>";
    }
    else {
        while ($review = mysqli_fetch_assoc($result)){
            echo "Location: " . $review['Name'] . "<br>";
            echo "Description: " . $review['Description'] . "<br>";
            echo "Average Rating: " . (($review['AvgRating'] == 0) ? "unrated" : $review['AvgRating']) . "<br>";
            echo "Status: " . (($review['Public'] == 0) ? "Public" : "Private") . "<br>";
            echo "Number of stalls: " . $review['Stalls'] . "<br>";
        }
    }
	echo "<fieldset>";
	echo "<legend>Restroom Reviews</legend>";
    $query = "SELECT Username, Timestamp, Rating, Cleanliness, Supply, ReviewText, ReviewID FROM Restrooms NATURAL JOIN Locations NATURAL JOIN Reviews WHERE RestroomID = '$restroomID'";
    $result = mysqli_query($connection,$query);
    if (!$result) {
        $error = "server error<br>";
    }
    else {
        $count = 0;
        while ($review = mysqli_fetch_assoc($result)){
            echo "<br>";
            $i=0;
            echo ($review['Username'] == "" ? "<i>[deleted]</i>" : $review['Username']).", ".$review['Timestamp'] ."<br>";
            echo $review['ReviewText'] . "<br>";
            echo "Rating: " . $review['Rating'] . "/10<br>";
            echo "Cleanliness: " . $review['Cleanliness'] . "/10<br>";
            echo "Supply: " . $review['Supply'] . "<br>";
            $comment_query = "SELECT Username, Comment, Timestamp FROM Comments WHERE ReviewID = ".$review['ReviewID'];
            $comment_result = mysqli_query($connection,$comment_query);
            if (!$comment_result){
                $error = "server error<br>";
            }
            else {
                while ($comment = mysqli_fetch_assoc($comment_result)){
                    echo "at ".$comment['Timestamp'].", ".$comment['Username']." said:<br>";
                    echo $comment['Comment']."<br>";
                }
            }
			echo "</fieldset>";
            if (isset($_SESSION['user'])){
                ?>
				</fieldset>	
                <form action = "restroom_info.php">
                <input type = "hidden" name = "action" value = "submit_comment">
                <input type = "hidden" name = "username" value = <?php echo $_SESSION['user']; ?>>
                <input type = "hidden" name = "review_id" value = <?php echo $review['ReviewID']; ?>>
                <input type = "hidden" name = "restroom_id" value = <?php echo $_GET['restroom_id']; ?>>
                <textarea name = "comment_text" rows = "5" cols = "20"></textarea>
                <input type = "submit">
                </form>
			
                <?php
            }
            $count++;
        }
        if ($count == 0){
            echo "<br><i>Currently no reviews.</i>";
            
        }
        if (!isset($_SESSION['user'])){
            echo "<a href = \"log_in.php\">Log in</a> to write a review<br>";
        }
        else {
            ?>
			<fieldset style="border: 3px black solid">

<legend style="border: 2px black solid; margin-left: 1em; padding: 0.2em 0.8em">Leave A Review</legend>
     
            <form action = "restroom_info.php">
            <input type = "hidden" name = "action" value = "submit_review">
            <input type = "hidden" name = "username" value = <?php echo $_SESSION['user']; ?>>
            <input type = "hidden" name = "restroom_id" value = <?php echo $_GET['restroom_id']; ?>>
            <textarea name = "review_text" rows = "5" cols = "40">[review text here]</textarea><br>
            <label style="font-size: 15px">Supply status</label><span id="slider_value2" style="color:red;font-weight:bold;font-size:15px;"></span><br>
			<textarea name = "supply_text" rows = "2" cols = "40"></textarea><br>
            <label>Cleanliness</label><span id="slider_value2" style="color:red;font-weight:bold;font-size:15px;"></span><br>
			<input type = "range" name = "cleanliness" min = "1" max = "10" step = "1" value = "5" style="float:left"><br>
            <label>Overall Rating</label><span id="slider_value2" style="color:red;font-weight:bold;font-size:15px;"></span><br>
			<input type = "range" name = "rating" min = "1" max = "10" step = "1" value = "5" style="float:left"><br>
            <input type = "submit">
            </form>
            <?php
        }
        
    }
?>
</fieldset>
</div>