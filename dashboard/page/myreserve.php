<?php
include '../config.php';

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

// Prepare and execute the query
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $idno);  // Bind the logged-in user's IDno
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="flex">
    <!-- Sidebar Section -->
    <?php include $sidebars[$userType] ?? ''; ?>

    <!-- Reservation Content Section -->
    <div class="flex-grow">
        <!-- Header at the Top -->
        <?php include 'include/header.php'; ?>

        <div class="container mx-auto px-4 py-6">
            <h2 class="text-3xl font-semibold mb-4 text-center">Your Reservations</h2>

            <!-- Search Input -->
            <div class="w-full max-w-md mx-auto mb-4">
                <input type="text" id="searchInput" class="w-full py-2 px-4 border rounded-md" placeholder="Search by Book Title..." onkeyup="filterReservations()" />
            </div>

            <!-- Table -->
            <table class="min-w-full table-auto bg-white shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="py-2 px-4 border-b">Book Title</th>
                        <th class="py-2 px-4 border-b">Book Copy</th>
                        <th class="py-2 px-4 border-b">Reserved At</th>
                        <th class="py-2 px-4 border-b">Status</th>
                    </tr>
                </thead>
                <tbody id="reservationTableBody">
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Format the reservation date
                            $reserveAt = date('Y-m-d H:i:s', strtotime($row['reserved_at']));

                            echo "<tr class='reservation-row'>
                                    <td class='py-2 px-4'>" . htmlspecialchars($row['B_title']) . "</td>
                                    <td class='py-2 px-4'>" . htmlspecialchars($row['book_copy']) . "</td>
                                    <td class='py-2 px-4'>" . $reserveAt . "</td>
                                    <td class='py-2 px-4'>" . htmlspecialchars($row['status']) . "</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td class='py-2 px-4 text-center' colspan='4'>No reservations Available.</td></tr>";
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

        <!-- Footer -->
        <footer class="bg-blue-600 text-white mt-auto">
            <?php include 'include/footer.php'; ?>
        </footer>
    </div>
</div>

<script>
    let currentPage = 1;
    const rowsPerPage = 10;
    let filteredRows = [];

    document.getElementById('searchInput').addEventListener('input', filterReservations);

    function filterReservations() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('.reservation-row');
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
        filterReservations();
    });

    // Periodically update reservation data with AJAX
    function fetchUpdatedReservations() {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "fetch_reservations.php?idno=" + <?php echo json_encode($idno); ?>, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('reservationTableBody').innerHTML = xhr.responseText;
                filterReservations(); // Reapply the filter if necessary
            }
        };
        xhr.send();
    }

    // Fetch updated reservations every minute (60000 milliseconds)
    setInterval(fetchUpdatedReservations, 60000); 
</script>

<style>
    /* Hover effect for rows */
    .reservation-row:hover {
        cursor: pointer;
        background-color: #f0f0f0;
    }
</style>
