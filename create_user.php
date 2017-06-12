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
<div id="bodyCreate">
<p> Sign-Up </p>
<form action = "create_user.php" method = "post">
        
    <input type = "text" name = "username" placeholder= "Enter Username"><br>
    <input type = "password" name = "password" placeholder= "Enter Password"><br>
    <input type = "password" name = "password2" placeholder= "Re-Enter Password"><br>
    <input type = "submit" value = "Submit">
    
</form>
</div>
<?php
    if (isset($_POST['username'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $error = validate_input($connection,$username,$password,$password2);
        if ($error != ""){
            echo $error;
        }
        else {
            $salt = rand(0,99999999);
            $salted_password = $salt . $password;
            $hash = hash("sha256",$salted_password);
            $query =  "INSERT INTO `Users` (`Username`,`Password`,`Salt`) VALUES('".$username."','".$hash."','".$salt."')";
            $result = mysqli_query($connection,$query);
            if (!$result){
                $error_msg = "Submission failed for some reason :(<br>";
            }
            else {
                $error_msg = "Successfully submitted user " . $username . "<br>";
            }
            echo $error_msg;
        }
    }
    function validate_input($connection,$username,$password,$password2){
        $error = "";
        if ($password != $password2){
            $error = $error . "<p>Passwords must match.</p>";
        }
        if (strlen($password) < 8){
            $error = $error . "<p>Password must be at least 8 characters.</p>";
        }
        if ($username == ""){
            $error = $error . "<p>Username cannot be blank.</p>";
        }
        else {
            $query = "SELECT * FROM `Users` WHERE `Username` = '" . $username . "'";
            $result = mysqli_query($connection,$query);
            if (!$result){
                $error = "<p>failed to do stuff</p>";
            }
            else {
                if ($result->num_rows > 0) {
                    $error = $error . "<p>There is already someone with the username '". $username . "'</p>";
                }
            }
        }
        return $error;
    }
?>