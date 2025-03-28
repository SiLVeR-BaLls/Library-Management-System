<!-- ViewBook.php -->
<?php
    include '../config.php';

    // Initialize message variables
    $message = "";
    $message_type = "";

    // Define the target directory for photos
    $targetDir = '../../pic/Book/';

    // Get the book title from the query string
    $title = $_GET['title'] ?? '';

    if ($title) {
      // Fetch the book details
      $sql = "SELECT * FROM Book WHERE book_id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $title);

      if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
          $message = "No book found with that title.";
          $message_type = "error";
        } else {
          $book = $result->fetch_assoc();

          // Handle photo upload
          if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            // Validate image format
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $file_type = mime_content_type($_FILES['photo']['tmp_name']);

            if (!in_array($file_type, $allowed_types)) {
              echo "<script>alert('Invalid image format. The image format should be JPG, PNG, or GIF.');</script>";
              echo '<script>window.history.back();</script>';
              exit;
            }

            // Fetch the current photo from the database
            $sql = "SELECT photo FROM book WHERE book_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $title);
            $stmt->execute();
            $stmt->bind_result($currentPhoto);
            $stmt->fetch();
            $stmt->close();

            // Delete the current photo from the server if it exists
            if ($currentPhoto) {
              $current_photo_path = $targetDir . $currentPhoto;
              if (file_exists($current_photo_path)) {
                unlink($current_photo_path);
              }
            }

            // Check if the uploads directory exists; if not, create it
            if (!is_dir($targetDir)) {
              mkdir($targetDir, 0755, true);
            }

            // Set the photo name and ensure it is unique
            $photo_name = pathinfo($_FILES['photo']['name'], PATHINFO_FILENAME);
            $photo_extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $photo = $photo_name . "_" . time() . "." . $photo_extension;
            $targetFilePath = $targetDir . $photo;

            // Move the uploaded file to the specified directory
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {
              // Update the database with the new photo path
              $updateSql = "UPDATE Book SET photo = ? WHERE book_id = ?";
              $updateStmt = $conn->prepare($updateSql);
              $updateStmt->bind_param("ss", $photo, $title);
              $updateStmt->execute();
              $updateStmt->close();
            } else {
              $message = "Error uploading photo.";
              $message_type = "error";
            }
          }

          // Fetch related data using a helper function
          function fetch_related_data($conn, $query, $title)
          {
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $title);
            $stmt->execute();
            return $stmt->get_result();
          }

          // Fetch related data
          $coAuthorsResult = fetch_related_data($conn, "SELECT * FROM CoAuthor WHERE book_id = ?", $title);
          $subjectsResult = fetch_related_data($conn, "SELECT * FROM Subject WHERE book_id = ?", $title);
        }
      } else {
        $message = "Error executing query: " . $stmt->error;
        $message_type = "error";
      }
      $stmt->close();
    } else {
      $message = "No book title provided.";
      $message_type = "error";
    }

    // Helper function to hide sections if empty
    function hide_if_empty($value) {
        return empty($value) ? 'hidden' : '';
    }
?>

<!-- Main Content Area with Sidebar and BrowseBook Section -->
<div class="flex">
  <!-- Sidebar Section -->
  <?php include $sidebars[$userType] ?? ''; ?>

  <!-- BrowseBook Content Section -->
  <div class="flex-grow bg-gray-100">
    <!-- Header at the Top -->
    <?php include 'include/header.php'; ?>

    <!-- Message -->
    <?php if ($message): ?>
      <div class="mb-4 p-4 text-center rounded-lg <?php echo $message_type === 'error' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600'; ?>">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>

    <?php if (isset($book)): ?>
      <div class="bg-white p-6 rounded-lg shadow mb-6">
        <a href="index.php"
        class="inline-block bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300 mb-2" 
        >&larr; Return</a>
        
        <!-- Book Photo and Title -->
        <div class="text-center mb-6">
          <div class="mb-4">
            <?php if (!empty($book['photo'])): ?>
              <img src="../../pic/Book/<?php echo htmlspecialchars($book['photo']); ?>" alt="Book Photo" class="w-48 h-48 mx-auto rounded-lg shadow-md">
            <?php else: ?>
              <img src="../../pic/default/book.jpg" alt="Default Book Photo" class="w-48 h-48 mx-auto rounded-lg shadow-md">
            <?php endif; ?>
          </div>
          <h2 class="text-3xl font-semibold"><?php echo htmlspecialchars($book['B_title']); ?></h2>
        </div>

      

<div class="flex justify-center gap-4 mb-6">
  <?php
  // Check if the user is an admin or librarian
  if ($userType === 'admin' || $userType === 'librarian') {
  ?>
    <a href="edit_book.php?title=<?php echo urlencode($book['book_id']); ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Edit</a>
    <a href="AddBookCopy.php?title=<?php echo urlencode($book['book_id']); ?>" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Add Copy</a>
  <?php
  }
  ?>
  <!-- This link is visible to everyone -->
  <a href="BookList.php?title=<?php echo urlencode($book['book_id']); ?>" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Copy List</a>
</div>


      </div>

      <!-- Book Information Section -->
      <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h3 class="text-2xl font-semibold mb-4">Book Information</h3>
        <div class="grid grid-cols-2 gap-6">
          <div class="flex">
            <div class="w-1/3 font-semibold text-gray-700">Subtitle:</div>
            <div class="w-2/3"><?php echo htmlspecialchars($book['subtitle']); ?></div>
          </div>
          <div class="flex">
            <div class="w-1/3 font-semibold text-gray-700">Author:</div>
            <div class="w-2/3"><?php echo htmlspecialchars($book['author']); ?></div>
          </div>
          <div class="flex">
            <div class="w-1/3 font-semibold text-gray-700">Edition:</div>
            <div class="w-2/3"><?php echo htmlspecialchars($book['edition']); ?></div>
          </div>
          <div class="flex <?php echo hide_if_empty($book['LCCN']); ?>">
            <div class="w-1/3 font-semibold text-gray-700">LCCN:</div>
            <div class="w-2/3"><?php echo htmlspecialchars($book['LCCN']); ?></div>
          </div>
          <div class="flex <?php echo hide_if_empty($book['ISBN']); ?>">
            <div class="w-1/3 font-semibold text-gray-700">ISBN:</div>
            <div class="w-2/3"><?php echo htmlspecialchars($book['ISBN']); ?></div>
          </div>
          <div class="flex <?php echo hide_if_empty($book['ISSN']); ?>">
            <div class="w-1/3 font-semibold text-gray-700">ISSN:</div>
            <div class="w-2/3"><?php echo htmlspecialchars($book['ISSN']); ?></div>
          </div>
          <div class="flex">
            <div class="w-1/3 font-semibold text-gray-700">Material Type:</div>
            <div class="w-2/3"><?php echo htmlspecialchars($book['MT']); ?></div>
          </div>
          <div class="flex">
            <div class="w-1/3 font-semibold text-gray-700">Subject Type:</div>
            <div class="w-2/3"><?php echo htmlspecialchars($book['ST']); ?></div>
          </div>
          <div class="flex">
            <div class="w-1/3 font-semibold text-gray-700">Place:</div>
            <div class="w-2/3"><?php echo htmlspecialchars($book['place']); ?></div>
          </div>
          <div class="flex">
            <div class="w-1/3 font-semibold text-gray-700">Publisher:</div>
            <div class="w-2/3"><?php echo htmlspecialchars($book['publisher']); ?></div>
          </div>
          <div class="flex">
            <div class="w-1/3 font-semibold text-gray-700">Publication Date:</div>
            <div class="w-2/3"><?php echo htmlspecialchars($book['Pdate']); ?></div>
          </div>
          <div class="flex">
            <div class="w-1/3 font-semibold text-gray-700">Extent:</div>
            <div class="w-2/3"><?php echo htmlspecialchars($book['extent']); ?></div>
          </div>
        </div>
      </div>

      <!-- Additional Information Section -->
      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-2xl font-semibold mb-4">Additional Information</h3>
        <div class="grid grid-cols-2 gap-6">
          <div class="flex">
            <div class="w-1/3 font-semibold text-gray-700">Volume:</div>
            <div class="w-2/3"><?php echo htmlspecialchars($book['volume']); ?></div>
          </div>
          <div class="flex">
            <div class="w-1/3 font-semibold text-gray-700">Resources:</div>
            <div class="w-2/3">
              <?php if (!empty($book['url']) || !empty($book['Description'])): ?>
                <a href="<?php echo htmlspecialchars($book['url']); ?>" class="text-blue-500 underline" target="_blank">
                  <?php echo htmlspecialchars($book['Description'] ?: $book['url']); ?>
                </a>
              <?php else: ?>
                No resources available.
              <?php endif; ?>
            </div>
          </div>
          <div class="flex">
            <div class="w-1/3 font-semibold text-gray-700">Co-authors:</div>
            <div class="w-2/3">
              <?php if ($coAuthorsResult->num_rows > 0): ?>
                <ul class="list-disc pl-6">
                  <?php while ($row = $coAuthorsResult->fetch_assoc()): ?>
                    <li><?php echo htmlspecialchars($row['Co_Name']) . " - " . htmlspecialchars($row['Co_Date']) . " (" . htmlspecialchars($row['Co_Role']) . ")"; ?></li>
                  <?php endwhile; ?>
                </ul>
              <?php else: ?>
                No co-authors available.
              <?php endif; ?>
            </div>
          </div>
          <div class="flex">
            <div class="w-1/3 font-semibold text-gray-700">Subjects:</div>
            <div class="w-2/3">
              <?php if ($subjectsResult->num_rows > 0): ?>
                <ul class="list-disc pl-6">
                  <?php while ($row = $subjectsResult->fetch_assoc()): ?>
                    <li><?php echo htmlspecialchars($row['Sub_Head']) . ": " . htmlspecialchars($row['Sub_Head_input']); ?></li>
                  <?php endwhile; ?>
                </ul>
              <?php else: ?>
                No subjects available.
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Footer -->
    <footer>
      <?php include 'include/footer.php'; ?>
    </footer>
  </div>
</div>