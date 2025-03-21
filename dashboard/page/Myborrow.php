<?php
    include '../config.php';

    // Default number of records per page
    $defaultRecordsPerPage = 5;

    // Get the number of records per page from the user input or use the default
    $recordsPerPage = isset($_GET['books_per_page']) ? (int)$_GET['books_per_page'] : $defaultRecordsPerPage;

    // Ensure the recordsPerPage is within a reasonable range
    $recordsPerPage = max(1, min($recordsPerPage, 50)); // Between 1 and 50

    // Get the current page number for borrowed books
    $borrowPage = isset($_GET['borrow_page']) ? (int)$_GET['borrow_page'] : 1;
    $borrowPage = max(1, $borrowPage); // Ensure the page number is at least 1

    // Calculate the offset for borrowed books
    $borrowOffset = ($borrowPage - 1) * $recordsPerPage;

    // SQL query to count total borrowed books
    $countBorrowQuery = "SELECT COUNT(*) AS total FROM borrow_book WHERE return_date IS NULL AND IDno = ?";
    if ($stmt = $conn->prepare($countBorrowQuery)) {
        $stmt->bind_param("s", $userID);
        $stmt->execute();
        $stmt->bind_result($totalBorrowRecords);
        $stmt->fetch();
        $stmt->close();
    }
    $totalBorrowPages = ceil($totalBorrowRecords / $recordsPerPage);

    // Ensure the current page does not exceed the total pages for borrowed books
    $borrowPage = min($borrowPage, $totalBorrowPages);

    // Query to fetch borrowed books for the logged-in user
    $query = "
            SELECT 
                bb.borrow_id,
                bb.borrow_date,
                bc.B_title,
                b.author,
                DATEDIFF(CURDATE(), bb.borrow_date) AS days_borrowed
            FROM borrow_book AS bb
            JOIN book_copies AS bc ON bb.ID = bc.book_copy_ID
            JOIN book AS b ON bc.B_title = b.B_title
            WHERE bb.return_date IS NULL AND bb.IDno = ?
            LIMIT ?, ?
        ";

    // Prepare and execute the query
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("sii", $userID, $borrowOffset, $recordsPerPage);
        $stmt->execute();
        $result = $stmt->get_result();


        // Close the prepared statement
        $stmt->close();
    } else {
        echo "<div class='mt-4 text-center text-red-500'>Error retrieving data.</div>";
    }

    // Close the database connection
    $conn->close();
?>
<!-- Main Content Area with Sidebar and BrowseBook Section -->
<main class="flex  ">
    <!-- Sidebar Section -->
            <?php include $sidebars[$userType] ?? ''; ?>
    <!-- BrowseBook Content Section -->
    <div class="flex-grow ">
        <!-- Header at the Top -->
        <?php include 'include/header.php'; ?>

        <div class="container mx-auto px-4 py-6 ">
            <h2 class="text-2xl font-semibold text-gray-800">Your Borrowed Books</h2>
            <?php

            // Check if any rows were returned
            if ($result && $result->num_rows > 0) {
                echo "<div class='overflow-x-auto bg-white shadow-md rounded-lg'>";
                echo "<table class='min-w-full table-auto'>
                <thead class='bg-blue-600 text-white'>
                    <tr>
                        <th class='px-6 py-3 text-left'>Borrow ID</th>
                        <th class='px-6 py-3 text-left'>Book Title</th>
                        <th class='px-6 py-3 text-left'>Author</th>
                        <th class='px-6 py-3 text-left'>Borrow Date</th>
                        <th class='px-6 py-3 text-left'>Days Borrowed</th>
                    </tr>
                </thead>
                <tbody class='bg-gray-50'>";

                // Output each row from the query result
                while ($row = $result->fetch_assoc()) {
                    echo "<tr class='border-b'>
                    <td class='px-6 py-4'>" . htmlspecialchars($row['borrow_id']) . "</td>
                    <td class='px-6 py-4'>" . htmlspecialchars($row['B_title']) . "</td>
                    <td class='px-6 py-4'>" . htmlspecialchars($row['author']) . "</td>
                    <td class='px-6 py-4'>" . htmlspecialchars($row['borrow_date']) . "</td>
                    <td class='px-6 py-4'>" . htmlspecialchars($row['days_borrowed']) . " days</td>
                  </tr>";
                }

                echo "</tbody></table></div>";
            } else {
                echo "<div class='mt-4 text-center text-gray-500'>No borrowed books found for your account.</div>";
            }
            ?>
            <!-- Pagination -->
            <div class="mt-4  text-center">
                <form method="GET" class="inline-block mb-2">
                    <select name="books_per_page" id="books_per_page" onchange="this.form.submit()" class="border rounded px-2 py-1">
                        <option value="5" <?= ($recordsPerPage == 5 ? "selected" : "") ?>>5</option>
                        <option value="10" <?= ($recordsPerPage == 10 ? "selected" : "") ?>>10</option>
                        <option value="20" <?= ($recordsPerPage == 20 ? "selected" : "") ?>>20</option>
                    </select>
                    <input type="hidden" name="borrow_page" value="<?= $borrowPage ?>">
                </form>

                <!-- Navigation Controls -->
                <?php
                if ($borrowPage > 1) {
                    echo "<a href='?borrow_page=1&books_per_page=$recordsPerPage' class='px-3 py-2 bg-gray-200 border rounded mx-1'>«</a>";
                    echo "<a href='?borrow_page=" . ($borrowPage - 1) . "&books_per_page=$recordsPerPage' class='px-3 py-2 bg-gray-200 border rounded mx-1'>‹</a>";
                }
                echo "<span class='px-3 py-2 bg-blue-600 text-white border rounded mx-1'>Page $borrowPage of $totalBorrowPages</span>";

                if ($borrowPage < $totalBorrowPages) {
                    echo "<a href='?borrow_page=" . ($borrowPage + 1) . "&books_per_page=$recordsPerPage' class='px-3 py-2 bg-gray-200 border rounded mx-1'>›</a>";
                    echo "<a href='?borrow_page=$totalBorrowPages&books_per_page=$recordsPerPage' class='px-3 py-2 bg-gray-200 border rounded mx-1'>»</a>";
                }
                ?>
            </div>
        </div>

        <!-- Footer at the Bottom -->
        <footer class="bg-blue-600 text-white mt-auto">
            <?php include 'include/footer.php'; ?>
        </footer>
</main>