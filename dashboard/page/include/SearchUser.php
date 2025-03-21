<?php
include '../../config.php';

// Get the IDno from the request
$IDno = $_GET['IDno'];

// Query to search for the user by IDno, only where status is 'approved'
$sql = "SELECT ui.IDno,
       ui.Fname,
       ui.U_Type
FROM users_info ui
WHERE ui.IDno LIKE ? 
  AND ui.status_log = 'approved';
";

$stmt = $conn->prepare($sql);
$searchParam = "%$IDno%";
$stmt->bind_param("s", $searchParam);
$stmt->execute();
$result = $stmt->get_result();

// Display results with clickable selection
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $userID = htmlspecialchars($row['IDno']);
        $userName = htmlspecialchars($row['Fname']);
        $userType = htmlspecialchars($row['U_Type']);
        
        // Each result is a clickable div that will call the selectUser() function in JavaScript
        echo "<div onclick=\"selectUser('{$userID}', '{$userName}')\" class='p-2 cursor-pointer hover:bg-gray-200'>
                <strong>User ID:</strong> $userID <br>
                <strong>Name:</strong> $userName <br>
                <strong>User Type:</strong> $userType
              </div>";
    }
} else {
    echo "<div>No approved user found with ID: " . htmlspecialchars($IDno) . "</div>";
}

// Close the connection
$stmt->close();
$conn->close();
?>
