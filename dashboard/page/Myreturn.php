<?php
    include '../config.php';

    // Query to get book titles, borrow_id, borrow_date, due_date, return_date, and IDno where return_date is NOT NULL for the logged-in user
    $query = "
        SELECT DISTINCT b.borrow_id, c.B_title, b.borrow_date, b.due_date, b.return_date, b.IDno
        FROM borrow_book b
        JOIN book_copies c ON b.book_copy = c.book_copy
        WHERE b.return_date IS NOT NULL AND b.IDno = ?
        ORDER BY b.borrow_id ASC
    ";
    
    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $idno);  // Bind the logged-in user's IDno
    $stmt->execute();
    $result = $stmt->get_result();

    // Add a new query to calculate the status of returned books
    $statusQuery = "
        SELECT DISTINCT b.borrow_id, c.B_title, b.borrow_date, b.due_date, b.return_date,
        CASE 
            WHEN b.return_date <= b.due_date THEN 'On Time'
            ELSE 'Overdue'
        END AS status
        FROM borrow_book b
        JOIN book_copies c ON b.book_copy = c.book_copy
        WHERE b.return_date IS NOT NULL AND b.IDno = ?
        ORDER BY b.borrow_id ASC
    ";

    // Prepare and execute the status query
    $statusStmt = $conn->prepare($statusQuery);
    $statusStmt->bind_param("s", $idno);  // Bind the logged-in user's IDno
    $statusStmt->execute();
    $statusResult = $statusStmt->get_result();
?>

<div class="flex">
    <!-- Sidebar Section -->
    <?php include $sidebars[$userType] ?? ''; ?>

    <!-- BrowseBook Content Section -->
    <div class="flex-grow">
        <!-- Header at the Top -->
        <?php include 'include/header.php'; ?>

        <div class="container mx-auto px-4 py-6">
            <h2 class="text-3xl font-semibold mb-4 text-center">Your Returned Books</h2>

            <!-- Search Input -->
            <div class="w-full max-w-md mx-auto mb-4">
                <input type="text" id="searchInput" class="w-full py-2 px-4 border rounded-md" placeholder="Search by Book Title..." onkeyup="filterBooks()" />
            </div>

            <!-- Table -->
            <table class="min-w-full table-auto bg-white shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="py-2 px-4 border-b">Book Title</th>
                        <th class="py-2 px-4 border-b">Borrow Date</th>
                        <th class="py-2 px-4 border-b">Due Date</th>
                        <th class="py-2 px-4 border-b">Return Date</th>
                    </tr>
                </thead>
                <tbody id="bookTableBody">
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Format the dates
                            $borrowDate = date('Y-m-d H:i:s', strtotime($row['borrow_date']));
                            $dueDate = date('Y-m-d', strtotime($row['due_date']));
                            $returnDate = date('Y-m-d', strtotime($row['return_date'])); // Format the return date

                            echo "<tr class='book-row'>
                                    <td class='py-2 px-4'>" . htmlspecialchars($row['B_title']) . "</td>
                                    <td class='py-2 px-4'>" . $borrowDate . "</td>
                                    <td class='py-2 px-4'>" . $dueDate . "</td>
                                    <td class='py-2 px-4'>" . $returnDate . "</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td class='py-2 px-4 text-center' colspan='4'>No returned books available.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Pagination Controls -->
            <div class="flex justify-center items-center space-x-4 my-6 flex-col md:flex-row md:space-x-6">
                <button id="prevBtn" onclick="prevPage()" class="px-6 py-2 bg-gray-800 text-white rounded-lg cursor-pointer disabled:bg-gray-400 disabled:cursor-not-allowed transition duration-300 hover:bg-gray-600" disabled>Previous</button>
                <span id="pageInfo" class="text-lg text-gray-600 font-medium">Page 1 of X</span>
                <button id="nextBtn" onclick="nextPage()" class="px-6 py-2 bg-gray-800 text-white rounded-lg cursor-pointer transition duration-300 hover:bg-gray-600">Next</button>
            </div>
        </div>

        <div class="container mx-auto px-4 py-6">
            <!-- New Table for Status -->
            <h2 class="text-3xl font-semibold mb-4 text-center">Returned Books Status</h2>
            <table class="min-w-full table-auto bg-white shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="py-2 px-4 border-b">Book Title</th>
                        <th class="py-2 px-4 border-b">Borrow Date</th>
                        <th class="py-2 px-4 border-b">Due Date</th>
                        <th class="py-2 px-4 border-b">Return Date</th>
                        <th class="py-2 px-4 border-b">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($statusResult && $statusResult->num_rows > 0) {
                        while ($row = $statusResult->fetch_assoc()) {
                            // Format the dates
                            $borrowDate = date('Y-m-d H:i:s', strtotime($row['borrow_date']));
                            $dueDate = date('Y-m-d', strtotime($row['due_date']));
                            $returnDate = date('Y-m-d', strtotime($row['return_date']));

                            echo "<tr>
                                    <td class='py-2 px-4'>" . htmlspecialchars($row['B_title']) . "</td>
                                    <td class='py-2 px-4'>" . $borrowDate . "</td>
                                    <td class='py-2 px-4'>" . $dueDate . "</td>
                                    <td class='py-2 px-4'>" . $returnDate . "</td>
                                    <td class='py-2 px-4'>" . htmlspecialchars($row['status']) . "</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td class='py-2 px-4 text-center' colspan='5'>No returned books available.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <footer>
            <?php include 'include/footer.php'; ?>
        </footer>
    </div>
</div>

<script>
    let currentPage = 1;
    const rowsPerPage = 10;
    let filteredRows = [];

    document.getElementById('searchInput').addEventListener('input', filterBooks);

    function filterBooks() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('.book-row');
        filteredRows = [];

        rows.forEach(row => {
            const rowTitle = row.querySelector('td').innerText.toLowerCase();
            if (rowTitle.includes(searchTerm)) {
                row.style.display = "";
                filteredRows.push(row);
            } else {
                row.style.display = "none";
            }
        });
        updatePagination();
    }

    function updatePagination() {
        const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
        if (currentPage < 1) currentPage = 1;
        if (currentPage > totalPages) currentPage = totalPages;

        filteredRows.forEach((row, index) => {
            row.style.display = (index >= (currentPage - 1) * rowsPerPage && index < currentPage * rowsPerPage) ? "" : "none";
        });

        document.getElementById("pageInfo").innerText = `Page ${currentPage} of ${totalPages}`;
        updatePageControls();
    }

    function prevPage() {
        if (currentPage > 1) {
            currentPage--;
            updatePagination();
        }
    }

    function nextPage() {
        const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            updatePagination();
        }
    }

    function updatePageControls() {
        const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
        document.getElementById("prevBtn").disabled = currentPage === 1;
        document.getElementById("nextBtn").disabled = currentPage === totalPages;
    }

    document.addEventListener("DOMContentLoaded", function() {
        filterBooks();
    });
</script>

<style>
    /* Hover effect for rows */
    .book-row:hover {
        cursor: pointer;
        background-color: #f0f0f0;
    }
</style>
