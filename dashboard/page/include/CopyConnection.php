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
     $sublocation = $_POST['Sublocation'] ?? '';
     $vendor = $_POST['vendor'] ?? '';
     $fundingSource = $_POST['fundingSource'] ?? '';
     $rating = $_POST['rating'] ?? '';
     $note = $_POST['note'] ?? '';
    // Validate required fields
    if (empty($callNumber) || empty($status) || empty($B_title)) {
        $message = "Required fields cannot be empty.";
        $message_type = "error";
    } elseif (!DateTime::createFromFormat('Y-m-d', $dateAcquired)) {
        $message = "Invalid date format. Please use YYYY-MM-DD.";
        $message_type = "error";
    } else {
        // Begin transaction for data integrity
        $conn->begin_transaction();
        try {
            // Prepare the insert statement, including B_title and book_copy.  B_title is already included.
            $sql = "INSERT INTO book_copies (book_copy, status, callNumber, circulationType, dateAcquired, description1, description2, description3, number1, number2, number3, Sublocation, vendor, fundingSource, rating, note, B_title, book_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . $conn->error);
            }

            for ($i = 0; $i < $copyNumber; $i++) {
                // Get next book_copy ID
                $result = $conn->query("SELECT IFNULL(MAX(CAST(SUBSTRING(book_copy, 5) AS UNSIGNED)), 0) + 1 AS next_id FROM book_copies");
                if (!$result) {
                    throw new Exception("Error querying for next_id: " . $conn->error);
                }
                $row = $result->fetch_assoc(); // Use fetch_assoc()
                $next_id = $row['next_id'];
                $book_copy = 'BOOK' . str_pad($next_id, 7, '0', STR_PAD_LEFT);

                // Bind parameters. B_title was already in the parameter list, no changes needed here.
                $stmt->bind_param("sssssssssssssssssi", $book_copy, $status, $callNumber, $circulationType, $dateAcquired, $description1, $description2, $description3, $number1, $number2, $number3, $sublocation, $vendor, $fundingSource, $rating, $note, $B_title, $book_id);

                if (!$stmt->execute()) {
                    throw new Exception("Error inserting record: " . $stmt->error);
                }
            }

            // Commit the transaction
            $conn->commit();
            $message = "$copyNumber book copy(ies) added successfully with custom IDs!";
            $message_type = "success";
        } catch (Exception $e) {
            // Rollback the transaction on error
            $conn->rollback();
            $message = "Error: " . $e->getMessage(); // Detailed error message
            $message_type = "error";
        } finally {
            // Close the statement
            if ($stmt) {
                $stmt->close();
            }
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
            Swal.fire({
                icon: '<?php echo $message_type; ?>',
                title: '<?php echo $message_type === "success" ? "Success" : "Error"; ?>',
                text: '<?php echo htmlspecialchars($message); ?>',
                confirmButtonColor: '#3085d6',
                iconColor: '<?php echo $message_type === "success" ? "#28a745" : "#dc3545"; ?>',
            }).then(() => {
                // Redirect based on the message type
                if ("<?php echo $message_type; ?>" === "success") {
                    history.go(-2); // Go back 2 pages on success
                } else {
                    history.go(-1); // Go back 1 page on error
                }
            });
        <?php endif; ?>
    });
</script>