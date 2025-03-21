<?php
// Configuration
include '../../config.php';

// Initialize message variables
$message = "";
$message_type = "";

// Check connection
if ($conn->connect_error) {
    $message = "Connection failed: " . $conn->connect_error;
    $message_type = "error";
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get POST data
    $B_title = $_POST['B_title'] ?? ''; // Use B_title directly from the form
    $book_id = $_POST['book_id'] ?? ''; // Use book_id directly from the form
    $copy_ID = $_POST['copy_ID'] ?? '';
    $status = $_POST['status'] ?? '';
    $copyNumber = max(1, intval($_POST['copyNumber'] ?? 1)); // Default to 1 if not set
    $callNumber = $_POST['callNumber'] ?? '';
    $circulationType = $_POST['circulationType'] ?? '';
    $dateAcquired = $_POST['dateAcquired'] ?? '';
    $description1 = $_POST['description1'] ?? '';
    $description2 = $_POST['description2'] ?? '';
    $description3 = $_POST['description3'] ?? '';
    $number1 = $_POST['number1'] ?? '';
    $number2 = $_POST['number2'] ?? '';
    $number3 = $_POST['number3'] ?? '';
    $sublocation = $_POST['sublocation'] ?? '';
    $vendor = $_POST['vendor'] ?? '';
    $fundingSource = $_POST['fundingSource'] ?? '';
    $rating = $_POST['rating'] ?? '';
    $note = $_POST['note'] ?? '';

    // Validate required fields
    if (empty($copy_ID) || empty($callNumber) || empty($status) || empty($B_title)) {
        $message = "Required fields cannot be empty.";
        $message_type = "error";
    } elseif (!DateTime::createFromFormat('Y-m-d', $dateAcquired)) {
        $message = "Invalid date format.";
        $message_type = "error";
    } else {
        // Prepare the insert statement, including B_title
        $sql = "INSERT INTO book_copies (copy_ID, status, callNumber, circulationType, dateAcquired, description1, description2, description3, number1, number2, number3, sublocation, vendor, fundingSource, rating, note, B_title, book_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssssssssssssssssi", $copy_ID, $status, $callNumber, $circulationType, $dateAcquired, $description1, $description2, $description3, $number1, $number2, $number3, $sublocation, $vendor, $fundingSource, $rating, $note, $B_title, $book_id);

            // Insert multiple copies without modifying copy_ID
            $conn->begin_transaction();
            try {
                for ($i = 0; $i < $copyNumber; $i++) {
                    if (!$stmt->execute()) {
                        throw new Exception("Error inserting book copy: " . $stmt->error);
                    }
                }
                $conn->commit();
                $message = "$copyNumber book copies registered successfully!";
                $message_type = "success";
            } catch (Exception $e) {
                $conn->rollback();
                $message = $e->getMessage();
                $message_type = "error";
            }
            $stmt->close();
        } else {
            $message = "Error preparing insert statement: " . $conn->error;
            $message_type = "error";
        }
    }
}
?>


<!-- SweetAlert2 CSS and JavaScript -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        <?php if (!empty($message)): ?>
            let returnValue = 0; // Default return value
            if ("<?php echo $message_type; ?>" === "success") {
                returnValue = -2; // Success return value
            } else {
                returnValue = -1; // Error return value
            }

            Swal.fire({
                icon: '<?php echo $message_type; ?>',
                title: '<?php echo $message_type === "success" ? "Success" : "Error"; ?>',
                text: '<?php echo htmlspecialchars($message); ?>',
                confirmButtonColor: '#3085d6',
                iconColor: '<?php echo $message_type === "success" ? "#28a745" : "#dc3545"; ?>',
            }).then(() => {
                // Redirect based on the return value
                if (returnValue === -2) {
                    history.go(-2); // Go back 2 pages on success
                } else {
                    history.go(-1); // Go back 1 page on error
                }
            });
        <?php endif; ?>
    });
</script>