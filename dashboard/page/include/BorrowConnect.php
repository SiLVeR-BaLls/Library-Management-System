
<?php
include '../../config.php';

// Function to calculate the due date based on user type
function calculateDueDate($U_Type) {
    $currentDate = new DateTime();

    if ($U_Type === 'student') {
        $daysToAdd = 0;
        while ($daysToAdd < 3) {
            $currentDate->modify('+1 day');
            $dayOfWeek = $currentDate->format('N'); // 1 = Monday, 7 = Sunday
            if ($dayOfWeek < 6) { // weekdays only
                $daysToAdd++;
            }
        }
    } elseif (in_array($U_Type, ['admin', 'professor', 'super_admin'])) {
        $currentDate->modify('+3 months');
    }

    return $currentDate->format('Y-m-d'); // Return due date in YYYY-MM-DD format
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $IDno = $_POST["IDno"];
    $bookIDs = $_POST["bookID"];
    $errors = [];
    $borrowedBooks = [];

    // Fetch the user's U_Type
    $userTypeQuery = $conn->prepare("SELECT U_Type FROM users_info WHERE IDno = ?");
    $userTypeQuery->bind_param("s", $IDno);
    $userTypeQuery->execute();
    $result = $userTypeQuery->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $U_Type = $row['U_Type'];

        // Calculate the due date
        $dueDate = calculateDueDate($U_Type);

        // Proceed with the book borrowing process
        foreach ($bookIDs as $bookID) {
            // Check if the book is available
            $bookCheck = $conn->prepare("SELECT * FROM book_copies WHERE book_copy = ? AND status = 'available'");
            $bookCheck->bind_param("s", $bookID);
            $bookCheck->execute();
            $bookCheck->store_result();

            if ($bookCheck->num_rows > 0) {
                // Insert the borrowing record with borrow date and due date
                $stmt = $conn->prepare("INSERT INTO borrow_book (IDno, book_copy, borrow_date, due_date) VALUES (?, ?, NOW(), ?)");
                $stmt->bind_param("sss", $IDno, $bookID, $dueDate);
                $stmt->execute();

                // Update the book status to 'borrowed'
                $updateBook = $conn->prepare("UPDATE book_copies SET status = 'borrowed' WHERE book_copy = ?");
                $updateBook->bind_param("s", $bookID);
                $updateBook->execute();

                // Get the book title (B_title) based on the book_copy
                $bookTitleQuery = $conn->prepare("SELECT B_title FROM book WHERE book_id = (SELECT book_id FROM book_copies WHERE book_copy = ?)");
                $bookTitleQuery->bind_param("s", $bookID);
                $bookTitleQuery->execute();
                $titleResult = $bookTitleQuery->get_result();
                $titleRow = $titleResult->fetch_assoc();

                $borrowedBooks[] = [
                    'book_copy' => $bookID,
                    'B_title' => $titleRow['B_title'] ?? 'Unknown Title',
                ];

                $stmt->close();
                $updateBook->close();
                $bookTitleQuery->close();
            } else {
                $errors[] = "Book with ID <b>$bookID</b> is not available (it may be borrowed).";
            }
            $bookCheck->close();
        }
    } else {
        $errors[] = "User with ID <b>$IDno</b> does not exist in the user log.";
    }

    $userTypeQuery->close();

    if (!empty($borrowedBooks) || !empty($errors)) {
        echo "<div class='modal'>";
        echo "<div class='modal-content'>";
    
        // Success Messages
        if (!empty($borrowedBooks)) {
            echo "<h2 class='text-xl font-bold text-green-600 mb-4'>Borrowed Books</h2>";
            foreach ($borrowedBooks as $book) {
                echo "<div class='notification-container success'>";
                echo "<div class='notification-content'>";
                echo "<p class='text-gray-700'><span class='font-semibold'>Book Copy:</span> {$book['book_copy']}</p>";
                echo "<p class='text-gray-700'><span class='font-semibold'>Title:</span> {$book['B_title']}</p>";
                echo "</div>";
                echo "</div>";
            }
            echo "<p class='notification-container'>Due Date:</span> <span class='text-green-600'>$dueDate</span></p>";
        }
    
        // Error Messages
        if (!empty($errors)) {
            echo "<h2 class='text-xl font-bold text-red-600 mt-6'>Errors</h2>";
            foreach ($errors as $error) {
                echo "<div class='notification-container error'>";
                echo "<div class='notification-content'>";
                echo "<p class='text-red-700'>$error</p>";
                echo "</div>";
                echo "</div>";
            }
        }
    
        // Close Button
        echo "<a href='../borrow.php' class='close-button'>Go to Borrow Page</a>";
        echo "</div>";
        echo "</div>";
    
        // Auto-redirect script
        echo "<script>setTimeout(() => { window.location.href = '../borrow.php'; }, 5000);</script>";
    }
  
}

$conn->close();
?>
  
 <style>
    /* Centered notification container styling */
.modal {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5); /* Dimmed background for the modal */
}

/* Modal content box styling */
.modal-content {
    background-color: #fff;
    padding: 15px; /* Reduced padding for less space */
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 400px; /* Reduced max width */
    text-align: center;
}

/* Success message styling */
.success {
    background-color: #eaffea; /* Light green background */
    color: #4CAF50; /* Green text */
    font-weight: bold; /* Make text bold */
    padding: 10px; /* Reduced padding */
    border-radius: 8px;
}

/* Error message styling */
.error {
    background-color: #fee2e2; /* Light red background */
    color: #f44336; /* Red text */
    font-weight: bold; /* Make text bold */
    padding: 10px; /* Reduced padding */
    border-radius: 8px;
}


/* Close button styling */
.close-button {
    display: block;
    margin-top: 15px; /* Reduced margin */
    padding: 8px 16px; /* Adjusted padding */
    background-color: #333;
    color: white;
    text-align: center;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
}

/* Hover effect for the close button */
.close-button:hover {
    background-color: #555;
}

 </style>