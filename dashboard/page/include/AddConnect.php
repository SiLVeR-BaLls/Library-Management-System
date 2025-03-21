<?php
include '../../config.php';

// Initialize message variables
$message = "";
$message_type = "";

// Check connection
if ($conn->connect_error) {
  $message = "Connection failed: " . $conn->connect_error;
  $message_type = "error";
} else {
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get POST data for Book
    $B_title = $_POST['B_title'] ?? '';
    $subtitle = $_POST['subtitle'] ?? '';
    $author = filter_var($_POST['author'] ?? '', FILTER_SANITIZE_STRING);
    $edition = $_POST['edition'] ?? '';
    $LCCN = $_POST['LCCN'] ?? '';
    $ISBN = $_POST['ISBN'] ?? '';
    $ISSN = $_POST['ISSN'] ?? '';
    $MT = $_POST['MT'] ?? '';
    $ST = $_POST['ST'] ?? '';
    $place = $_POST['place'] ?? '';
    $publisher = $_POST['publisher'] ?? '';
    $Pdate = $_POST['Pdate'] ?? '';
    $copyright = $_POST['copyright'] ?? '';
    $extent = $_POST['extent'] ?? '';
    $Odetail = $_POST['Odetail'] ?? '';
    $size = $_POST['size'] ?? '';
    $volume = $_POST['volume'] ?? '';
    $url = $_POST['url'] ?? '';
    $Description = $_POST['Description'] ?? '';
    $UTitle = $_POST['UTitle'] ?? '';
    $VForm = $_POST['VForm'] ?? '';
    $SUTitle = $_POST['SUTitle'] ?? '';
    $note = $_POST['note'] ?? '';

    // Check for duplicate B_title
    $checkSql = "SELECT COUNT(*) FROM Book WHERE B_title = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $B_title);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
      $message = "A book with this title already exists.";
      $message_type = "error";
    } else {
      // Insert into Book table
      $sql = "INSERT INTO Book (B_title, UTitle, VForm, SUTitle, url, Description, volume, subtitle, author, edition, LCCN, ISBN, ISSN, MT, ST, place, publisher, Pdate, copyright, extent, Odetail, size, note) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      if (!executeStatement($conn, $sql, "ssssissssssssssssssssss", $B_title, $UTitle, $VForm, $SUTitle, $url, $Description, $volume, $subtitle, $author, $edition, $LCCN, $ISBN, $ISSN, $MT, $ST, $place, $publisher, $Pdate, $copyright, $extent, $Odetail, $size, $note)) {
        $message = "Error inserting book: " . $conn->error;
        $message_type = "error";
      } else {
        $message = "Book registration successful!";
        $message_type = "success";

        // Get the last inserted id
        $last_id = $conn->insert_id;

        // Insert CoAuthors
        $coAuthors = $_POST['Co_Name'] ?? []; // Array of co-author names
        $coAuthorDates = $_POST['Co_Date'] ?? []; // Array of co-author dates
        $coAuthorRoles = $_POST['Co_Role'] ?? []; // Array of co-author roles

        foreach ($coAuthors as $index => $coAuthor) {
          $coAuthorDate = $coAuthorDates[$index] ?? ''; // Ensure proper handling if there's no date
          $coAuthorRole = $coAuthorRoles[$index] ?? ''; // Ensure proper handling if there's no role

          // Prepare the insert statement for each co-author
          $stmt = $conn->prepare("INSERT INTO coauthor (book_id, Co_Name, Co_Date, Co_Role) VALUES (?, ?, ?, ?)");
          $stmt->bind_param("isss", $last_id, $coAuthor, $coAuthorDate, $coAuthorRole);

          // Execute the statement and check for errors
          if (!$stmt->execute()) {
            error_log("Error inserting co-author: " . $stmt->error); // Log errors for debugging
          }
        }

        // Insert subjects
        $subHeads = $_POST['Sub_Head'] ?? [];
        $subHeadsInputs = $_POST['Sub_Head_input'] ?? [];

        foreach ($subHeads as $index => $subHead) {
          $subHeadInput = $subHeadsInputs[$index] ?? '';

          // Insert each subject into the database
          $sql = "INSERT INTO subject (book_id, Sub_Head, Sub_Head_input) VALUES (?, ?, ?)";
          if (!executeStatement($conn, $sql, "iss", $last_id, $subHead, $subHeadInput)) {
            error_log("Error inserting subject: " . $conn->error); // Log error if insertion fails
          }
        }
      }
    }

    // Close the connection
    $conn->close();
  }
}

// Function to execute a prepared statement and return success status
function executeStatement($conn, $sql, $types, ...$params)
{
  $stmt = $conn->prepare($sql);
  if ($stmt === false) {
    // Log error for debugging
    error_log("SQL prepare failed: " . $conn->error);
    return false; // Return false if the statement preparation fails
  }
  $stmt->bind_param($types, ...$params);
  $result = $stmt->execute();
  if (!$result) {
    // Log error for debugging
    error_log("Execution failed: " . $stmt->error);
  }
  $stmt->close();
  return $result;
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<script>
  function showAlert(message, type) {
    if (type === 'success') {
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: message,
        didClose: () => {
          window.location.href = '../index.php'; // Redirect to the index page
        }
      });
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        didClose: () => {
          window.history.back(); // Redirect back on error
        }
      });
    }
  }

  // Check if there's a message and type
  <?php if ($message): ?>
    document.addEventListener('DOMContentLoaded', function() {
      var fullMessage = "<?php echo addslashes($message); ?>";
      var messageType = "<?php echo $message_type; ?>";

      showAlert(fullMessage, messageType);
    });
  <?php endif; ?>
</script>