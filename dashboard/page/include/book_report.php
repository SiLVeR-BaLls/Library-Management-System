<?php

// Query to fetch books with ratings (0, 1, 2) and count borrowed books
$ratingQuery = "
        SELECT bc.B_title, bc.book_copy, bc.rating
        FROM book_copies bc
        WHERE bc.rating IS NOT NULL AND bc.status IN ('Available', 'Borrowed')
    ";
$ratingResult = $conn->query($ratingQuery);

// Initialize arrays to store books by rating
$booksWithRatingZero = [];
$booksWithRatingOne = [];
$booksWithRatingTwo = [];

// Initialize array for most borrowed books by college
$borrowedBooksByCollege = [];
$allBorrowedBooks = [];

// Query for most borrowed books based on B_title and college IDno
$borrowedQuery = "
        SELECT ui.college, bc.B_title, COUNT(bb.book_copy) AS Borrowed_Count
        FROM borrow_book bb
        JOIN book_copies bc ON bb.book_copy = bc.book_copy
        JOIN users_info ui ON bb.IDno = ui.IDno
        WHERE bc.status = 'Borrowed'
        GROUP BY ui.college, bc.B_title
        ORDER BY ui.college, Borrowed_Count DESC
    ";
$borrowedResult = $conn->query($borrowedQuery);

// Organize borrowed books by college
if ($borrowedResult->num_rows > 0) {
    while ($row = $borrowedResult->fetch_assoc()) {
        // Save the most borrowed book per college
        $borrowedBooksByCollege[$row['college']][] = $row;
        // Also store for the overall table (only the most borrowed book from each college)
        if (count($borrowedBooksByCollege[$row['college']]) == 1) {
            $allBorrowedBooks[] = $row;
        }
    }
}

// Process rating results
if ($ratingResult->num_rows > 0) {
    while ($row = $ratingResult->fetch_assoc()) {
        if ($row['rating'] == 0) {
            $booksWithRatingZero[] = $row;
        } elseif ($row['rating'] == 1) {
            $booksWithRatingOne[] = $row;
        } elseif ($row['rating'] == 2) {
            $booksWithRatingTwo[] = $row;
        }
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<style>
    .report-container {
        max-height: 400px;
        overflow-x: hidden;
        overflow-y: auto;
    }

    .report-card {
        word-wrap: break-word;
        white-space: normal;
        text-overflow: ellipsis;
        overflow: hidden;
        padding: 0.75rem;
    }

    .heading {
        font-size: 1.25rem;
    }

    .subheading {
        font-size: 1rem;
    }

    li {
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }
</style>

<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Library Reports</h1>

    <!-- Grid Layout for Reports -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <!-- Most Borrowed Books (Table) -->
        <div class="bg-white rounded shadow-md border border-gray-300 w-full report-container">
            <h2 class="heading text-gray-800 mb-4">Most Borrowed Books</h2>
            <div class="report-card">
                <table class="table-auto w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 border">College</th>
                            <th class="px-4 py-2 border">Book Title</th>
                            <th class="px-4 py-2 border">Borrowed Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Display the most borrowed book from each college
                        foreach ($allBorrowedBooks as $book) {
                            echo "<tr>";
                            echo "<td class='px-4 py-2 border'>{$book['college']}</td>";
                            echo "<td class='px-4 py-2 border'>{$book['B_title']}</td>";
                            echo "<td class='px-4 py-2 border'>{$book['Borrowed_Count']}</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Books with Rating 0 (Broken Status Suggestion) -->
        <div class="bg-white rounded shadow-md border border-gray-300 w-full report-container">
            <h2 class="heading text-gray-800 mb-4">
                Books with Rating 0 (Broken Status Suggestion)
                <span class="text-gray-600">(<?php echo count($booksWithRatingZero); ?>)</span>
            </h2>
            <div class="report-card">
                <ul class="list-none ml-4">
                    <?php
                    if (count($booksWithRatingZero) > 0) {
                        foreach ($booksWithRatingZero as $book) {
                            echo "<li class='text-red-600 font-semibold'><strong>{$book['book_copy']}</strong> - {$book['B_title']}</li>";
                        }
                    } else {
                        echo "<li>No books with rating 0 found.</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>

        <!-- Books with Rating 1 (Need Reevaluation) -->
        <div class="bg-white rounded shadow-md border border-gray-300 w-full report-container">
            <h2 class="heading text-gray-800 mb-4">
                Books with Rating 1 (Need Reevaluation)
                <span class="text-gray-600">(<?php echo count($booksWithRatingOne); ?>)</span>
            </h2>
            <div class="report-card">
                <ul class="list-none ml-4">
                    <?php
                    if (count($booksWithRatingOne) > 0) {
                        foreach ($booksWithRatingOne as $book) {
                            echo "<li class='text-orange-600 font-semibold'><strong>{$book['book_copy']}</strong> - {$book['B_title']}</li>";
                        }
                    } else {
                        echo "<li>No books with rating 1 found.</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>

        <!-- Books with Rating 2 (Might Be Good, Try Checking Again) -->
        <div class="bg-white rounded shadow-md border border-gray-300 w-full report-container">
            <h2 class="heading text-gray-800 mb-4">
                Books with Rating 2 (Might Be Good, Try Checking Again)
                <span class="text-gray-600">(<?php echo count($booksWithRatingTwo); ?>)</span>
            </h2>
            <div class="report-card">
                <ul class="list-none ml-4">
                    <?php
                    if (count($booksWithRatingTwo) > 0) {
                        foreach ($booksWithRatingTwo as $book) {
                            echo "<li class='text-yellow-600 font-semibold'><strong>{$book['book_copy']}</strong> - {$book['B_title']}</li>";
                        }
                    } else {
                        echo "<li>No books with rating 2 found.</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>

    </div>
</div>



<?php
// Close the database connection
$conn->close();
?>