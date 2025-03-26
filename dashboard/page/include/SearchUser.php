<?php
include '../../config.php';

// Get the search term from the request
$searchTerm = $_GET['IDno'];

// Query to search for the user by IDno, Fname, or Sname, only where status is 'approved'
$sql = "SELECT ui.IDno,
               ui.Fname,
               ui.Sname,
               ui.U_Type
        FROM users_info ui
        WHERE (ui.IDno LIKE ? OR ui.Fname LIKE ? OR ui.Sname LIKE ?)
          AND ui.status_log = 'approved'";

$stmt = $conn->prepare($sql);
$searchParam = "%$searchTerm%";
$stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();

// Display results with clickable selection
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $userID = htmlspecialchars($row['IDno']);
        $userName = htmlspecialchars($row['Fname'] . ' ' . $row['Sname']);
        $userType = htmlspecialchars($row['U_Type']);
        
        // Each result is a clickable div that will call the selectUser() function in JavaScript
        echo "<div class='p-2 cursor-pointer hover:bg-gray-200 search-item' 
              data-id='{$userID}' 
              onclick=\"selectUser('{$userID}', '{$userName}')\">
                <strong>User ID:</strong> $userID <br>
                <strong>Name:</strong> $userName <br>
                <strong>User Type:</strong> $userType
              </div>";
    }
} else {
    echo "<div>No approved user found matching: " . htmlspecialchars($searchTerm) . "</div>";
}

// Close the connection
$stmt->close();
$conn->close();
?>
