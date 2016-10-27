<?php
/**
 * Created by PhpStorm.
 * User: Austin
 * Date: 19-Oct-16
 * Time: 5:50 PM
 */
session_start();

$username = "dbu319t10";
$passwordServer = "spUj4h?h";
$dbServer = "mysql.cs.iastate.edu";
$dbName   = "db319t10";

$userName = $_REQUEST["userName"];
$password = $_REQUEST["password"];
$confirmPassword = $_REQUEST["passwordConfirm"];
$email = $_REQUEST["email"];
$phone = $_REQUEST["phone"];
$librarian = $_REQUEST["librarian"];
$firstName = $_REQUEST["firstName"];
$lastName = $_REQUEST["firstName"];

$result=0;

//TODO
//insert into table using procedure
if(!ctype_alnum($userName)){
    //not valid
    $result = 1;
}
if(strcmp($password,$confirmPassword)!=0){
    //not valid
    $result = 1;
}
$email = filter_var($email,FILTER_SANITIZE_EMAIL);
if(filter_var($email,FILTER_VALIDATE_EMAIL)==false) {
    //not valid
    $result = 1;
}
$pattern = '/^(?:\(\+?44\)\s?|\+?44 ?)?(?:0|\(0\))?\s?(?:(?:1\d{3}|7[1-9]\d{2}|20\s?[78])\s?\d\s?\d{2}[ -]?\d{3}|2\d{2}\s?\d{3}[ -]?\d{4})$/';
if( !preg_match( $pattern, $phone ) ){
    // $phone not valid
    $result = 1;
}
if($librarian==null){
    //not valid
    $result = 1;
}
if(!ctype_alpha($firstName) || !ctype_alpha($lastName)){
    //not valid
    $result =  1;
}
if($result!=1){
    $password = md5($password);
//$sql = "INSERT "
    $conn = new mysqli($dbServer, $username, $passwordServer, $dbName);

    $sql = "call addUser('$userName','$password','$email','$phone','$librarian','$firstName','$lastName');";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}else{
    echo 1;
}
?>