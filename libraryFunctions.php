<?php
/**
 * Created by PhpStorm.
 * User: nab0310
 * Date: 10/20/2016
 * Time: 11:18 AM
 */
session_start();
$username = "dbu319t10";
$passwordServer = "spUj4h?h";
$dbServer = "mysql.cs.iastate.edu";
$dbName   = "db319t10";
$conn = new mysqli($dbServer, $username, $passwordServer, $dbName);

$action = $_REQUEST["action"];
$loggedInUser = $_SESSION["username"];

if(strcmp($action, "userID") == 0){
    echo "User: " . $loggedInUser;
}

if(strcmp($action,"loadBooks")==0){
    $books = array();
    $sql = "call getAllBooks();";
    $result = $conn->query($sql);
    mysqli_next_result($conn);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
        echo json_encode($books);
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if(strcmp($action,"addBook")==0){
    $id = $_REQUEST["BookID"];
    $title = $_REQUEST["bookTitle"];
    $author = $_REQUEST["author"];
    $available = $_REQUEST["available"];
    $shelf = $_REQUEST["shelf"];

    $sql2 = "call selectAllRowsFromShelf('$shelf')";

    $result2 = $conn->query($sql2);
    mysqli_next_result($conn);
    if ($result2->num_rows< 20) {
        $sql = "call addBook('$id', '$title', '$author', '$available')";
        $result = $conn->query($sql);
        mysqli_next_result($conn);
        //TODO how to know if it was added correctly?
        if($result){
            $sql1 = "call addBookToShelf('$id','$shelf')";
            $result1 = $conn->query($sql1);
            if($result1){
                echo "1";
            }
        }else{
            echo "Error: " . $sql . "<br>" . $conn->error;;
        }
    }else{
        echo "Shelf is full, try another!";
    }
}

if(strcmp($action,"deleteBook")==0){
    $id = $_REQUEST["BookID"];

    $sql = "call deleteBook('$id')";
    $result = $conn->query($sql);
    mysqli_next_result($conn);
    //TODO how to know if it was deleted correctly?
    if($result){
        echo "1";
    }else{
        echo "Error: " . $sql . "<br>" . $conn->error;;
    }
}

if(strcmp($action,"checkLoanHistory")==0) {
    $username = $_REQUEST["userNAME"];
    //array of books that the user has borrowed
    $books = array();

    $sql = "call borrowHistoryOfUser('$username')";
    $result = $conn->query($sql);
    mysqli_next_result($conn);
    //TODO how to know if it was deleted correctly?
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
        echo json_encode($books);
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
if(strcmp($action,"borrowBook")==0){
    $bookID = $_REQUEST["bookID"];


    $sql = "call checkIfBookIsAvailable('$bookID')";
    $result = $conn->query($sql);
    mysqli_next_result($conn);
    if($result->num_rows > 0){
        $result->close();
        $sql1 = "call borrowBook('$loggedInUser','$bookID')";
        $result1 = $conn->query($sql1);
        mysqli_next_result($conn);
        if($result1){
            echo "successfuly checked out book";
        }else{
            echo "Book could not be checked out";
        }
    }else{
        echo "Book is not available";
    }
}
if(strcmp($action,"returnBook")==0){
    $bookID = $_REQUEST["bookID"];

    $sql = "call checkIfUserHasBookCheckedOut('$loggedInUser','$bookID')";
    $result = $conn->query($sql);
    mysqli_next_result($conn);
    if($result->num_rows > 0){
        $result->close();
        $sql1 = "call returnBook('$loggedInUser','$bookID')";
        $result1 = $conn->query($sql1);
        mysqli_next_result($conn);
        if($result1){
            echo "successfuly returned book";
        }else{
            echo "Book could not be returned out";
        }
    }else{
        echo "Book is not available";
    }
}
if(strcmp($action,"showInfo")==0){
    $bookID = $_REQUEST["BookID"];

    $sql = "call showInfo('$bookID')";
    $result = $conn->query($sql);
    $info = array();
    if($result){
        while($row = $result->fetch_assoc()) {
            $info[] = $row;
        }
        echo json_encode($info);
    }else{
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
