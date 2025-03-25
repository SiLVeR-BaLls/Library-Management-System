<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];
    $users = json_decode($_POST['users'], true);

    if (!empty($users)) {
        $ids = implode("','", array_map('mysqli_real_escape_string', array_fill(0, count($users), $conn), $users));
        $query = "UPDATE users_info SET status_details='$status' WHERE IDno IN ('$ids')";
        if (mysqli_query($conn, $query)) {
            echo "Status updated successfully!";
        } else {
            echo "Error updating status: " . mysqli_error($conn);
        }
    } else {
        echo "No users selected.";
    }
}
?>
