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
<div id="bodyLog">
<h4>Log In</h4>  
<form action = "log_in.php" method = "post">  
    <input type = "text" name = "username" placeholder=" Enter Username" ><br>
    <input type = "password" name = "password" placeholder=" Enter Password"><br>
    <input type = "submit" value = "Submit">    
</form>
</div>

<?php
	if ($_GET['log_out'] = 'true' and isset($_SESSION['user'])){
        echo "Logged out of account " . $_SESSION['user'];
        unset($_SESSION['user']);
    }
    if (isset($_POST['username'])){
        $error = "plop";
        $username = $connection->real_escape_string($_POST['username']);
        $password = $_POST['password'];
        $query = "SELECT Salt FROM Users WHERE Username = '" . $username . "'";
        $result = mysqli_query($connection,$query);
        if (!$result) {
            $error = "server error<br>";
        }
        else {
            if ($result->num_rows == 0){
                $error = "Incorrect username or password<br>";
            }
            else {
                $salt = mysqli_fetch_row($result)[0];
                $salted_password = $salt . $password;
                $hash = hash("sha256",$salted_password);
                $query = "SELECT Password FROM Users WHERE Username = '" . $username . "'";
                $result = mysqli_query($connection,$query);
                if (!$result) {
                    $error = "server error<br>";
                }
                else {
                    if ($result->num_rows == 0){
                        $error = "Incorrect username or password<br>";
                    }
                    else {
                        if ($hash == mysqli_fetch_row($result)[0]){
                            $error = "Correct username and password! Logged in<br>";
                        }
                        else {
                            $error = "Incorrect username or password<br>";
                        }
                    }
                }
            }
        }
        echo $error;
    }
?>