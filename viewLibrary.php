<?php
/**
 * Created by PhpStorm.
 * User: Austin
 * Date: 19-Oct-16
 * Time: 9:41 PM
 */
session_start();

//TODO
//1. select join to view shelves/books
//      echo results to html-will display in a table format
?>
<HTML>
<HEAD>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <title>Library</title>

    <style>
        h1{
            color: green;
            text-align: center;}
        td.required{
            color: red;}
        td.indent{
            text-indent: 4em;
        }
        tr:hover{
            background-color:#f5f5f5}
        p.uppercase {
            text-transform: uppercase;}
        p.lowercase {
            text-transform: lowercase;}
        p.capitalize {
            text-transform: capitalize;}
    </style>
</HEAD>

<h1>Team 10 Library</h1>

<strong><div id="userID" style="text-align: right"></div></strong>

<div id="history"></div>

<div id="library"></div>

<br><br>
<div id="L_add" style="display: none">
    <table>
        <tr>
            <td><b>Add book</b>

            </td><td></td><td></td><td class="indent">&nbsp;</td>
            <td class="L_delete"><b>Delete book</b></td>

            <td></td><td class="indent">&nbsp;</td>
            <td class="L_laonH"><b>Check Loan History</b></td>
        </tr>
        <tr>
            <td>BookID:</td>
            <td><input type="text" id="bookID"/></td>
            <td class="required">*Must be unique</td>

            <td class="indent">&nbsp;</td>
            <td class="L_delete">BookID:</td>
            <td class="L_delete"><input type="text" id="bookIDToDelete"/></td>

            <td class="indent">&nbsp;</td>
            <td class="L_laonH">User:</td>
            <td class="L_loanH"><input type="text" id="personToCheck"/></td>
        </tr>
        <tr>
            <td>Title:</td>
            <td><input type="text" id="title"/></td>

            <td></td><td class="indent">&nbsp;</td>
            <td class="L_delete"><button onclick="deleteBook()">Delete Book</button></td>

            <td></td><td>&nbsp;</td>
            <td><button onclick="displayInput(document.getElementById("personToCheck").value)">Check</button></td>
        </tr>
        <tr>
            <td>Author:</td>
            <td><input type="text" id="author"/></td>
        </tr>
        <tr>
            <td>Available:</td>
            <td><input type="radio" name="availability" value="1">Yes</td>
        </tr>
        <tr>
            <td></td>
            <td><input type="radio" name="availability" value="0">No</td>
        </tr>
        <tr>
            <td>Shelf:</td>
            <td><input type="text" name="Shelf" id="shelf"></td>
        </tr>
        <tr>
            <td><button onclick="addBook()">Submit</button></td>
        </tr>
    </table>
</div>
<div class="S_add">
    <table>
        <tr>
            <td><b>Borrow Book</b>

            </td><td></td><td></td><td class="indent">&nbsp;</td>
            <td class="S_borrow"><b>Return Book</b></td>
        </tr>
        <tr>
            <td>BookID:</td>
            <td><input type="text" id="bookIDToBorrow"/></td>
            <td class="required">*Must be unique</td>

            <td class="indent">&nbsp;</td>
            <td class="L_delete">BookID:</td>
            <td class="L_delete"><input type="text" id="bookIDToReturn"/></td>
        </tr>
        <tr>
            <td></td><td class="indent">&nbsp;</td>
            <td class="L_delete"><button onclick="borrowBook()">Borrow Book</button></td>

            <td></td><td>&nbsp;</td>
            <td class="L_delete"><button onclick="returnBook()">Return Book</button></td>
        </tr>
    </table>
</div>


<script>
    $(document).ready(function () {
        displayBooks();
    });
</script>
<script>
    function displayInput(text) {
        alert(text);
    }
</script>
<script>
    function borrowBook(){
        $.get("libraryFunctions.php?action=borrowBook&bookID="+document.getElementById("bookIDToBorrow").value,
        function(data,status){
            alert(data);
        });
        var loggedInUser = "<?php echo $_SESSION["username"]; ?>";
        checkLoanHistory(loggedInUser);
        displayBooks();
    }
</script>
<script>
    function returnBook(){
        $.get("libraryFunctions.php?action=returnBook&bookID="+document.getElementById("bookIDToReturn").value,
            function(data,status){
                alert(data);
            });
        var loggedInUser = "<?php echo $_SESSION["username"]; ?>";
        checkLoanHistory(loggedInUser);
        displayBooks();
    }
</script>
<script>
    function displayBooks() {
        $.get("libraryFunctions.php?action=userID", function(data,status){
                $("#userID").text(data);
        });

        $.get("libraryFunctions.php?action=loadBooks", function (data, status) {
            var dataObj = JSON.parse(data);
            var i;
            var books = [];
            var book;
            var avail = "Yes";
            var Ltext = "<table>" + "<tr>" + "<td>" + "Book ID" + "</td>" +
                "<td>" + "Book Title" + "</td>" +
                "<td>" + "Author" + "</td>" +
                "<td>" + "Available" + "</td>" + "</tr>";
            for (i = 0; i < dataObj.length; i++) {
                if (dataObj[i]["Availability"] == 1) {
                    avail = "Yes";
                } else if (dataObj[i]["Availability"] == 0) {
                    avail = "No";
                }
                //myDiv.text("BookID: "+dataObj[i]["BookID"]+", Book Title: "+dataObj[i]["BookTitle"]+", Author: "+dataObj[i]["Author"]+", Availability: "+dataObj[i]["Availability"]);
                Ltext += "<tr class='Book'>" +
                    "<td>" + dataObj[i]["BookID"] + "</td>" +
                    "<td>" + dataObj[i]["BookTitle"] + "</td>" +
                    "<td>" + dataObj[i]["Author"] + "</td>" +
                    "<td>" + avail + "</td>" +
                    "</tr>";
                book = new Book(dataObj[i]["BookID"],dataObj[i]["BookTitle"],dataObj[i]["Author"],dataObj[i]["Availability"]);
                books.push(book);
            }

            Ltext += "</table>";
            $("#library").html(Ltext);
            attachHandlers();
        });
    }
</script>
<script>
    function attachHandlers(){
        $(".Book").click(function(){
            var info = [];
            $(this).find('td').each (function() {
                // do your cool stuff
                var cellText = $(this).html();
                info.push(cellText);
            });
            $.get("libraryFunctions.php?BookID="+info[0]+"&action=showInfo",
                function (data,status) {
                var dataObj = JSON.parse(data);
                var i;
                for(i=0;i<dataObj.length;i++){
                    var avail;
                    if (dataObj[i]["Availability"] == 1) {
                        avail = "Yes";
                    } else if (dataObj[i]["Availability"] == 0) {
                        avail = "No";
                    }
                    alert("Book Title: "+dataObj[i]["BookTitle"]+", Shelf: "+dataObj[i]["ShelfName"]+", Author: "+dataObj[i]["Author"]+", Availability: "+avail);
                }
            });
        });
    }
</script>
<script>
    function Book(givenID, givenTitle, givenAuthor, givenAvailability){
        this.ID = givenID;
        this.title = givenTitle;
        this.author = givenAuthor;
        this.avail = givenAvailability;
    }
    Book.prototype.getDetails = function() {
        var text = "Book ID: "+ this.ID + "\nBook Title: "+ this.title + "\nAuthor: " + this.author + "\nAvailable: " + this.avail;

        $("#details").text(text);
    };
</script>
<script>
    function addBook(){
        //TODO
        var available = document.getElementsByName('availability');
        var availableResult;
        for (var i = 0, length = available.length; i < length; i++) {
            if (available[i].checked) {
                // do whatever you want with the checked radio
                availableResult = available[i].value;

                // only one radio can be logically checked, don't check the rest
                break;
            }
        }
        $.get("libraryFunctions.php?BookID="+document.getElementById("bookID").value+
                "&bookTitle="+document.getElementById("title").value+
                "&author="+document.getElementById("author").value+
                "&available="+availableResult+
                "&shelf="+document.getElementById("shelf").value+
                "&action=addBook",
            function (data,status){
                alert(data);
                if(data==1){
                    alert("Book added successfully.");
                }else{
                    alert("Book could not be added.");
                }
            });
        displayBooks();
    }
</script>
<script>
    function deleteBook(){
        //TODO
        //call to delete from table
        $.get("libraryFunctions.php?BookID="+document.getElementById("bookIDToDelete").value+
            "&action=deleteBook",
            function (data,status){
            alert(data);
            if(data==1){
                alert("Book deleted.");
            }else{
                alert("Book could not be deleted.");
            }
        });
        displayBooks();
    }
</script>
<script>
    function checkLoanHistory(userName){
        //TODO
        //call to delete from table
        $.get("libraryFunctions.php?userNAME="+userName+
            "&action=checkLoanHistory",
            function (data,status) {
                alert(data);
                var dataObj = JSON.parse(data);
                var i;
                $("#history").empty();
                for (i = 0; i < dataObj.length; i++) {
                    $("#history").append("UserName: " + dataObj[i]["UserName"] + ", BookID: " + dataObj[i]["BookID"] + ", Due Date: " + dataObj[i]["DueDate"] + ", Returned Date: " + dataObj[i]["ReturnedDate"]);
                    $("#history").append("<br />");
                }
            }
        );
    }
</script>
<script>
    function showLibrianFunctions() {
        $("#L_add").show();
        $("#L_delete").show();
    }
</script>
<script>
    function logout(){
        $.get("logout.php?",
            function () {
                window.location = "Login.html";
            });
     }
</script>
<?php
$username = "dbu319t10";
$passwordServer = "spUj4h?h";
$dbServer = "mysql.cs.iastate.edu";
$dbName   = "db319t10";
$conn = new mysqli($dbServer, $username, $passwordServer, $dbName);
$loggedInUser = $_SESSION["username"];

$sql = "call checkIfLibrarian('$loggedInUser');";

$result = $conn->query($sql);
//If the sessions librarian variable is true, buttons will show, otherwise they will be hidden
//TODO commented out for editing purposes
if($result->num_rows>0) {
    echo "<script> showLibrianFunctions();</script>";
}
?>
</html>