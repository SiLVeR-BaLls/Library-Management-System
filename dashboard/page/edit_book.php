<?php
include '../config.php';

// Initialize message variables
$message = "";
$message_type = "";

// Get the book title from the query string
$title = $_GET['title'] ?? ''; // Use null coalescing operator to handle missing 'title'

if ($title) {

  // Fetch book
  $sql = "SELECT * FROM book WHERE book_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $title);
  if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      // Fetch the book details
      $book = $result->fetch_assoc();
    } else {
      // No book found with the provided book_id
      $message = "No book found with that ID.";
      $message_type = "error";
    }
  }
  $stmt->close();
  // Get co-authors data
  $coAuthorsQuery = "SELECT * FROM coauthor WHERE book_id = ?";
  $stmt = $conn->prepare($coAuthorsQuery);
  $stmt->bind_param("s", $title);  // Ensure $title is passed
  $stmt->execute();
  $coAuthorsResult = $stmt->get_result();

  // Get subject data
  $subjectQuery = "SELECT * FROM subject WHERE book_id = ?";
  $stmt = $conn->prepare($subjectQuery);
  $stmt->bind_param("s", $title);  // Ensure $title is passed
  $stmt->execute();
  $subjectResult = $stmt->get_result();
} else {
  // No book title provided
  $message = "No book title provided.";
  $message_type = "error";
}

// Handle form submission for updating book details, co-authors, and subjects
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {

  // Variables to hold any message or error
  $message = '';
  $message_type = '';

  // ** Handle Co-authors Update **
  if (!empty($_POST['Co_Name']) && !empty($_POST['Co_Date']) && !empty($_POST['Co_Role']) && !empty($_POST['co_author_ids'])) {
    $Co_Name = $_POST['Co_Name'];
    $Co_Date = $_POST['Co_Date'];
    $Co_Role = $_POST['Co_Role'];
    $co_author_ids = $_POST['co_author_ids'];

    for ($i = 0; $i < count($Co_Name); $i++) {
      $name = $conn->real_escape_string($Co_Name[$i]);
      $date = $conn->real_escape_string($Co_Date[$i]);
      $role = $conn->real_escape_string($Co_Role[$i]);
      $co_author_id = (int)$co_author_ids[$i]; // Get the co_author_id from the form

      // Update co-author based on book_id and co_author_id
      $updateQuery = "UPDATE coauthor 
                            SET Co_Name='$name', Co_Date='$date', Co_Role='$role' 
                            WHERE co_author_id=$co_author_id AND book_id=" . (int)$book['book_id'];

      if ($conn->query($updateQuery) !== TRUE) {
        $message = "Error updating co-author: " . $conn->error;
        $message_type = "error";
      }
    }
  }

  // ** Handle Subjects Update **
  if (!empty($_POST['subject_heads']) && !empty($_POST['subject_inputs']) && !empty($_POST['subject_ids'])) {
    $subject_heads = $_POST['subject_heads'];
    $subject_inputs = $_POST['subject_inputs'];
    $subject_ids = $_POST['subject_ids'];

    for ($i = 0; $i < count($subject_heads); $i++) {
      $head = $conn->real_escape_string($subject_heads[$i]);
      $input = $conn->real_escape_string($subject_inputs[$i]);
      $subject_id = (int)$subject_ids[$i];

      // Update subject based on book_id and subject_id
      $updateQuery = "UPDATE subject 
                            SET Sub_Head='$head', Sub_Head_input='$input' 
                            WHERE subject_id=$subject_id AND book_id=" . (int)$book['book_id'];

      if ($conn->query($updateQuery) !== TRUE) {
        $message = "Error updating subject: " . $conn->error;
        $message_type = "error";
      }
    }
  }

  // ** Handle Book Details Update and File Upload **
  if (empty($message)) {
    // Process file upload for book photo
    $photoPath = $book['photo']; // Default to existing photo
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; // Allowed MIME types

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
      $fileType = mime_content_type($_FILES['photo']['tmp_name']);
      $fileSize = $_FILES['photo']['size'];

      if (!in_array($fileType, $allowedTypes) || $fileSize > 2 * 1024 * 1024) { // 2 MB limit
        $message = "Invalid image format or file too large.";
        $message_type = "error";
      } else {
        $uploadDir = '../../pic/Book/';
        if (!is_dir($uploadDir)) {
          mkdir($uploadDir, 0755, true);
        }

        // Delete existing photo if present
        if (!empty($book['photo'])) {
          unlink($uploadDir . $book['photo']);
        }

        // Handle new file upload
        $fileName = uniqid() . '_' . basename($_FILES['photo']['name']);
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $fileName)) {
          $photoPath = $fileName;
        } else {
          $message = "Failed to upload photo.";
          $message_type = "error";
        }
      }
    }

    // ** Update Book Information **
    if (empty($message)) {
      $sql = "UPDATE book SET subtitle=?, author=?, edition=?, LCCN=?, ISBN=?, ISSN=?, MT=?, ST=?, place=?, publisher=?, Pdate=?, copyright=?, extent=?, Odetail=?, size=?, Description=?, url=?, UTitle=?, VForm=?, SUTitle=?, photo=? WHERE book_id=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssssssssssssssssssssss", $_POST['subtitle'], $_POST['author'], $_POST['edition'], $_POST['LCCN'], $_POST['ISBN'], $_POST['ISSN'], $_POST['MT'], $_POST['ST'], $_POST['place'], $_POST['publisher'], $_POST['Pdate'], $_POST['copyright'], $_POST['extent'], $_POST['Odetail'], $_POST['size'], $_POST['Description'], $_POST['url'], $_POST['UTitle'], $_POST['VForm'], $_POST['SUTitle'], $photoPath, $title);

      if (!$stmt->execute()) {
        $message = "Error updating book: " . $stmt->error;
        $message_type = "error";
      }
      $stmt->close();
    }
  }

  // ** Redirect After Update or Error **
  if (empty($message)) {
    // Redirect to the book view page on success
    header("Location: ViewBook.php?message=success&title=" . urlencode($title));
    exit();
  }
}


// Handle book deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
  // Get the photo filename before deleting the book record
  $photoToDelete = $book['photo'] ?? '';

  $deleteSql = "DELETE FROM Book WHERE book_id = ?";
  $deleteStmt = $conn->prepare($deleteSql);
  $deleteStmt->bind_param("s", $title);

  if ($deleteStmt->execute()) {
    // Check if the photo exists and delete it from the directory
    if ($photoToDelete && file_exists("../../pic/Book/" . $photoToDelete)) {
      unlink("../../pic/Book/" . $photoToDelete);
    }

    // Redirect to index page after deletion
    header("Location: index.php?message=deleted&title=" . urlencode($title));
    exit();
  } else {
    $message = "Error deleting book: " . $deleteStmt->error;
    $message_type = "error";
  }
  $deleteStmt->close();
}

// Handle Co-authors Update
if (isset($_POST['update'])) {
  if (!empty($_POST['Co_Name']) && !empty($_POST['Co_Date']) && !empty($_POST['Co_Role']) && !empty($_POST['co_author_ids'])) {
    $Co_Name = $_POST['Co_Name'];
    $Co_Date = $_POST['Co_Date'];
    $Co_Role = $_POST['Co_Role'];
    $co_author_ids = $_POST['co_author_ids']; // Assuming this field is added to your form

    for ($i = 0; $i < count($Co_Name); $i++) {
      $name = $conn->real_escape_string($Co_Name[$i]);
      $date = $conn->real_escape_string($Co_Date[$i]);
      $role = $conn->real_escape_string($Co_Role[$i]);
      $co_author_id = (int)$co_author_ids[$i]; // Get the co_author_id from the form

      // Update co-author based on book_id and co_author_id
      $updateQuery = "UPDATE coauthor 
                              SET Co_Name='$name', Co_Date='$date', Co_Role='$role' 
                              WHERE co_author_id=$co_author_id AND book_id=" . (int)$book['book_id'];

      if ($conn->query($updateQuery) !== TRUE) {
        echo "Error updating co-author: " . $conn->error;
      }
    }
  }
}
// Handle Subjects Update
if (isset($_POST['update'])) {
  if (!empty($_POST['subject_heads']) && !empty($_POST['subject_inputs']) && !empty($_POST['subject_ids'])) {
    $subject_heads = $_POST['subject_heads'];
    $subject_inputs = $_POST['subject_inputs'];
    $subject_ids = $_POST['subject_ids']; // Assuming this field is added to your form

    for ($i = 0; $i < count($subject_heads); $i++) {
      $head = $conn->real_escape_string($subject_heads[$i]);
      $input = $conn->real_escape_string($subject_inputs[$i]);
      $subject_id = (int)$subject_ids[$i]; // Get the subject_id from the form

      // Update subject based on book_id and subject_id
      $updateQuery = "UPDATE subject 
                              SET Sub_Head='$head', Sub_Head_input='$input' 
                              WHERE subject_id=$subject_id AND book_id=" . (int)$book['book_id'];

      if ($conn->query($updateQuery) !== TRUE) {
        echo "Error updating subject: " . $conn->error;
      }
    }
  }
}
?>

<title>Edit Book</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  .body_contain {
    padding: 20px;
  }

  .book-card {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 5px;
    margin-bottom: 20px;
  }

  .form-section {
    margin-bottom: 15px;
  }
</style>

<!-- Main Content Area with Sidebar and BrowseBook Section -->
<main class="flex  ">
  <!-- Sidebar Section -->
          <?php include $sidebars[$userType] ?? ''; ?>
  <!-- BrowseBook Content Section -->
  <div class="flex-grow ">
    <!-- Header at the Top -->
    <?php include 'include/header.php'; ?>

    <div class="container mx-auto px-4 py-6 ">

      <!-- Breadcrumb Section -->
      <div class="text-sm text-gray-600 mb-4">
        <a href="index.php" class="hover:text-blue-800 hover:underline">Home</a> &rarr;

        <?php if (isset($book) && $book): ?>
          <a href="ViewBook.php?title=<?php echo urlencode($book['book_id']); ?>"
            class="hover:text-blue-800 hover:underline">
            <?php echo htmlspecialchars($book['B_title']); ?>
          </a> &rarr;
          <a href="edit_book.php?title=<?php echo urlencode($book['book_id']); ?>"
            class="hover:text-blue-800 hover:underline">Edit Copy</a>
        <?php else: ?>
          <span>Book not found</span>
        <?php endif; ?>
      </div>


      <a href="ViewBook.php?title=<?php echo urlencode($book['book_id']); ?>"
        class="hover:text-blue-800 hover:underline">&larr; Back</a>

      <div class="text-center">

        <?php if (isset($book)): ?>
          <h2 class="text-3xl font-semibold mt-4">
            <?php echo htmlspecialchars($book['B_title']); ?>
          </h2>
          <div class="book-title">Editing:</div>
      </div>
      <form method="POST" enctype="multipart/form-data" class="book-card">


        <!-- photo  -->
        <div class="form-group">

          <?php if ($message): ?>
            <div class="alert alert-<?php echo htmlspecialchars($message_type); ?>">
              <?php echo htmlspecialchars($message); ?>
            </div>
          <?php endif; ?>
          <label for="photo">Upload Photo:</label>
          <?php if (!empty($book['photo'])): ?>
            <img src="../../pic/Book/<?php echo htmlspecialchars($book['photo']); ?>" alt="Book Photo"
              class="w-48 h-48 mx-auto rounded-lg shadow-md">
          <?php else: ?>
            <img src="../../pic/default/book.jpg" alt="Default Book Photo"
              class="w-48 h-48 mx-auto rounded-lg shadow-md">
          <?php endif; ?>

          <input type="file" id="photo" class="form-control" name="photo" accept="image/*">
        </div>

        <!-- brief title -->
        <div class="row">
          <h3>Title</h3>
          <!-- Form fields for book details (add the fields as necessary) -->
          <div class="form-section col-md-6">
            <label for="subtitle">Subtitle:</label>
            <input type="text" class="form-control" id="subtitle" name="subtitle"
              value="<?php echo htmlspecialchars($book['subtitle']); ?>">
          </div>
          <div class="form-section col-md-6">
            <label for="author">Author:</label>
            <input type="text" class="form-control" id="author" name="author"
              value="<?php echo htmlspecialchars($book['author']); ?>">
          </div>
          <div class="form-section col-md-6">
            <label for="edition">Edition:</label>
            <input type="text" class="form-control" id="edition" name="edition"
              value="<?php echo htmlspecialchars($book['edition']); ?>">
          </div>
          <div class="form-section col-md-6">
            <label for="LCCN">LCCN:</label>
            <input type="text" class="form-control" id="LCCN" name="LCCN"
              value="<?php echo htmlspecialchars($book['LCCN']); ?>">
          </div>
          <div class="form-section col-md-6">
            <label for="ISBN">ISBN:</label>
            <input type="text" class="form-control" id="ISBN" name="ISBN"
              value="<?php echo htmlspecialchars($book['ISBN']); ?>">
          </div>
          <div class="form-section col-md-6">
            <label for="ISSN">ISSN:</label>
            <input type="text" class="form-control" id="ISSN" name="ISSN"
              value="<?php echo htmlspecialchars($book['ISSN']); ?>">
          </div>
          <div class="form-section col-md-6">
            <label for="MT">Material Type:</label>
            <input type="text" class="form-control" id="MT" name="MT"
              value="<?php echo htmlspecialchars($book['MT']); ?>">
          </div>
          <div class="form-section col-md-6">
            <label for="ST">Shelf Type:</label>
            <input type="text" class="form-control" id="ST" name="ST"
              value="<?php echo htmlspecialchars($book['ST']); ?>">
          </div>
          <div class="form-section col-md-6">
            <label for="place">Place:</label>
            <input type="text" class="form-control" id="place" name="place"
              value="<?php echo htmlspecialchars($book['place']); ?>">
          </div>
          <div class="form-section col-md-6">
            <label for="publisher">Publisher:</label>
            <input type="text" class="form-control" id="publisher" name="publisher"
              value="<?php echo htmlspecialchars($book['publisher']); ?>">
          </div>
          <div class="form-section col-md-6">
            <label for="Pdate">Publication Date:</label>
            <input type="date" class="form-control" id="Pdate" name="Pdate"
              value="<?php echo htmlspecialchars($book['Pdate']); ?>">
          </div>
          <div class="form-section col-md-6">
            <label for="copyright">Copyright:</label>
            <input type="text" class="form-control" id="copyright" name="copyright"
              value="<?php echo htmlspecialchars($book['copyright']); ?>">
          </div>
          <div class="form-section col-md-6">
            <label for="extent">Extent:</label>
            <input type="text" class="form-control" id="extent" name="extent"
              value="<?php echo htmlspecialchars($book['extent']); ?>">
          </div>
          <div class="form-section col-md-6">
            <label for="Odetail">Other Details:</label>
            <textarea class="form-control" id="Odetail"
              name="Odetail"><?php echo htmlspecialchars($book['Odetail']); ?></textarea>
          </div>
          <div class="form-section col-md-6">
            <label for="size">Size:</label>
            <input type="text" class="form-control" id="size" name="size"
              value="<?php echo htmlspecialchars($book['size']); ?>">
          </div>
        </div>

        <!-- series -->
        <div class="row">
          <h3>series</h3>
          <div class="form-section col-md-6">
            <label for="volume">Volume:</label>
            <input type="text" class="form-control" id="volume" name="volume"
              value="<?php echo htmlspecialchars($book['volume']); ?>">
          </div>

        </div>

        <!-- resources -->
        <div class="row">
          <h3>Resources</h3>
          <div class="form-section col-md-6">
            <label for="url">URL</label>
            <input type="text" class="form-control" id="url" name="url"
              value="<?php echo htmlspecialchars($book['url']); ?>">
          </div>
          <div class="form-section col-md-6">
            <label for="Description">Description</label>
            <input type="text" class="form-control" id="Description" name="Description"
              value="<?php echo htmlspecialchars($book['Description']); ?>">
          </div>
        </div>


        <!-- alternate entries -->
        <div class="row">
          <h3>Alternate Entries</h3>
          <div class="form-section col-md-6">
            <label for="UTitle">Uniform Title</label>
            <input type="text" class="form-control" id="UTitle" name="UTitle"
              value="<?php echo htmlspecialchars($book['UTitle']); ?>">
          </div>
          <div class="form-section col-md-6">
            <label for="VForm">Varying Formm</label>
            <input type="text" class="form-control" id="VForm" name="VForm"
              value="<?php echo htmlspecialchars($book['VForm']); ?>">
          </div>
          <div class="form-section col-md-6">
            <label for="SUTitle">Series Uniform Title</label>
            <input type="text" class="form-control" id="SUTitle" name="SUTitle"
              value="<?php echo htmlspecialchars($book['SUTitle']); ?>">
          </div>
        </div>

        <!-- Co-authors Form -->
        <div class="flex">
          <div class="row">
            <h3>Co-Authors</h3>
            <?php if ($coAuthorsResult->num_rows > 0): ?>
              <ul>
                <?php while ($row = $coAuthorsResult->fetch_assoc()): ?>
                  <li>
                    <input type="hidden" name="co_author_ids[]" value="<?php echo $row['co_author_id']; ?>" />
                    <input type="date" name="Co_Date[]" value="<?php echo htmlspecialchars($row['Co_Date']); ?>" />
                    <input type="text" name="Co_Name[]" value="<?php echo htmlspecialchars($row['Co_Name']); ?>" />
                    <input type="text" name="Co_Role[]" value="<?php echo htmlspecialchars($row['Co_Role']); ?>" />
                  </li>
                <?php endwhile; ?>
              </ul>
            <?php endif; ?>
          </div>


          <!-- Subjects Form -->
          <div class="row">
            <h3>Subject</h3>

            <?php if ($subjectResult->num_rows > 0): ?>
              <ul>
                <?php while ($book = $subjectResult->fetch_assoc()): ?>
                  <li>
                    <input type="hidden" name="subject_ids[]" value="<?php echo $book['subject_id']; ?>" />

                    <input type="text" name="subject_heads[]" value="<?php echo htmlspecialchars($book['Sub_Head']); ?>" />
                    <input type="text" name="subject_inputs[]" value="<?php echo htmlspecialchars($book['Sub_Head_input']); ?>" />
                  </li>
                <?php endwhile; ?>
              </ul>
            <?php endif; ?>
          </div>
        </div>





        <!-- Update and Delete buttons -->
        <button type="submit" name="update" class="btn btn-primary" onclick="return confirmUpdate()">Update
          Book</button>
        <button type="submit" name="delete" class="btn btn-danger" onclick="return confirmDelete()">Delete
          Book</button>
    </div>

    </form>
  <?php endif; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Footer at the Bottom -->
  <footer class="bg-blue-600 text-white mt-auto">
    <?php include 'include/footer.php'; ?>
  </footer>
</main>

<script>
  // JavaScript functions to confirm actions
  function confirmUpdate() {
    return confirm("Are you sure you want to update this book's details?");
  }

  function confirmDelete() {
    return confirm("Are you sure you want to delete this book? This action cannot be undone.");
  }
</script>