<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<?php
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
            book_copies.callNumber,
            GROUP_CONCAT(DISTINCT coauthor.Co_Name SEPARATOR ', ') AS coauthor,
            COUNT(CASE WHEN book_copies.status = 'Available' THEN 1 END) AS available_count,
            COUNT(book_copies.book_copy_ID) AS total_count,
            GROUP_CONCAT(DISTINCT book_copies.callNumber SEPARATOR ', ') AS callNumber
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
            book.B_title;";

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
    <div>
        <button id="filterLink" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 font-semibold">
            Filter
        </button>
    </div>

</div>

<div class="p-3 overflow-x-auto w-full table-container rounded-lg shadow-md flex flex-col items-center">
    <div class="flex flex-row w-full md:w-full justify-between items-start gap-2">
        <table class="w-4/5 table-screen rounded-lg">
            <thead class="w-screen" style="color: <?= $text2 ?>; background: <?= $sidebar ?>;">
                <tr>
                    <th class="px-3 py-1 rounded-tl-lg">Title</th>
                    <th class="px-3 py-1 rounded-tl-lg">all</th>
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
                            data-title="<?php echo htmlspecialchars($row['callNumber']); ?>"
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
                            <td class="px-4 py-2 title"><?php echo htmlspecialchars($row['callNumber']); ?></td>
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
            <h3 class="text-lg font-semibold mb-3">Top 3 Most Borrowed Books</h3>
            <ul>
                <?php
                // Query to fetch the top 3 most borrowed books
                $topBorrowedQuery = "
            SELECT 
                book.B_title, 
                COUNT(borrow_book.book_copy) AS borrow_count
            FROM 
                book
            INNER JOIN 
                book_copies ON book.book_id = book_copies.book_id
            INNER JOIN 
                borrow_book ON book_copies.book_copy = borrow_book.book_copy
            GROUP BY 
                book.B_title
            ORDER BY 
                borrow_count DESC
            LIMIT 3;
        ";

                $topBorrowedResult = $conn->query($topBorrowedQuery);

                if ($topBorrowedResult && $topBorrowedResult->num_rows > 0):
                    while ($topBook = $topBorrowedResult->fetch_assoc()):
                ?>
                        <li class="mb-2">
                            <span class="font-medium"><?php echo htmlspecialchars($topBook['B_title']); ?></span> -
                            <span class="text-gray-600"><?php echo $topBook['borrow_count']; ?> times borrowed</span>
                        </li>
                    <?php
                    endwhile;
                else:
                    ?>
                    <li class="text-gray-600">No data available.</li>
                <?php endif; ?>
            </ul>
        </div>

    </div>

    <div class="flex justify-center items-center space-x-2 my-3 flex-col md:flex-row md:space-x-4">
        <button id="prevBtn" onclick="prevPage()" class="btn-pagination px-4 py-1 bg-gray-800 text-white rounded-lg cursor-pointer disabled:bg-gray-400 disabled:cursor-not-allowed transition duration-300 hover:bg-gray-600" disabled>Previous</button>
        <span id="pageInfo" class="text-sm text-gray-600 font-medium">Page 1 of X</span> <button id="nextBtn" onclick="nextPage()" class="btn-pagination px-4 py-1 bg-gray-800 text-white rounded-lg cursor-pointer transition duration-300 hover:bg-gray-600">Next</button>
    </div>
</div>


<div id="materialTypeModal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div style="color: <?= $text2 ?>; background: <?= $background ?>;" class="modal-content relative p-3 rounded-lg w-full max-w-4xl bg-white shadow-lg">
        <div class="absolute top-2 right-2">
            <a href="javascript:void(0);" id="modalCloseLink" style="color: <?= $text1 ?>" class="hover:underline font-semibold">Close</a>
        </div>
        <form id="materialType">
            <h2 class="text-2xl font-bold text-center" style="color: <?= $text2 ?>;">Filters</h2>
            <div>
                <h3 class="text-xl font-semibold mb-3" style="color: <?= $text2 ?>;">Select Material Types</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2" style="color: <?= $text2 ?>;">
                            <input type="radio" name="materialType" value="Book" class="h-4 w-4" />
                            <span class="text-sm font-medium" style="color: <?= $text2 ?>;">Book</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2" style="color: <?= $text2 ?>;">
                            <input type="radio" name="materialType" value="Computer File" class="h-4 w-4" />
                            <span class="text-sm font-medium" style="color: <?= $text2 ?>;">Computer File</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2" style="color: <?= $text2 ?>;">
                            <input type="radio" name="materialType" value="Electronic Book" class="h-4 w-4" />
                            <span class="text-sm font-medium" style="color: <?= $text2 ?>;">Electronic Book (E-Book)</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2" style="color: <?= $text2 ?>;">
                            <input type="radio" name="materialType" value="Equipment" class="h-4 w-4" />
                            <span class="text-sm font-medium" style="color: <?= $text2 ?>;">Equipment</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2" style="color: <?= $text2 ?>;">
                            <input type="radio" name="materialType" value="Kit" class="h-4 w-4" />
                            <span class="text-sm font-medium" style="color: <?= $text2 ?>;">Kit</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2" style="color: <?= $text2 ?>;">
                            <input type="radio" name="materialType" value="Manuscript Language Material" class="h-4 w-4" />
                            <span class="text-sm font-medium" style="color: <?= $text2 ?>;">Manuscript Language Material</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2" style="color: <?= $text2 ?>;">
                            <input type="radio" name="materialType" value="Map" class="h-4 w-4" />
                            <span class="text-sm font-medium" style="color: <?= $text2 ?>;">Map</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2" style="color: <?= $text2 ?>;">
                            <input type="radio" name="materialType" value="Mixed Material" class="h-4 w-4" />
                            <span class="text-sm font-medium" style="color: <?= $text2 ?>;">Mixed Material</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2" style="color: <?= $text2 ?>;">
                            <input type="radio" name="materialType" value="Music" class="h-4 w-4" />
                            <span class="text-sm font-medium" style="color: <?= $text2 ?>;">Music (Printed)</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2" style="color: <?= $text2 ?>;">
                            <input type="radio" name="materialType" value="Picture" class="h-4 w-4" />
                            <span class="text-sm font-medium" style="color: <?= $text2 ?>;">Picture</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2" style="color: <?= $text2 ?>;">
                            <input type="radio" name="materialType" value="Serial" class="h-4 w-4" />
                            <span class="text-sm font-medium" style="color: <?= $text2 ?>;">Serial</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2" style="color: <?= $text2 ?>;">
                            <input type="radio" name="materialType" value="Musical Sound Recording" class="h-4 w-4" />
                            <span class="text-sm font-medium" style="color: <?= $text2 ?>;">Musical Sound Recording</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2" style="color: <?= $text2 ?>;">
                            <input type="radio" name="materialType" value="NonMusical Sound Recording" class="h-4 w-4" />
                            <span class="text-sm font-medium" style="color: <?= $text2 ?>;">Non-Musical Sound Recording</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2" style="color: <?= $text2 ?>;">
                            <input type="radio" name="materialType" value="Video" class="h-4 w-4" />
                            <span class="text-sm font-medium" style="color: <?= $text2 ?>;">Video</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2" style="color: <?= $text2 ?>;">
                            <input type="radio" name="materialType" value="Journal" class="h-4 w-4" />
                            <span class="text-sm font-medium" style="color: <?= $text2 ?>;">Journal</span>
                        </label>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-xl font-semibold mb-3" style="color: <?= $text2 ?>;">Select SubType</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2" style="color: <?= $text2 ?>;">
                            <input type="radio" name="subType" value="Not Assigned" class="h-4 w-4" />
                            <span class="text-sm font-medium" style="color: <?= $text2 ?>;">Not Assigned</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2" style="color: <?= $text2 ?>;">
                            <input type="radio" name="subType" value="Braille" class="h-4 w-4" />
                            <span class="text-sm font-medium" style="color: <?= $text2 ?>;">Braille</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2" style="color: <?= $text2 ?>;">
                            <input type="radio" name="subType" value="Hardcover" class="h-4 w-4" />
                            <span class="text-sm font-medium" style="color: <?= $text2 ?>;">Hardcover</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2" style="color: <?= $text2 ?>;">
                            <input type="radio" name="subType" value="LargePrint" class="h-4 w-4" />
                            <span class="text-sm font-medium" style="color: <?= $text2 ?>;">Large Print</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2" style="color: <?= $text2 ?>;">
                            <input type="radio" name="subType" value="Paperback" class="h-4 w-4" />
                            <span class="text-sm font-medium" style="color: <?= $text2 ?>;">Paperback</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2" style="color: <?= $text2 ?>;">
                            <input type="radio" name="subType" value="Picture" class="h-4 w-4" />
                            <span class="text-sm font-medium" style="color: <?= $text2 ?>;">Picture</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2" style="color: <?= $text2 ?>;">
                            <input type="radio" name="subType" value="Dictionary" class="h-4 w-4" />
                            <span class="text-sm font-medium" style="color: <?= $text2 ?>;">Dictionary</span>
                        </label>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-xl font-semibold mb-3" style="color: <?= $text2 ?>;">Filter by DDC Main Class</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-2 lg:grid-cols-3 gap-2">
                    <div class="flex items-center">
                        <input type="radio" id="ddcAll" name="ddcRange" value="" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="ddcAll" class="ml-2 text-sm font-medium" style="color: <?= $text2 ?>;">All DDC Classes</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="ddc000" name="ddcRange" value="000-099" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="ddc000" class="ml-2 text-sm font-medium" style="color: <?= $text2 ?>;">General Works & Computer Science (000-099)</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="ddc100" name="ddcRange" value="100-199" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="ddc100" class="ml-2 text-sm font-medium" style="color: <?= $text2 ?>;">Philosophy & Psychology (100-199)</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="ddc200" name="ddcRange" value="200-299" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="ddc200" class="ml-2 text-sm font-medium" style="color: <?= $text2 ?>;">Religion (200-299)</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="ddc300" name="ddcRange" value="300-399" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="ddc300" class="ml-2 text-sm font-medium" style="color: <?= $text2 ?>;">Social Sciences (300-399)</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="ddc400" name="ddcRange" value="400-499" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="ddc400" class="ml-2 text-sm font-medium" style="color: <?= $text2 ?>;">Language (400-499)</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="ddc500" name="ddcRange" value="500-599" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="ddc500" class="ml-2 text-sm font-medium" style="color: <?= $text2 ?>;">Pure Sciences (500-599)</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="ddc600" name="ddcRange" value="600-699" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="ddc600" class="ml-2 text-sm font-medium" style="color: <?= $text2 ?>;">Technology (Applied Sciences) (600-699)</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="ddc700" name="ddcRange" value="700-799" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="ddc700" class="ml-2 text-sm font-medium" style="color: <?= $text2 ?>;">The Arts (700-799)</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="ddc800" name="ddcRange" value="800-899" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="ddc800" class="ml-2 text-sm font-medium" style="color: <?= $text2 ?>;">Literature & Rhetoric (800-899)</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="ddcOther" name="ddcRange" value="?" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="ddcOther" class="ml-2 text-sm font-medium" style="color: <?= $text2 ?>;">Other (?)</label>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 mt-3 justify-center">
                <button type="button" id="filterApply" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Apply Filter</button>
                <button type="button" id="filterClear" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Clear Filters</button>
            </div>
        </form>
    </div>
</div>

<script>
        $(document).ready(function() {
            let currentPage = 1;
            const rowsPerPage = 10;
            let filteredRows = [];
            let allRows = [];
    
            function captureAllRows() {
                allRows = [];
                $('#bookTableBody tr').each(function() {
                    allRows.push($(this));
                });
                filteredRows = allRows;
            }
    
            captureAllRows();
            displayTablePage(currentPage);
    
            $('#searchInput').on('keyup', filterTable);
            $('#searchType').on('change', filterTable);
    
            function filterTable() {
                const searchType = $('#searchType').val();
                const searchText = $('#searchInput').val().trim().toLowerCase();
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
                        case 'title':
                            match = rowTitle.includes(searchText);
                            break;
                        case 'author':
                            match = rowAuthor.includes(searchText);
                            break;
                        case 'coauthor':
                            match = rowCoauthor.includes(searchText);
                            break;
                        case 'lccn':
                            match = rowLCCN.includes(searchText);
                            break;
                        case 'isbn':
                            match = rowISBN.includes(searchText);
                            break;
                        case 'issn':
                            match = rowISSN.includes(searchText);
                            break;
                        case 'MT':
                            match = rowMT.includes(searchText);
                            break;
                        case 'ST':
                            match = rowST.includes(searchText);
                            break;
                        case 'extent':
                            match = rowExtent.includes(searchText);
                            break;
                    }
    
                    const typeMatch = !selectedType || rowMT === selectedType.toLowerCase();
                    const subTypeMatch = !selectedSubType || rowST === selectedSubType.toLowerCase();
    
                    return match && typeMatch && subTypeMatch;
                });
    
                currentPage = 1;
                displayTablePage(currentPage);
            }
    
            function displayTablePage(pageNumber) {
                const startIndex = (pageNumber - 1) * rowsPerPage;
                const endIndex = startIndex + rowsPerPage;
                const rowsToDisplay = filteredRows.slice(startIndex, endIndex);
    
                $('#bookTableBody').empty().append(rowsToDisplay);
    
                const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
    
                if (filteredRows.length === 0) {
                    $('#pageInfo').text('Page 1 of 0');
                    $('#prevBtn, #nextBtn').prop('disabled', true);
                } else {
                    $('#pageInfo').text(`Page ${pageNumber} of ${totalPages}`);
                    $('#prevBtn').prop('disabled', pageNumber <= 1);
                    $('#nextBtn').prop('disabled', pageNumber >= totalPages);
                }
            }
    
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
    
            $('#nextBtn').on('click', nextPage);
            $('#prevBtn').on('click', prevPage);
            filterTable();
    
            // Material Type Modal
            $('#filterApply').on('click', function() {
                filterTable();
                $('#materialTypeModal').hide();
            });
    
            $('#filterClear').on('click', function() {
                $('input[name="materialType"]').prop('checked', false);
                $('input[name="subType"]').prop('checked', false);
                $('#searchInput').val('');
                $('#searchType').val('all');
                filteredRows = allRows;
                currentPage = 1;
                displayTablePage(currentPage);
            });
    
            $('#filterLink').on('click', function() {
                $('#materialTypeModal').show();
            });
    
            $('#modalCloseLink').on('click', function() {
                $('#materialTypeModal').hide();
            });
    
            $(window).on('click', function(event) {
                if ($(event.target).is('#materialTypeModal')) {
                    $('#materialTypeModal').hide();
                }
            });
    
            // --- Call Number Filter Modal ---
            $('#callNumberFilterLink').on('click', function() {
                $('#callNumberFilterModal').show();
            });
    
            // Close call number modal on outside click
            $(document).on('click', function(event) {
                const modal = $('#callNumberFilterModal');
                if (modal.is(':visible') && !$(event.target).closest('.inline-block').length && !$(event.target).is('#callNumberFilterLink')) {
                    modal.hide();
                }
            });
        });
</script>

<style>
    #materialTypeModal .modal-content {
        margin: auto;
        /* Center horizontally */
        top: 50%;
        /* Center vertically */
        transform: translateY(-50%);
        /* Adjust for vertical centering */
    }
</style>