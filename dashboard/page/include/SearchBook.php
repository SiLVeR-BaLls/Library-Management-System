<?php
include '../../config.php';

// Get the search term from the request
$searchTerm = isset($_GET['bookID']) ? $_GET['bookID'] : '';

// Prevent SQL Injection and sanitize the input
$searchTerm = htmlspecialchars($searchTerm);

// Query to search for books by B_title, author, or Co_Name
$sql = "SELECT 
            book_copies.book_copy, book_copies.copy_ID, book_copies.B_title, book_copies.status, 
            book_copies.callNumber, book_copies.circulationType, book_copies.dateAcquired, 
            book_copies.description1, book_copies.description2, book_copies.description3,
            book.B_title AS book_title, book.author, book.ISBN, book.publisher, book.Pdate,
            coauthor.Co_Name
        FROM book_copies
        LEFT JOIN book ON book_copies.B_title = book.B_title
        LEFT JOIN coauthor ON book_copies.B_title = coauthor.book_id
        WHERE (book_copies.book_copy LIKE ? OR book.B_title LIKE ? OR book.author LIKE ? OR coauthor.Co_Name LIKE ?)
          AND (book_copies.status = 'available' OR book_copies.status = 'reserved')";

// Prepare the SQL statement to prevent SQL injection
$stmt = $conn->prepare($sql);
$searchParam = "%" . $searchTerm . "%"; // Using wildcards to search for partial matches
$stmt->bind_param("ssss", $searchParam, $searchParam, $searchParam, $searchParam);
$stmt->execute();

// Get the results
$result = $stmt->get_result();

// Check if any books were found
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Sanitize output for safety
        $bookID = htmlspecialchars($row['book_copy']);
        $bookTitle = htmlspecialchars($row['book_title']);
        $author = htmlspecialchars($row['author']);
        $coName = htmlspecialchars($row['Co_Name']);
        $status = htmlspecialchars($row['status']);
        $callNumber = htmlspecialchars($row['callNumber']);
        $publisher = htmlspecialchars($row['publisher']);
        $publicationYear = htmlspecialchars($row['Pdate']);

        // Check if the book is reserved and validate the user
        if ($status === 'reserved') {
            $reservationQuery = $conn->prepare("SELECT IDno FROM reservation WHERE book_copy = ?");
            $reservationQuery->bind_param("s", $bookID);
            $reservationQuery->execute();
            $reservationResult = $reservationQuery->get_result();
            $reservedBy = $reservationResult->fetch_assoc()['IDno'] ?? null;

            if ($reservedBy !== $_SESSION['IDno']) {
                echo "<div class='no-results'>Book with ID: $bookID is reserved by another user.</div>";
                continue;
            }
        }

        // Display each book result as a clickable div
        echo "<div class='p-2 cursor-pointer hover:bg-gray-200 search-item' 
        data-id='{$bookID}' 
        data-title='{$bookTitle}' 
        data-author='{$author}' 
        data-coName='{$coName}' 
        data-callNumber='{$callNumber}' 
        data-publisher='{$publisher}' 
        data-publicationYear='{$publicationYear}' 
        data-status='{$status}'>
          <strong>Book ID:</strong> $bookID <br>
          <strong>Title:</strong> $bookTitle <br>
          <strong>Author:</strong> $author <br>
          <strong>Co-Author:</strong> $coName <br>
          <strong>Genre:</strong> $callNumber <br>
          <strong>Publisher:</strong> $publisher <br>
          <strong>Year:</strong> $publicationYear <br>
          <strong>Status:</strong> $status
        </div><hr>";
    }  
} else {
    echo "<div class='no-results'>No book found matching: " . htmlspecialchars($searchTerm) . "</div>";
}

// Close the database connection
$stmt->close();
$conn->close();
?>
