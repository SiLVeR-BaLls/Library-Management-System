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

// Retrieve the query parameters from the URL
$queryParams = $_GET;
unset($queryParams['title']); // Remove the 'title' parameter to avoid conflicts
$queryString = http_build_query($queryParams); // Rebuild the query string without 'title'

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
function hide_if_empty($value)
{
  return empty($value) ? 'hidden' : '';
}
?>

<!-- Main Content Area with Sidebar and BrowseBook Section -->
<div class="flex">
  <!-- Sidebar Section -->
  <?php if (!empty($userType) && isset($sidebars[$userType]) && file_exists($sidebars[$userType])) {
    include $sidebars[$userType]; 
}?>
  <!-- BrowseBook Content Section -->
  <div class="flex-grow bg-gray-100">
    <!-- Header at the Top -->
    <?php include 'include/header.php'; ?>

    <!-- Message -->
    <?php if ($message): ?>
      <div class="mb-2 p-3 text-center rounded-lg <?php echo $message_type === 'error' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600'; ?>">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>

    <?php if (isset($book)): ?>
      <div class="bg-white p-4 rounded-lg shadow mb-4">
        <a href="search_results.php?<?= htmlspecialchars($queryString) ?>"
          class="inline-block bg-gray-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-gray-700 focus:outline-none focus:ring focus:ring-gray-300 mb-2">&larr; Return</a>
        <!-- Book Photo and Title -->
        <div class="flex flex-row gap-10 text-center mb-4">
          <div class="mb-3">
            <?php if (!empty($book['photo'])): ?>
              <img src="../../pic/Book/<?php echo htmlspecialchars($book['photo']); ?>" alt="Book Photo" class="w-36 h-36 mx-auto rounded-lg shadow-md">
            <?php else: ?>
              <img src="../../pic/default/book.jpg" alt="Default Book Photo" class="w-36 h-36 mx-auto rounded-lg shadow-md">
            <?php endif; ?>
          </div>
          <div class="text-left">
            <h2 class="text-2xl font-semibold"><?php echo htmlspecialchars($book['B_title']); ?></h2>
            <div class="flex justify-left gap-3 mb-4">
              <?php if ($userType === 'admin' || $userType === 'librarian'): ?>
                <!-- Edit Icon -->
                <a href="edit_book.php?title=<?php echo urlencode($book['book_id']); ?>" 
                   class="bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600 flex items-center gap-2">
                   <i class="fa fa-edit"></i>
                </a>
                <!-- Add Copy Icon -->
                <a href="AddBookCopy.php?title=<?php echo urlencode($book['book_id']); ?>" 
                   class="bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600 flex items-center gap-2">
                   <i class="fa fa-plus"></i>
                </a>
              <?php endif; ?>
              <!-- Copy List Icon -->
              <a href="BookList.php?title=<?php echo urlencode($book['book_id']); ?>" 
                 class="bg-gray-500 text-white px-3 py-2 rounded hover:bg-gray-600 flex items-center gap-2">
                 <i class="fa fa-list"></i>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Book Information Section -->
      <div class="bg-white p-4 rounded-lg shadow mb-4">
        <h3 class="text-xl font-semibold mb-3">Book Information</h3>
        <div class="grid grid-cols-2 gap-4">
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
      <div class="bg-white p-4 rounded-lg shadow">
        <h3 class="text-xl font-semibold mb-3">Additional Information</h3>
        <div class="grid grid-cols-2 gap-4">
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
                <ul class="pl-6" style="list-style-type: none;">
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
                <ul class="pl-6" style="list-style-type: none;">
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