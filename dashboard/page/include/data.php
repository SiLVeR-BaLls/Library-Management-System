<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$sql = "SELECT * FROM book_copies";
$result = $conn->query($sql);
echo $result->num_rows;

// if ($result->num_rows > 0) {
//     // output data of each row
//     while($row = $result->fetch_assoc()) {
//         echo "book_copy_ID: " . $row["book_copy_ID"]. " - lms: " . $row["B_title"];
//     }
// } else {
//     echo "0 results";
// }

$conn->close();
?>