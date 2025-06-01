<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Include the database connection
    include 'config.php';

    // Determine User Type
    $userTypes = ['admin', 'student', 'librarian', 'faculty'];
    $userType = null;
    $idno = null;

    foreach ($userTypes as $type) {
        if (!empty($_SESSION[$type]['IDno'])) {
            $userType = $type;
            $idno = $_SESSION[$type]['IDno'];
            break;
        }
    }

    // Database query for top borrowed books
    $topBorrowedQuery = "
        SELECT
            book.book_id,
            book.photo,
            book.B_title,
            COUNT(borrow_book.book_copy) AS borrow_count
        FROM
            book
        INNER JOIN
            book_copies ON book.book_id = book_copies.book_id
        INNER JOIN
            borrow_book ON book_copies.book_copy = borrow_book.book_copy
        GROUP BY
            book.book_id, book.B_title, book.photo
        ORDER BY
            borrow_count DESC
        LIMIT 10;
    ";

    $topBorrowedResult = $conn->query($topBorrowedQuery);
    $topBorrowedBooks = [];
    if ($topBorrowedResult && $topBorrowedResult->num_rows > 0) {
        while ($row = $topBorrowedResult->fetch_assoc()) {
            $topBorrowedBooks[] = $row;
        }
    }

    // Function to generate image tag
    function generateBookImage($photo) {
        if (!empty($photo) && file_exists('../../pic/Book/' . $photo)) {
            return '<img src="../pic/Book/' . htmlspecialchars($photo) . '" alt="Book Cover" class="book-cover-image">';
        } else {
            return '<img src="../pic/default/book.jpg" alt="Default Book Cover" class="book-cover-image">';
        }
    }
?>


<div class="flex flex-1">
    <div class="sidebar">
        <?php
        // Only include sidebar if a valid user type and the file exists
        if (!empty($userType) && isset($sidebars[$userType]) && file_exists($sidebars[$userType])) {
            include $sidebars[$userType];
        }
        ?>
    </div>
    <div class="flex flex-col flex-1">
        <?php include 'include/header.php'; ?>

        <div class="flex flex-col flex-1">
            <div class="p-6 flex-1 flex flex-col">
                <div id="searchForm" class="flex flex-col space-y-4">

                    <div class="flex flex-row space-x-4">

                        <div class="flex flex-col w-1/5">
                            <div class="flex-1">
                                <select id="materialType" class="w-full p-2 border rounded">
                                    <option value="all">All</option>
                                    <option selected value="">Select Material Type</option>
                                    <option value="Book">Book</option>
                                    <option value="Computer File">Computer File</option>
                                    <option value="Electronic Book">Electronic Book (E-Book)</option>
                                    <option value="Map">Map</option>
                                    <option value="Picture">Picture</option>
                                    <option value="Serial">Serial</option>
                                    <option value="Video">Video</option>
                                    <option value="Journal">Journal</option>
                                </select>
                            </div>

                            <div class="flex-1">
                                <select id="subType" class="w-full p-2 border rounded">
                                    <option value="all">All</option>
                                    <option value="Not Assigned">Not Assigned</option>
                                    <option value="Hardcover">Hardcover</option>
                                    <option value="Microform">Microform</option>
                                    <option value="Online">Online</option>
                                    <option value="Paperback">Paperback</option>
                                    <option value="Braille">Braille</option>
                                    <option value="Dictionary">Dictionary</option>
                                    <option value="Picture">Picture</option>
                                    <option value="Video">Video</option>
                                    <option value="Ebook">Ebook</option>
                                </select>
                            </div>

                            <div class="flex-1">
                                <select id="ddcMainClass" class="w-full p-2 border rounded">
                                    <option value="all">Display All Books</option>
                                    <option value="other">Books Other</option>
                                    <option value="000-099">General Works & Computer Science</option>
                                    <option value="100-199">Philosophy & Psychology</option>
                                    <option value="200-299">Religion</option>
                                    <option value="300-399">Social Sciences</option>
                                    <option value="400-499">Language</option>
                                    <option value="500-599">Pure Sciences</option>
                                    <option value="600-699">Technology (Applied Sciences)</option>
                                    <option value="700-799">The Arts</option>
                                    <option value="800-899">Literature & Rhetoric</option>
                                    <option value="900-999">History & Geography</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col w-4/5">
                            <div class="flex w-full items-center">
                                <input type="text" placeholder="Enter search term..." class="w-full py-2 px-4 rounded-lg" id="searchInput" />
                                <button type="button" id="searchButton" class="ml-4 px-6 search-btn block px-4 py-2 rounded-lg text-center cursor-pointer transition duration-300 py-2 rounded-lg font-semibold">Search</button>
                                <button type="button" id="resetButton" class="ml-4 px-6 bg-gray-500 text-white rounded-lg px-4 py-2 text-center cursor-pointer transition duration-300 hover:bg-gray-600">Reset</button>
                            </div>

                            <div class="space-y-2 w-full W-1/6 py-3">
                                <div id="searchButtons" class="flex gap-3">
                                    <div class="flex w-full gap-3 flex-col">

                                        <label class="search-label W-FULL cursor-pointer">
                                            <input type="radio" name="searchByOption" value="title" class="hidden" id="titleButton">
                                            <span class="search-btn block px-4 py-2 rounded-lg text-center cursor-pointer transition duration-300">Title</span>
                                        </label>

                                        <label class="search-label W-FULL cursor-pointer">
                                            <input type="radio" name="searchByOption" value="all" class="hidden" id="allButton">
                                            <span class="search-btn block bg-blue-600 px-4 py-2 rounded-lg text-center cursor-pointer transition duration-300">All</span>
                                        </label>

                                    </div>
                                    <div class="flex w-full gap-3 flex-col">

                                        <label class="search-label W-FULL cursor-pointer">
                                            <input type="radio" name="searchByOption" value="author" class="hidden" id="authorButton">
                                            <span class="search-btn block px-4 py-2 rounded-lg text-center cursor-pointer transition duration-300">Author</span>
                                        </label>

                                        <label class="search-label W-FULL cursor-pointer">
                                            <input type="radio" name="searchByOption" value="coauthor" class="hidden" id="coauthorButton">
                                            <span class="search-btn block px-4 py-2 rounded-lg text-center cursor-pointer transition duration-300">Co-Author</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="top-borrowed-books">
                    <h3 class="text-lg font-semibold mb-3">Top 10 Most Borrowed Books</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-300 rounded-md">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="text-left px-4 py-2 border-b">count</th>
                                    <th class="text-left px-4 py-2 border-b">Photo</th>
                                    <th class="text-left px-4 py-2 border-b">Book Title</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($topBorrowedBooks)): ?>
                                    <?php foreach ($topBorrowedBooks as $topBook): ?>
                                        <tr class="hover:bg-gray-50 cursor-pointer recommended-row"
                                            data-book-id="<?php echo htmlspecialchars($topBook['book_id']); ?>">
                                            <td class="px-4 py-2 border-b"><?php echo $topBook['borrow_count']; ?></td>
                                            <td class="px-4 py-2 border-b text-gray-900">
                                                <div class="image-container">
                                                    <?php echo generateBookImage($topBook['photo']); ?>
                                                    <div class="image-overlay"></div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-2 border-b text-gray-900"><?php echo htmlspecialchars($topBook['B_title']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 text-center text-gray-600">No data available.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <footer class="mt-auto">
            <?php include 'include/footer.php'; ?>
        </footer>
    </div>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load saved filters from localStorage
            const savedFilters = JSON.parse(localStorage.getItem('searchFilters')) || {};
            document.getElementById('searchInput').value = savedFilters.searchTerm || '';
            document.getElementById('materialType').value = savedFilters.materialType || 'all';
            document.getElementById('subType').value = savedFilters.subType || 'all';
            document.getElementById('ddcMainClass').value = savedFilters.ddcMainClass || 'all';

            const searchByOption = savedFilters.searchByOption || 'all';
            document.querySelector(`input[name="searchByOption"][value="${searchByOption}"]`).checked = true;

            // Save filters to localStorage and redirect to search_results.php
            document.getElementById('searchButton').addEventListener('click', function() {
                const filters = {
                    searchTerm: document.getElementById('searchInput').value,
                    searchByOption: document.querySelector('input[name="searchByOption"]:checked').value,
                    materialType: document.getElementById('materialType').value,
                    subType: document.getElementById('subType').value,
                    ddcMainClass: document.getElementById('ddcMainClass').value,
                };
                localStorage.setItem('searchFilters', JSON.stringify(filters));
                window.location.href = 'search_results.php'; // Redirect without query parameters
            });

            // Reset filters and clear localStorage
            document.getElementById('resetButton').addEventListener('click', function() {
                localStorage.removeItem('searchFilters');
                document.getElementById('searchInput').value = '';
                document.getElementById('materialType').value = 'all';
                document.getElementById('subType').value = 'all';
                document.getElementById('ddcMainClass').value = 'all';
                document.querySelector('input[name="searchByOption"][value="all"]').checked = true;
            });
        });

        const searchLabels = document.querySelectorAll('#searchButtons .search-label');

        searchLabels.forEach(label => {
            label.addEventListener('click', () => {
                searchLabels.forEach(lbl => lbl.querySelector('span').classList.remove('active-btn'));
                label.querySelector('span').classList.add('active-btn');
                const radio = label.querySelector('input[type="radio"]');
                radio.checked = true;
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const selectedOption = document.querySelector('input[name="searchByOption"]:checked');
            if (selectedOption) {
                const label = selectedOption.closest('.search-label');
                if (label && label.querySelector('span')) {
                    label.querySelector('span').classList.add('active-btn');
                }
            }
        });

        // Add click event for recommended (top borrowed) book rows like in search_results.php
        const isLoggedIn = <?= json_encode(!empty($idno)); ?>;
        document.querySelectorAll('.recommended-row').forEach(row => {
            row.addEventListener('click', function() {
                const bookId = this.getAttribute('data-book-id');
                if (!isLoggedIn) {
                    alert('Log in first');
                } else {
                    window.location.href = 'ViewBook.php?title=' + encodeURIComponent(bookId);
                }
            });
        });
    </script>

    <style>
        .search-btn {
            background-color: <?= $button ?>;
            color: <?= $text1 ?>;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
        }

        .search-btn:hover {
            background-color: <?= $button_hover ?>;
        }

        .search-btn.active-btn {
            background-color: <?= $button_active ?>;
        }

        .book-cover-image {
            width: 60px;
            height: 80px;
        }
        .image-container {
            width: 4rem;
            height: 3rem;
            overflow: hidden; /* Clip any part of the overlay that goes beyond */
        }


    </style>
