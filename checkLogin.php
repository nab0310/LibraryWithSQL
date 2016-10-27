<?php
/**
 * Created by PhpStorm.
 * User: Austin
 * Date: 19-Oct-16
 * Time: 9:41 PM
 */
session_start();

$username = "dbu319t10";
$passwordServer = "spUj4h?h";
$dbServer = "mysql.cs.iastate.edu";
$dbName   = "db319t10";

$conn = new mysqli($dbServer, $username, $passwordServer, $dbName);
//TODO
//1. need to make a call to access database
//2. select all usernames + password
//      from table users
//      where username and password match
//3. if table returns something, send to viewLibrary
//      else send back to login
//
//?? need to make a new connection in every php file?

$userName = $_REQUEST["userName"];
$action = $_REQUEST["action"];

if(strcmp($action,"CheckUserName")==0){

    $sql = "call checkIfUserIsInDatabase('$userName');";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        echo '1';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        echo $result;
    }
}
if(strcmp($action,"login")==0){
    $password = $_REQUEST["password"];

    $password = md5($password);

    $sql = "call validateLogin('$userName','$password');";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        $_SESSION["username"] = $userName;
        echo '1';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        echo $result;
    }

}

$conn->close();

?>

