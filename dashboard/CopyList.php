<?php
    include 'config.php';

    // Initialize message variables
    $message = "";
    $message_type = "";

    // Get the book B_title from the URL (filtering by B_title)
    $book_B_title = isset($_GET['B_title']) ? $_GET['B_title'] : '';

    // Query for book_copies (you can modify this based on your needs)
    $query = "SELECT * FROM book_copies WHERE B_title LIKE '%$book_B_title%'"; // Example query to filter by B_title
    $result = mysqli_query($conn, $query);
?>

<div class="flex">
    <!-- Sidebar Section -->
    <?php include $sidebars[$userType] ?? ''; ?>

    <!-- BrowseBook Content Section -->
    <div class="flex-grow">
        <!-- Header at the Top -->
        <?php include 'include/header.php'; ?>

        <div class="container mx-auto px-4 py-6 ">
        <h2 class="text-3xl font-semibold mb-6">Book Copy List</h2>

            <?php if ($message): ?>
                <div class="mb-4 p-4 <?php echo $message_type == 'error' ? 'bg-red-500 text-white' : 'bg-green-500 text-white'; ?> rounded">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Search and Status Radio Buttons -->
            <div class="flex mb-4 space-x-4">
                <div class="w-full max-w-md">
                    <input type="text" id="searchInput" class="w-full py-2 px-4 border rounded-md" placeholder="Search by Book B_title or ID..." onkeyup="filterBooks()" />
                </div>

                <!-- Radio Buttons for Status Filter -->
                <div class="w-full max-w-md">
                    <div class="radio-input flex space-x-4">
                        <label class="flex-1 text-center px-4 py-2 font-semibold text-black cursor-pointer transition-all hover:bg-blue-400 bg-blue-200 border border-gray-300 rounded-md">
                            <input type="radio" name="statusFilter" value="all" id="allRadio" class="hidden" checked>
                            <span class="block">All Status</span>
                        </label>
                        <label class="flex-1 text-center px-4 py-2 font-semibold text-black cursor-pointer transition-all hover:bg-blue-400 bg-blue-200 border border-gray-300 rounded-md">
                            <input type="radio" name="statusFilter" value="Available" id="availableRadio" class="hidden">
                            <span class="block">Available</span>
                        </label>
                        <label class="flex-1 text-center px-4 py-2 font-semibold text-black cursor-pointer transition-all hover:bg-blue-400 bg-blue-200 border border-gray-300 rounded-md">
                            <input type="radio" name="statusFilter" value="Borrowed" id="BorrowedRadio" class="hidden">
                            <span class="block">Borrowed</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <table class="min-w-full table-auto bg-white shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="py-2 px-4 border-b">BOOK ID</th>
                        <th class="py-2 px-4 border-b">Status</th>
                        <th class="py-2 px-4 border-b">B_title</th>
                        <th class="py-2 px-4 border-b">Rating</th>
                    </tr>
                </thead>
                <tbody id="bookTableBody">
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $rating = $row['rating'];
                            $maxStars = 5;
                            echo "<tr class='book-row' data-status='" . htmlspecialchars($row['status']) . "' data-B_title='" . htmlspecialchars($row['B_title']) . "' data-book_copy='" . htmlspecialchars($row['book_copy']) . "' onclick='window.location.href=\"viewcopyList.php?book_copy_ID=" . urlencode($row['book_copy_ID']) . "\"'>
                                    <td class='py-2 px-4'>" . htmlspecialchars($row['book_copy']) . "</td>
                                    <td class='py-2 px-4'>" . htmlspecialchars($row['status']) . "</td>
                                    <td class='py-2 px-4'>" . htmlspecialchars($row['B_title']) . "</td>
                                    <td class='py-2 px-4'>"; 

                                        // Star Rating logic
                                        $rating = $row['rating'];
                                        $maxStars = 5;
                                        for ($i = 1; $i <= $maxStars; $i++) {
                                            if ($rating >= $i) {
                                                switch ($rating) {
                                                    case 5: echo "<span class='text-green-500 text-lg'>&#9733;</span>"; break;
                                                    case 4: echo "<span class='text-blue-500 text-lg'>&#9733;</span>"; break;
                                                    case 3: echo "<span class='text-yellow-400 text-lg'>&#9733;</span>"; break;
                                                    case 2: echo "<span class='text-orange-500 text-lg'>&#9733;</span>"; break;
                                                    case 1: echo "<span class='text-red-500 text-lg'>&#9733;</span>"; break;
                                                    default: echo "<span class='text-gray-400 text-lg'>&#9733;</span>";
                                                }
                                            } else {
                                                echo "<span class='text-gray-400 text-lg'>&#9733;</span>";
                                            }
                                        }
                            echo "</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='py-2 px-4 text-center'>No book_copies Available.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Pagination Controls -->
            <div class="flex justify-center items-center space-x-4 my-6 flex-col md:flex-row md:space-x-6">
                <button id="prevBtn" onclick="prevPage()" class="btn-pagination px-6 py-2 bg-gray-800 text-white rounded-lg cursor-pointer disabled:bg-gray-400 disabled:cursor-not-allowed transition duration-300 hover:bg-gray-600" disabled>Previous</button>
                <span id="pageInfo" class="text-lg text-gray-600 font-medium">Page 1 of X</span> <!-- Placeholder for page info -->
                <button id="nextBtn" onclick="nextPage()" class="btn-pagination px-6 py-2 bg-gray-800 text-white rounded-lg cursor-pointer transition duration-300 hover:bg-gray-600">Next</button>
            </div>
        </div>

        <!-- Footer at the Bottom -->
        <footer>
            <?php include 'include/footer.php'; ?>
        </footer>
    </div>
</div>

<script>
    // JavaScript for managing search and pagination
    let currentPage = 1;
    const rowsPerPage = 10; // Number of rows per page
    let filteredRows = []; // Holds the filtered rows after applying search filter

    // Handle search input and status filter changes
    document.getElementById('searchInput').addEventListener('input', filterBooks);
    document.querySelectorAll('input[name="statusFilter"]').forEach(radio => {
        radio.addEventListener('change', filterBooks);
    });

    // Function to filter book_copies based on search term and selected status
    function filterBooks() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const selectedStatus = document.querySelector('input[name="statusFilter"]:checked').value;

        const rows = document.querySelectorAll('.book-row');
        filteredRows = [];

        rows.forEach(row => {
            const rowTitle = row.querySelector('td:nth-child(3)').innerText.toLowerCase(); // B_title column
            const rowStatus = row.getAttribute('data-status');
            const rowBookCopy = row.getAttribute('data-book_copy').toLowerCase(); // Book copy column

            // Check if row matches both search term and selected status
            const matchesSearch = rowTitle.includes(searchTerm) || rowBookCopy.includes(searchTerm);
            const matchesStatus = selectedStatus === 'all' || rowStatus === selectedStatus;

            if (matchesSearch && matchesStatus) {
                row.style.display = ""; // Show matching rows
                filteredRows.push(row); // Add to the filtered list
            } else {
                row.style.display = "none"; // Hide non-matching rows
            }
        });

        // Update pagination with filtered rows
        updatePagination();
    }

    // Function to update pagination based on filtered rows
    function updatePagination() {
        const totalPages = Math.ceil(filteredRows.length / rowsPerPage); // Adjust for filtered rows
        if (currentPage < 1) currentPage = 1;
        if (currentPage > totalPages) currentPage = totalPages;

        // Hide all rows and then display the appropriate ones for the current page
        filteredRows.forEach((row, index) => {
            row.style.display = (index >= (currentPage - 1) * rowsPerPage && index < currentPage * rowsPerPage) ? "" : "none";
        });

        // Update page info text
        document.getElementById("pageInfo").innerText = `Page ${currentPage} of ${totalPages}`;
        updatePageControls(); // Update page control buttons
    }

    // Function to go to the previous page
    function prevPage() {
        if (currentPage > 1) {
            currentPage--;
            updatePagination();
        }
    }

    // Function to go to the next page
    function nextPage() {
        const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            updatePagination();
        }
    }

    // Function to update the pagination controls (Previous, Next)
    function updatePageControls() {
        const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
        document.getElementById("prevBtn").disabled = currentPage === 1;
        document.getElementById("nextBtn").disabled = currentPage === totalPages;
    }

    // Call this on page load to initialize the table view
    document.addEventListener("DOMContentLoaded", function() {
        filterBooks(); // Initialize the table with the filter applied and pagination
    });
</script>

<style>
    /* active (selected) state */
    .radio-input label:has(input:checked) {
        background-color: #1D4ED8;
        color: #fff;
    }

    /* Hover color for table rows */
    .user-row:hover {
        background-color: #f0f0f0;
    }
</style>
