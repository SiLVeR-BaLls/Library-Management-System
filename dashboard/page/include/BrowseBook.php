<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<?php

// Database Connection
$conn = new mysqli('localhost', 'root', '', 'lms');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Determine User Type
$userTypes = ['admin', 'student', 'librarian', 'faculty'];
$userType = null;
$idno = null;

// Find the logged-in user and their type
foreach ($userTypes as $type) {
    if (!empty($_SESSION[$type]['IDno'])) {
        $userType = $type;
        $idno = $_SESSION[$type]['IDno'];
        break;
    }
}

// Initialize variables for messages
$message = ""; // Variable to store messages
$message_type = ""; // Variable to store message type (e.g. success, error)

// Check if connection is still open before executing query
if ($conn && !$conn->connect_error) {
    $sql = "SELECT 
                book.book_id, 
                book.B_title, 
                book.subtitle, 
                book.author, 
                book.LCCN, 
                book.ISBN, 
                book.ISSN, 
                book.copyright, 
                book.MT, 
                book.ST, 
                book.extent,
                
                GROUP_CONCAT(DISTINCT coauthor.Co_Name SEPARATOR ', ') AS coauthor, 
                COUNT(CASE WHEN book_copies.status = 'Available' THEN 1 END) AS available_count,
                COUNT(book_copies.book_copy_ID) AS total_count
            FROM 
                book 
            LEFT JOIN 
                coauthor ON book.book_id = coauthor.book_id 
            LEFT JOIN 
                book_copies ON book.book_id = book_copies.book_id
            GROUP BY 
                book.book_id, 
                book.B_title, 
                book.subtitle, 
                book.author, 
                book.LCCN, 
                book.ISBN, 
                book.ISSN, 
                book.copyright, 
                book.MT, 
                book.ST, 
                book.extent
            ORDER BY 
                book.B_title;
            ";

    // Execute the query and get the result
    $result = $conn->query($sql);

    if ($result === false) {
        // Query failed, handle error
        echo "Error executing query: " . $conn->error;
    }
} else {
    // Connection is not open, handle error
    echo "Database connection is closed or failed.";
}
?>

<div class="my-2 px-10 flex w-full justify-between items-center">
    <div class="flex flex-row gap-2 items-center">
        <input type="text" id="searchInput"
            class="form-input block w-40 sm:w-60 px-3 py-1 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-gray-700 text-sm"
            placeholder="Enter search term...">

        <select id="searchType"
            class="form-select block px-3 py-1 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-gray-700 text-sm">
            <option value="all">All</option>
            <option value="title">Title</option>
            <option value="author">Author</option>
            <option value="coauthor">Co-Author</option>
            <option value="LCCN">LCCN</option>
            <option value="ISBN">ISBN</option>
            <option value="ISSN">ISSN</option>
            <option value="MT">Material Type</option>
            <option value="ST">Sub Type</option>
            <option value="extent">Extent</option>
        </select>
    </div>
    <button id="filterLink" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 font-semibold">
        Filter
    </button>
</div>

<div class="p-3 overflow-x-auto w-full table-container rounded-lg shadow-md flex flex-col items-center">
    <div class="flex flex-row w-full md:w-full justify-between items-start gap-2">
        <table class="w-4/5 table-screen rounded-lg">
            <thead class="w-screen" style="color: <?= $text2 ?>; background: <?= $sidebar ?>;">
                <tr>
                    <th class="px-3 py-1 rounded-tl-lg">Title</th>
                    <th class="px-3 py-1">Author</th>
                    <th class="px-3 py-1 hidden">Co-Author</th>
                    <th class="px-3 py-1 w-28">Material Type</th>
                    <th class="px-3 py-1 w-28">Sub Type</th>
                    <th class="px-3 py-1 rounded-tr-lg">Copies</th>
                </tr>
            </thead>
            <tbody id="bookTableBody" class="bg-white">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="border-y border-solid cursor-pointer hover:bg-gray-200"
                            data-title="<?php echo htmlspecialchars($row['B_title']); ?>"
                            data-author="<?php echo htmlspecialchars($row['author']); ?>"
                            data-lccn="<?php echo htmlspecialchars($row['LCCN']); ?>"
                            data-isbn="<?php echo htmlspecialchars($row['ISBN']); ?>"
                            data-issn="<?php echo htmlspecialchars($row['ISSN']); ?>"
                            data-material-type="<?php echo htmlspecialchars($row['MT']); ?>"
                            data-sub-type="<?php echo htmlspecialchars($row['ST']); ?>"
                            data-available-count="<?php echo $row['available_count']; ?>"
                            data-total-count="<?php echo $row['total_count']; ?>"
                            data-copyright="<?php echo htmlspecialchars($row['copyright']); ?>"
                            onclick="
                                <?php if (empty($idno)): ?>
                                    alert('You are not logged in yet');
                                <?php else: ?>
                                    window.location.href='ViewBook.php?title=<?php echo urlencode($row['book_id']); ?>';
                                <?php endif; ?>
                            "
                            onmouseenter="showPopup(event, this)" onmouseleave="hidePopup()">

                            <td class="px-4 py-2 title"><?php echo htmlspecialchars($row['B_title']); ?></td>
                            <td class="px-4 py-2 author"><?php echo htmlspecialchars($row['author']); ?></td>
                            <td class="px-4 py-2 hidden coauthor"><?php echo htmlspecialchars($row['coauthor']); ?></td>
                            <td class="px-4 py-2 MT"><?php echo htmlspecialchars($row['MT']); ?></td>
                            <td class="px-4 py-2 ST"><?php echo htmlspecialchars($row['ST']); ?></td>
                            <td class="px-4 py-2 flex justify-center gap-2">
                                <?php if ($row['available_count'] > 0): ?>
                                    <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center">✔</div>
                                <?php else: ?>
                                    <div class="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center">✖</div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center py-4">No books found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="top-borrowed-books p-3 bg-gray-100 rounded-lg shadow-md w-1/5">
    <h3 class="text-lg font-semibold mb-3">Top Borrowed Book Copies</h3>
    <ul>
        <?php
        // Query to fetch count of borrowed copies per book and the actual display title
        $query = "
           SELECT 
    bb.B_title AS book_title, 
    COUNT(bb.book_copy) AS borrow_count
FROM 
    borrow_book b
LEFT JOIN 
    book_copies bb ON b.book_copy = bb.book_copy  -- Left join for all book copies
RIGHT JOIN 
    book_copies bb2 ON b.book_copy = bb2.book_copy  -- Right join for all borrow records
GROUP BY 
    bb.B_title
ORDER BY 
    borrow_count DESC
LIMIT 3;

        ";

        $result = $conn->query($query);

        if ($result && $result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
               <li class="mb-2">
    <strong><?php echo htmlspecialchars($row['book_title']); ?> -</strong> 
    <span class="text-gray-600">(<?php echo $row['borrow_count']; ?>)</span>
</li>

        <?php
            endwhile;
        else:
        ?>
            <li class="text-red-600">No borrowed books found.</li>
        <?php endif; ?>
    </ul>
</div>


    </div>

    <div class="flex justify-center items-center space-x-2 my-3 flex-col md:flex-row md:space-x-4">
        <button id="prevBtn" onclick="prevPage()" class="btn-pagination px-4 py-1 bg-gray-800 text-white rounded-lg cursor-pointer disabled:bg-gray-400 disabled:cursor-not-allowed transition duration-300 hover:bg-gray-600" disabled>Previous</button>
        <span id="pageInfo" class="text-sm text-gray-600 font-medium">Page 1 of X</span> <button id="nextBtn" onclick="nextPage()" class="btn-pagination px-4 py-1 bg-gray-800 text-white rounded-lg cursor-pointer transition duration-300 hover:bg-gray-600">Next</button>
    </div>
</div>

<script>
    $(document).ready(function() {
        let currentPage = 1;
        const rowsPerPage = 10; // Set number of rows per page
        let filteredRows = []; // Store filtered rows
        let allRows = []; // Store all rows before filtering

        // Capture all rows initially
        function captureAllRows() {
            allRows = [];
            $('#bookTableBody tr').each(function() {
                allRows.push($(this)); // Store each row in the allRows array
            });
            filteredRows = allRows; // Initialize filteredRows with all rows
        }

        // Initialize all rows and handle search/filter
        captureAllRows();
        displayTablePage(currentPage);

        // Event listener for input changes
        $('#searchInput').on('keyup', filterTable);
        $('#searchType').on('change', filterTable);

        // Filter function to filter all rows and update pagination
        function filterTable() {
            const searchType = $('#searchType').val();
            const searchText = $('#searchInput').val().trim().toLowerCase(); // Trim and convert to lowercase
            const selectedType = $('input[name="materialType"]:checked').val();
            const selectedSubType = $('input[name="subType"]:checked').val();

            filteredRows = allRows.filter(function(row) {
                const rowTitle = row.find('.title').text().trim().toLowerCase();
                const rowAuthor = row.find('.author').text().trim().toLowerCase();
                const rowCoauthor = row.find('.coauthor').text().trim().toLowerCase();
                const rowLCCN = row.find('.lccn').text().trim().toLowerCase();
                const rowISBN = row.find('.isbn').text().trim().toLowerCase();
                const rowISSN = row.find('.issn').text().trim().toLowerCase();
                const rowMT = row.find('.MT').text().trim().toLowerCase();
                const rowST = row.find('.ST').text().trim().toLowerCase();
                const rowExtent = row.find('.extent').text().trim().toLowerCase();

                let match = false;
                switch (searchType) {
                    case 'all':
                        match = rowTitle.includes(searchText) ||
                            rowAuthor.includes(searchText) ||
                            rowCoauthor.includes(searchText) ||
                            rowLCCN.includes(searchText) ||
                            rowISBN.includes(searchText) ||
                            rowISSN.includes(searchText) ||
                            rowMT.includes(searchText) ||
                            rowST.includes(searchText) ||
                            rowExtent.includes(searchText);
                        break;
                    case 'title': match = rowTitle.includes(searchText); break;
                    case 'author': match = rowAuthor.includes(searchText); break;
                    case 'coauthor': match = rowCoauthor.includes(searchText); break;
                    case 'lccn': match = rowLCCN.includes(searchText); break;
                    case 'isbn': match = rowISBN.includes(searchText); break;
                    case 'issn': match = rowISSN.includes(searchText); break;
                    case 'MT': match = rowMT.includes(searchText); break;
                    case 'ST': match = rowST.includes(searchText); break;
                    case 'extent': match = rowExtent.includes(searchText); break;
                }

                // Apply material type and subtype filters
                const typeMatch = !selectedType || rowMT === selectedType.toLowerCase();
                const subTypeMatch = !selectedSubType || rowST === selectedSubType.toLowerCase();

                return match && typeMatch && subTypeMatch;
            });

            currentPage = 1; // Reset to the first page after filtering
            displayTablePage(currentPage); // Update the displayed rows after filtering
        }

        // Display current page of rows
        function displayTablePage(pageNumber) {
            const startIndex = (pageNumber - 1) * rowsPerPage;
            const endIndex = startIndex + rowsPerPage;
            const rowsToDisplay = filteredRows.slice(startIndex, endIndex);

            // Clear current table body and append filtered rows
            $('#bookTableBody').empty().append(rowsToDisplay);

            // Update pagination
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);

            if (filteredRows.length === 0) {
                // If no rows to display, show "1 of 0" and disable pagination
                $('#pageInfo').text('Page 1 of 0');
                $('#prevBtn, #nextBtn').prop('disabled', true);
            } else {
                $('#pageInfo').text(`Page ${pageNumber} of ${totalPages}`);
                $('#prevBtn').prop('disabled', pageNumber <= 1);
                $('#nextBtn').prop('disabled', pageNumber >= totalPages);
            }
        }

        // Pagination functions
        function nextPage() {
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                displayTablePage(currentPage);
            }
        }

        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                displayTablePage(currentPage);
            }
        }

        // Bind the pagination buttons to their functions
        $('#nextBtn').on('click', nextPage);
        $('#prevBtn').on('click', prevPage);

        // Initialize with all rows visible
        filterTable(); // Ensure the filter shows all rows initially

        // Modal filter functionality
        $('#filterApply').on('click', function() {
            filterTable(); // Apply filters and update pagination
            $('#materialTypeModal').hide(); // Hide the modal
        });

        $('#filterClear').on('click', function() {
            $('input[name="materialType"]').prop('checked', false);
            $('input[name="subType"]').prop('checked', false);
            $('#searchInput').val('');
            $('#searchType').val('all');
            filteredRows = allRows; // Reset filters
            currentPage = 1; // Reset to the first page
            displayTablePage(currentPage); // Update the displayed rows
        });

        // Replace button click with link click for opening the modal
        $('#filterLink').on('click', function() {
            $('#materialTypeModal').show(); // Show the modal
        });

        // Close modal when clicking the close link
        $('#modalCloseLink').on('click', function() {
            $('#materialTypeModal').hide();
        });

        // Close modal when clicking outside the modal content
        $(window).on('click', function(event) {
            if ($(event.target).is('#materialTypeModal')) {
                $('#materialTypeModal').hide();
            }
        });
    });
</script>

<div id="materialTypeModal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div style="color: <?= $text2 ?>; background: <?= $background ?>;" class="modal-content relative p-6 rounded-lg w-full max-w-4xl bg-white shadow-lg">
        <div class="absolute top-2 right-2">
            <a href="javascript:void(0);" id="modalCloseLink" style="color: <?= $text1 ?>" class="hover:underline font-semibold">Close</a>
        </div>
        <form id="materialTypeForm">
            <h2 class="text-2xl font-bold mb-6 text-center">Material Type Filters</h2>
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 mb-6">
                <h3 class="col-span-4 text-xl font-semibold">Select Material Types</h3>
                <div class="flex items-center">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="materialType" value="Book" class="h-4 w-4" />
                        <span>Book</span>
                    </label>
                </div>
                <div class="flex items-center">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="materialType" value="Computer File" class="h-4 w-4" />
                        <span>Computer File</span>
                    </label>
                </div>
                <div class="flex items-center">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="materialType" value="Electronic Book" class="h-4 w-4" />
                        <span>Electronic Book (E-Book)</span>
                    </label>
                </div>
                <div class="flex items-center">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="materialType" value="Equipment" class="h-4 w-4" />
                        <span>Equipment</span>
                    </label>
                </div>
                <div class="flex items-center">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="materialType" value="Kit" class="h-4 w-4" />
                        <span>Kit</span>
                    </label>
                </div>
                <div class="flex items-center">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="materialType" value="Manuscript Language Material" class="h-4 w-4" />
                        <span>Manuscript Language Material</span>
                    </label>
                </div>
                <div class="flex items-center">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="materialType" value="Map" class="h-4 w-4" />
                        <span>Map</span>
                    </label>
                </div>
                <div class="flex items-center">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="materialType" value="Mixed Material" class="h-4 w-4" />
                        <span>Mixed Material</span>
                    </label>
                </div>
                <div class="flex items-center">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="materialType" value="Music" class="h-4 w-4" />
                        <span>Music (Printed)</span>
                    </label>
                </div>
                <div class="flex items-center">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="materialType" value="Picture" class="h-4 w-4" />
                        <span>Picture</span>
                    </label>
                </div>
                <div class="flex items-center">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="materialType" value="Serial" class="h-4 w-4" />
                        <span>Serial</span>
                    </label>
                </div>
                <div class="flex items-center">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="materialType" value="Musical Sound Recording" class="h-4 w-4" />
                        <span>Musical Sound Recording</span>
                    </label>
                </div>
                <div class="flex items-center">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="materialType" value="NonMusical Sound Recording" class="h-4 w-4" />
                        <span>Non-Musical Sound Recording</span>
                    </label>
                </div>
                <div class="flex items-center">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="materialType" value="Video" class="h-4 w-4" />
                        <span>Video</span>
                    </label>
                </div>
                <div class="flex items-center">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="materialType" value="Journal" class="h-4 w-4" />
                        <span>Journal</span>
                    </label>
                </div>
            </div>
            <div class="mb-6">
                <h3 class="text-xl font-semibold">Select SubType</h3>
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 mt-4">
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="subType" value="Not Assigned" class="h-4 w-4" />
                            <span>Not Assigned</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="subType" value="Braille" class="h-4 w-4" />
                            <span>Braille</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="subType" value="Hardcover" class="h-4 w-4" />
                            <span>Hardcover</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="subType" value="LargePrint" class="h-4 w-4" />
                            <span>Large Print</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="subType" value="Paperback" class="h-4 w-4" />
                            <span>Paperback</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="subType" value="Picture" class="h-4 w-4" />
                            <span>Picture</span>
                        </label>
                    </div>
                </div>
                <div class="flex items-center">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="subType" value="Dictionary" class="h-4 w-4" />
                        <span>Dictionary</span>
                    </label>
                </div>
            </div>
            <div class="flex gap-4 mt-6 justify-center">
                <button type="button" id="filterApply" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Apply Filter</button>
                <button type="button" id="filterClear" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Clear Filters</button>
            </div>
        </form>
    </div>
</div>

<style>
    #materialTypeModal .modal-content {
        margin: auto; /* Center horizontally */
        top: 50%; /* Center vertically */
        transform: translateY(-50%); /* Adjust for vertical centering */
    }
</style>