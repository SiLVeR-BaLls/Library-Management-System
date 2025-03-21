<?php
    
    // Initialize message variables
    $message = "";
    $message_type = "";

    // Get the copy ID from the URL
    $ID = isset($_GET['book_copy_ID']) ? $_GET['book_copy_ID'] : '';

    // Handle deletion when confirmed
    if ($ID && isset($_GET['confirm_delete']) && $_GET['confirm_delete'] === 'true') {
        $delete_sql = "DELETE FROM book_copies WHERE book_copy_ID = '" . $conn->real_escape_string($ID) . "'";

        if ($conn->query($delete_sql) === TRUE) {
            $message = "Copy deleted successfully.";
            $message_type = "success";
        } else {
            $message = "Error deleting copy: " . $conn->error;
            $message_type = "error";
        }
    } elseif (!$ID) {
        $message = "No copy ID specified.";
        $message_type = "warning";
    }
?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script>
        // Show the confirmation dialog
        function confirmDelete() {
            if (confirm("Are you sure you want to delete this copy?")) {
                window.location.href = window.location.href + "&confirm_delete=true"; // Trigger deletion
            } else {
                window.location.href = "javascript:history.go(-1);"; // Go back if cancelled
            }
        }

        // Run confirmation function on page load if message exists
        window.onload = function() {
            if (<?php echo json_encode($message != ""); ?>) {
                confirmDelete();
            }
        };
    </script>
    <div class="container mt-4">
        <h2 id="book_copy_ID">Delete Copy</h2>
        <div class="alert alert-<?php echo $message_type; ?>" id="book_copy_ID_message_alert">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <button onclick="confirmDelete();" class="btn btn-danger mb-3" id="book_copy_ID_delete_button">Delete Copy</button>
        <button onclick="window.history.back();" class="btn btn-secondary mb-3" id="book_copy_ID_back_button">Cancel</button>
    </div>

