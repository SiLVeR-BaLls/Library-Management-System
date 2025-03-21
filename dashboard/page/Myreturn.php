<?php
    include '../config.php';

    // Default number of records per page
    $defaultRecordsPerPage = 5;

    // Get the number of records per page from the user input or use the default
    $recordsPerPage = isset($_GET['books_per_page']) ? (int)$_GET['books_per_page'] : $defaultRecordsPerPage;

    // Ensure the recordsPerPage is within a reasonable range
    $recordsPerPage = max(1, min($recordsPerPage, 50)); // Between 1 and 50

    // Get the current page number for returned books
    $returnPage = isset($_GET['return_page']) ? (int)$_GET['return_page'] : 1;
    $returnPage = max(1, $returnPage); // Ensure the page number is at least 1

    // Calculate the offset for returned books
    $returnOffset = ($returnPage - 1) * $recordsPerPage;

    // Query to count total returned books
    $countReturnQuery = "SELECT COUNT(*) AS total FROM borrow_book WHERE return_date IS NOT NULL AND IDno = ?";
    if ($stmt = $conn->prepare($countReturnQuery)) {
        $stmt->bind_param("s", $userID);
        $stmt->execute();
        $stmt->bind_result($totalReturnRecords);
        $stmt->fetch();
        $stmt->close();
    }
    $totalReturnPages = ceil($totalReturnRecords / $recordsPerPage);

    // Ensure the current page does not exceed the total pages for returned books
    $returnPage = min($returnPage, $totalReturnPages);

    // Returned Books Query
    $returnQuery = "
            SELECT 
                bb.borrow_id, 
                bb.borrow_date, 
                bc.B_title, 
                b.author, 
                DATEDIFF(bb.return_date, bb.borrow_date) AS days_borrowed, 
                bb.return_date 
            FROM borrow_book AS bb 
            JOIN book_copies AS bc ON bb.ID = bc.book_copy_ID 
            JOIN book AS b ON bc.B_title = b.B_title 
            WHERE bb.return_date IS NOT NULL AND bb.IDno = ? 
            LIMIT ?, ? 
        ";
    if ($stmt = $conn->prepare($returnQuery)) {
        $stmt->bind_param("sii", $userID, $returnOffset, $recordsPerPage);
        $stmt->execute();
        $returnResult = $stmt->get_result();
        $stmt->close();
    }
?>

<!-- Main Content Area with Sidebar and BrowseBook Section -->
<main class="flex h-full ">
    <!-- Sidebar Section -->
            <?php include $sidebars[$userType] ?? ''; ?>
    <!-- BrowseBook Content Section -->
    <div class="flex-grow ">
        <!-- Header at the Top -->
        <?php include 'include/header.php'; ?>

        <div class="container mx-auto px-4 py-6 ">

            <h2 class="text-2xl font-semibold text-gray-800">Your Returned Books</h2>
            <?php

            if ($returnResult && $returnResult->num_rows > 0) {
                echo "<div class='overflow-x-auto bg-white shadow-md rounded-lg'>";
                echo "<table class='min-w-full table-auto'>
                        <thead class='bg-blue-600 text-white'>
                            <tr>
                                <th class='px-6 py-3 text-left'>Borrow ID</th>
                                <th class='px-6 py-3 text-left'>Book Title</th>
                                <th class='px-6 py-3 text-left'>Author</th>
                                <th class='px-6 py-3 text-left'>Borrow Date</th>
                                <th class='px-6 py-3 text-left'>Return Date</th>
                                <th class='px-6 py-3 text-left'>Days Borrowed</th>
                            </tr>
                        </thead>
                        <tbody class='bg-gray-50'>";

                while ($row = $returnResult->fetch_assoc()) {
                    echo "<tr class='border-b'>
                            <td class='px-6 py-4'>" . htmlspecialchars($row['borrow_id']) . "</td>
                            <td class='px-6 py-4'>" . htmlspecialchars($row['B_title']) . "</td>
                            <td class='px-6 py-4'>" . htmlspecialchars($row['author']) . "</td>
                            <td class='px-6 py-4'>" . htmlspecialchars($row['borrow_date']) . "</td>
                            <td class='px-6 py-4'>" . htmlspecialchars($row['return_date']) . "</td>
                            <td class='px-6 py-4'>" . htmlspecialchars($row['days_borrowed']) . " days</td>
                        </tr>";
                }
                echo "</tbody></table></div>";
            } else {
                echo "<div class='mt-4 text-center text-gray-500'>No returned books found for your account.</div>";
            }
            ?>

            <!-- Pagination for Returned Books -->
            <div class="mt-4 text-center">
                <form method="GET" class="inline-block mb-2">
                    <select name="books_per_page" id="books_per_page" onchange="this.form.submit()" class="border rounded px-2 py-1">
                        <option value="5" <?= ($recordsPerPage == 5 ? "selected" : "") ?>>5</option>
                        <option value="10" <?= ($recordsPerPage == 10 ? "selected" : "") ?>>10</option>
                        <option value="20" <?= ($recordsPerPage == 20 ? "selected" : "") ?>>20</option>
                    </select>
                    <input type="hidden" name="return_page" value="<?= $returnPage ?>">
                </form>

                <!-- Navigation Controls for Returned Books -->
                <?php
                if ($returnPage > 1) {
                    echo "<a href='?return_page=1&books_per_page=$recordsPerPage' class='px-3 py-2 bg-gray-200 border rounded mx-1'>«</a>";
                    echo "<a href='?return_page=" . ($returnPage - 1) . "&books_per_page=$recordsPerPage' class='px-3 py-2 bg-gray-200 border rounded mx-1'>‹</a>";
                }
                echo "<span class='px-3 py-2 bg-blue-600 text-white border rounded mx-1'>Page $returnPage of $totalReturnPages</span>";

                if ($returnPage < $totalReturnPages) {
                    echo "<a href='?return_page=" . ($returnPage + 1) . "&books_per_page=$recordsPerPage' class='px-3 py-2 bg-gray-200 border rounded mx-1'>›</a>";
                    echo "<a href='?return_page=$totalReturnPages&books_per_page=$recordsPerPage' class='px-3 py-2 bg-gray-200 border rounded mx-1'>»</a>";
                }
                ?>
            </div>
        </div>

        <!-- Footer at the Bottom -->
        <footer class="bg-blue-600 text-white mt-auto">
            <?php include 'include/footer.php'; ?>
        </footer>
</main>