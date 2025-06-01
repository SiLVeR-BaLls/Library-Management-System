<?php
    include 'config.php';
    // Initialize message variables
    $message = "";
    $message_type = "";

    // Get the logged-in user's IDno from the session
    $idno = $_SESSION['IDno'] ?? null;

    // Check if IDno is set in the session
    if (!$idno) {
      $message = "User ID not found in session. Please log in.";
      $message_type = "error";
    } else {
      // Verify if the ID exists in the database
      $query = "SELECT * FROM users_info WHERE IDno = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("s", $idno);
      $stmt->execute();
      $result = $stmt->get_result();
      $userData = $result->fetch_assoc();

      if (!$userData) {
        $message = "User not found in the database.";
        $message_type = "error";
      }
    }

    // Get the book title from the URL (filtering by title)
    $book_title = isset($_GET['title']) ? $_GET['title'] : '';

    // Use prepared statements to fetch filtered book copies
    $sql = "SELECT book_copy, status, B_title, book_copy_ID, callNumber, vendor, fundingSource, sublocation, rating FROM book_copies"; // Added required fields
    if (!empty($book_title)) {
      $sql .= " WHERE book_id = ?";
    }
    $stmt = $conn->prepare($sql);

    if (!empty($book_title)) {
      $stmt->bind_param("s", $book_title);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
      $message = "Error retrieving books: " . $conn->error;
      $message_type = "error";
    }

    // Create reservations table if it doesn't exist
    $create_table_sql = "
      CREATE TABLE IF NOT EXISTS reservations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        book_copy VARCHAR(255) NOT NULL,
        IDno VARCHAR(255) NOT NULL,
        reserved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (book_copy) REFERENCES book_copies(book_copy) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (IDno) REFERENCES users_info(IDno) ON DELETE CASCADE ON UPDATE CASCADE
      )";
    $conn->query($create_table_sql);

    // Handle Reserve button click
    if (isset($_POST['reserve_book_copy'])) {
      $book_copy = $_POST['reserve_book_copy'];

      // Ensure the user is logged in
      if ($idno) {
        // Update the status to 'Reserved' if it's currently 'Available'
        $update_sql = "UPDATE book_copies SET status = 'Reserved' WHERE book_copy = ? AND status = 'Available'";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("s", $book_copy);

        if ($update_stmt->execute()) {
          // Insert reservation into the reservations table
          $insert_sql = "INSERT INTO reservations (book_copy, IDno) VALUES (?, ?)";
          $insert_stmt = $conn->prepare($insert_sql);
          $insert_stmt->bind_param("ss", $book_copy, $idno);

          if ($insert_stmt->execute()) {
            $message = "Book copy reserved successfully.";
            $message_type = "success";

            // Redirect to the same page to prevent form resubmission
            header("Location: " . $_SERVER['PHP_SELF'] . "?title=" . urlencode($book_title));
            exit;
          } else {
            $message = "Error adding reservation: " . $conn->error;
            $message_type = "error";
          }
        } else {
          $message = "Error reserving book copy: " . $conn->error;
          $message_type = "error";
        }
      } else {
        $message = "You must be logged in to reserve a book.";
        $message_type = "error";
      }
    }

    // Handle Unreserve button click
    if (isset($_POST['unreserve_book_copy'])) {
      $book_copy = $_POST['unreserve_book_copy'];

      // Ensure the user is logged in
      if ($idno) {
        // Check if the book is reserved by the logged-in user
        $check_sql = "SELECT * FROM reservations WHERE book_copy = ? AND IDno = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ss", $book_copy, $idno);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
          // Update the status to 'Available' if it's currently 'Reserved'
          $update_sql = "UPDATE book_copies SET status = 'Available' WHERE book_copy = ? AND status = 'Reserved'";
          $update_stmt = $conn->prepare($update_sql);
          $update_stmt->bind_param("s", $book_copy);

          if ($update_stmt->execute()) {
            // Delete the reservation from the reservations table
            $delete_sql = "DELETE FROM reservations WHERE book_copy = ? AND IDno = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("ss", $book_copy, $idno);

            if ($delete_stmt->execute()) {
              $message = "Book copy unreserved successfully.";
              $message_type = "success";

              // Redirect to the same page to prevent form resubmission
              header("Location: " . $_SERVER['PHP_SELF'] . "?title=" . urlencode($book_title));
              exit;
            } else {
              $message = "Error removing reservation: " . $conn->error;
              $message_type = "error";
            }
          } else {
            $message = "Error unreserving book copy: " . $conn->error;
            $message_type = "error";
          }
        } else {
          $message = "This book is not reserved by you and cannot be unreserved.";
          $message_type = "error";
        }
      } else {
        $message = "You must be logged in to unreserve a book.";
        $message_type = "error";
      }
    }

    
  // Update book_copies status to 'Available' for reservations older than 7 days
  $updateBookCopiesQuery = "
  UPDATE book_copies c
  JOIN reservations r ON c.book_copy = r.book_copy
  SET c.status = 'Available'
  WHERE c.status = 'Reserved'
  AND r.reserved_at < NOW() - INTERVAL 7 DAY
  ";
  if (!$conn->query($updateBookCopiesQuery)) {
  error_log("Error updating book_copies: " . $conn->error);
  }

  // Auto-delete reservations older than 7 days
  $deleteReservationsQuery = "
  DELETE FROM reservations
  WHERE reserved_at < NOW() - INTERVAL 7 DAY
  ";
  if (!$conn->query($deleteReservationsQuery)) {
  error_log("Error deleting reservations: " . $conn->error);
  }

  // Query to get reservation details for the logged-in user
  $query = "
  SELECT r.book_copy, r.IDno, r.reserved_at, c.B_title, c.status
  FROM reservations r
  JOIN book_copies c ON r.book_copy = c.book_copy
  WHERE r.IDno = ?
  ORDER BY r.reserved_at DESC
  ";
?>

<title><?php
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo htmlspecialchars($row['B_title']);
    } else {
        echo "Copy List";
    }
?></title>
<div class="flex">
     <?php if (!empty($userType) && isset($sidebars[$userType]) && file_exists($sidebars[$userType])) {
    include $sidebars[$userType]; 
}?><div class="flex-grow">
        <?php include 'include/header.php'; ?>

        <div class="container mx-auto px-4 py-6">
            <h2 class="text-2xl font-semibold mb-4">Copy List</h2>
            <a href="ViewBook.php?title=<?php echo urlencode($book_title); ?>"
                class="inline-block bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600 mb-3">Return</a>
            <?php if ($message): ?>
                <div
                    class="mb-4 p-4 <?php echo $message_type == 'error' ? 'bg-red-500 text-white' : 'bg-green-500 text-white'; ?> rounded">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <table class="min-w-full table-auto bg-white shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="py-2 px-4 border-b">BOOK ID</th>
                        <th class="py-2 px-4 border-b">Title</th> <th class="py-2 px-4 border-b">Rating</th> <th class="py-2 px-4 border-b">Status</th>
                        <th class="py-2 px-4 border-b">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        $result->data_seek(0); // Reset result pointer
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr class='border-b hover:bg-gray-50'>
                                        <td class='py-2 px-4'>" . htmlspecialchars($row['book_copy']) . "</td>
                                        <td class='py-2 px-4'>" . htmlspecialchars($row['B_title']) . "</td>
                                        <td class='py-2 px-4'>
                                            <div class='text-gray-700 flex-grow'>";
                                            $rating = (int) $row['rating']; // Fixed variable name
                                            $maxStars = 5;
                                            for ($i = 1; $i <= $maxStars; $i++) {
                                                if ($i <= $rating) {
                                                    $starColor = match ($rating) {
                                                        5 => 'text-green-500',
                                                        4 => 'text-blue-500',
                                                        3 => 'text-yellow-400',
                                                        2 => 'text-orange-500',
                                                        1 => 'text-red-500',
                                                        default => 'text-gray-400',
                                                    };
                                                    echo "<span class='{$starColor} text-3xl'>&#9733;</span>";
                                                } else {
                                                    echo '<span class="text-gray-400 text-3xl">&#9734;</span>';
                                                }
                                            }
                            echo "          </div>
                                        </td>
                                        <td class='py-2 px-4'>" . htmlspecialchars($row['status']) . "</td>
                                        <td class='py-2 px-4'>
                                            <a href='ViewCopy.php?book_copy_ID=" . urlencode($row['book_copy_ID']) . "' class='bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600 mr-2'>View</a>";

                            if ($row['status'] === 'Available') {
                                echo "<form method='POST' class='inline-block' onsubmit='return confirm(\"Are you sure you want to reserve this book?\");' style='margin-right: 8px;'>
                                            <input type='hidden' name='reserve_book_copy' value='" . htmlspecialchars($row['book_copy']) . "'>
                                            <button type='submit' class='bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600'>Reserve</button>
                                        </form>";
                            } elseif ($row['status'] === 'Reserved') {
                                // Check if the logged-in user reserved the book
                                $reserved_by_user = false;
                                $check_sql = "SELECT * FROM reservations WHERE book_copy = ? AND IDno = ?";
                                $check_stmt = $conn->prepare($check_sql);
                                $check_stmt->bind_param("ss", $row['book_copy'], $idno);
                                $check_stmt->execute();
                                $check_result = $check_stmt->get_result();
                                if ($check_result->num_rows > 0) {
                                    $reserved_by_user = true;
                                }

                                if ($reserved_by_user) {
                                    echo "<form method='POST' class='inline-block' onsubmit='return confirm(\"Are you sure you want to unreserve this book?\");' style='margin-right: 8px;'>
                                                <input type='hidden' name='unreserve_book_copy' value='" . htmlspecialchars($row['book_copy']) . "'>
                                                <button type='submit' class='bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600'>Unreserve</button>
                                            </form>";
                                } else {
                                    echo "<button class='bg-gray-500 text-white py-1 px-3 rounded' disabled style='margin-right: 8px;'>Reserved</button>";
                                }
                            } elseif ($row['status'] === 'Borrowed') {
                                echo "<button class='bg-gray-500 text-white py-1 px-3 rounded' disabled style='margin-right: 8px;'>Borrowed</button>";
                            } else {
                                echo "<button class='bg-gray-500 text-white py-1 px-3 rounded' disabled style='margin-right: 8px;'>Unavailable</button>";
                            }
                            echo "</td>
                                    </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='py-2 px-4 text-center'>No books available.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
              <div class="flex justify-center items-center space-x-2 my-3 flex-col md:flex-row md:space-x-4">
                <button id="prevBtn" onclick="prevPage()" class="btn-pagination px-4 py-1 bg-gray-800 text-white rounded-lg cursor-pointer disabled:bg-gray-400 disabled:cursor-not-allowed transition duration-300 hover:bg-gray-600" disabled>Previous</button>
                <span id="pageInfo" class="text-sm text-gray-600 font-medium">Page 1 of X</span>
                <button id="nextBtn" onclick="nextPage()" class="btn-pagination px-4 py-1 bg-gray-800 text-white rounded-lg cursor-pointer transition duration-300 hover:bg-gray-600">Next</button>
            </div>
        </div>
        
        <footer>
            <?php include 'include/footer.php'; ?>
        </footer>
    </div>
</div>

<script>
  const table = document.querySelector('table tbody');
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');
  const pageInfo = document.getElementById('pageInfo');
  const rowsPerPage = 10; // You can adjust the number of rows per page
  let currentPage = 1;
  let rows = Array.from(table.querySelectorAll('tr'));
  let numPages = Math.ceil(rows.length / rowsPerPage);

  function displayRows(page) {
    table.innerHTML = ''; // Clear the table body
    const startIndex = (page - 1) * rowsPerPage;
    const endIndex = startIndex + rowsPerPage;
    const rowsToShow = rows.slice(startIndex, endIndex);

    rowsToShow.forEach(row => {
      table.appendChild(row);
    });

    pageInfo.textContent = `Page ${page} of ${numPages}`;
    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage === numPages;
  }

  function nextPage() {
    if (currentPage < numPages) {
      currentPage++;
      displayRows(currentPage);
    }
  }

  function prevPage() {
    if (currentPage > 1) {
      currentPage--;
      displayRows(currentPage);
    }
  }

  // Initial display
  displayRows(currentPage);
</script>