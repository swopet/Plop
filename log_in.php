<?php
    include "database_connection.php";
    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (!$connection) {
        echo "could not connect :(";
    } ?>
<head>Log In</head>  
<form action = "log_in.php" method = "post">  
    <label>Username<br></label><input type = "text" name = "username"><br>
    <label>Password<br></label><input type = "password" name = "password"><br>
    <input type = "submit" value = "Submit">    
</form>
<?php
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