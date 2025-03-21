<?php
include '../../config.php';

// Get the bookID from the request (use GET to fetch the search query)
$bookID = isset($_GET['bookID']) ? $_GET['bookID'] : '';

// Prevent SQL Injection and sanitize the input
$bookID = htmlspecialchars($bookID);

// Query to search for books by a partial or exact match of the book ID
$sql = "SELECT 
            book_copies.book_copy, book_copies.copy_ID, book_copies.B_title, book_copies.status, 
            book_copies.callNumber, book_copies.circulationType, book_copies.dateAcquired, 
            book_copies.description1, book_copies.description2, book_copies.description3,
            book.B_title AS book_title, book.author, book.ISBN, book.publisher, book.Pdate
        FROM book_copies
        LEFT JOIN book ON book_copies.B_title = book.B_title
        WHERE book_copies.book_copy LIKE ? AND status = 'available' ";

// Prepare the SQL statement to prevent SQL injection
$stmt = $conn->prepare($sql);
$searchParam = "%" . $bookID . "%"; // Using wildcards to search for partial matches
$stmt->bind_param("s", $searchParam);
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
        $status = htmlspecialchars($row['status']);
        $callNumber = htmlspecialchars($row['callNumber']);
        $publisher = htmlspecialchars($row['publisher']);
        $publicationYear = htmlspecialchars($row['Pdate']);

        // Display each book result as a clickable div
        echo "<div class='p-2 cursor-pointer hover:bg-gray-200 search-item' 
        data-id='{$bookID}' 
        data-title='{$bookTitle}' 
        data-author='{$author}' 
        data-callNumber='{$callNumber}' 
        data-publisher='{$publisher}' 
        data-publicationYear='{$publicationYear}' 
        data-status='{$status}'>
          <strong>Book ID:</strong> $bookID <br>
          <strong>Title:</strong> $bookTitle <br>
          <strong>Author:</strong> $author <br>
          <strong>Genre:</strong> $callNumber <br>
          <strong>Publisher:</strong> $publisher <br>
          <strong>Year:</strong> $publicationYear <br>
          <strong>Status:</strong> $status
        </div><hr>";
    }  
} else {
    echo "<div class='no-results'>No book found with ID: " . htmlspecialchars($bookID) . "</div>";
}

// Close the database connection
$stmt->close();
$conn->close();
?>
