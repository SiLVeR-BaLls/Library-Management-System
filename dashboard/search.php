<div class="flex">
    <div class="sidebar">
    <?php if (!empty($userType) && isset($sidebars[$userType]) && file_exists($sidebars[$userType])) {
    include $sidebars[$userType]; 
}?>

    </div>
    <div class="flex flex-col w-screen">
        <?php include 'include/header.php'; ?>
        <div class="flex flex-row">
            <div class="p-6 w-4/5">
                <form id="searchForm" style="background: <?= $background ?>;" class="p-6 rounded-xl shadow-lg space-y-6 w-full">
                    <div class="flex w-full items-center">
                        <input type="text" placeholder="Enter search term..." class="w-full py-2 px-4 rounded-lg" id="searchInput" />
                        <button type="button" id="searchButton" class="ml-4 px-6 search-btn block px-4 py-2 rounded-lg text-center cursor-pointer transition duration-300 py-2 rounded-lg font-semibold">Search</button>
                        <button type="button" id="resetButton" class="ml-4 px-6 bg-gray-500 text-white rounded-lg px-4 py-2 text-center cursor-pointer transition duration-300 hover:bg-gray-600">Reset</button>
                    </div>

                    <div class="space-y-2 w-full">
                        <p class="text-sm font-semibold" style="color: <?= $text1 ?>;">Search By:</p>
                        <div id="searchButtons" class="flex w-full gap-3">
                            <label class="search-label flex-1 cursor-pointer">
                                <input type="radio" name="searchByOption" value="title" class="hidden" id="titleButton">
                                <span class="search-btn block px-4 py-2 rounded-lg text-center cursor-pointer transition duration-300">Title</span>
                            </label>
                            <label class="search-label flex-1 cursor-pointer">
                                <input type="radio" name="searchByOption" value="all" class="hidden" id="allButton">
                                <span class="search-btn block bg-blue-600 text-white px-4 py-2 rounded-lg text-center cursor-pointer transition duration-300">All</span>
                            </label>
                            <label class="search-label flex-1 cursor-pointer">
                                <input type="radio" name="searchByOption" value="author" class="hidden" id="authorButton">
                                <span class="search-btn block px-4 py-2 rounded-lg text-center cursor-pointer transition duration-300">Author</span>
                            </label>
                            <label class="search-label flex-1 cursor-pointer">
                                <input type="radio" name="searchByOption" value="coauthor" class="hidden" id="coauthorButton">
                                <span class="search-btn block px-4 py-2 rounded-lg text-center cursor-pointer transition duration-300">Co-Author</span>
                            </label>
                        </div>
                    </div>

                    <p class="text-sm font-semibold mt-6" style="color: <?= $text1 ?>;">Select Category:</p>
                    <div class="flex space-x-6">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold mb-3" style="color: <?= $text ?>">Select Material Type</h3>
                            <select id="materialType" class="w-full p-2 border rounded">
                                <option value="all">All</option>
                                <option value="Book">Book</option>
                                <option value="Kit">Kit</option>
                                <option value="Journal">Journal</option>
                                <option value="Thesis">Thesis</option>
                                <option value="Music Printed">Music Printed</option>
                                <option value="Non-Musical Sound Recording">Non-Musical Sound Recording</option>
                                <option value="Computer File">Computer File</option>
                                <option value="Manuscript Language Material">Manuscript Language Material</option>
                                <option value="Map">Map</option>
                                <option value="Series">Series</option>
                                <option value="Equipment">Equipment</option>
                                <option value="Mixed Materials">Mixed Materials</option>
                                <option value="Musical Sound Recordings">Musical Sound Recordings</option>
                                <option value="Article">Article</option>
                                <option value="Magazine">Magazine</option>
                                <option value="Newspaper">Newspaper</option>
                            </select>
                        </div>

                        <div class="flex-1">
                            <h3 class="text-xl font-semibold mb-3" style="color: <?= $text ?>">Select Subtype</h3>
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
                            <h3 class="text-xl font-semibold mb-3" style="color: <?= $text ?>">Select DDC Main Class</h3>
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
                </form>
            </div>

            <div class="top-borrowed-books m-6 p-3 bg-gray-100 rounded-lg shadow-md w-1/5">
                <h3 class="text-lg font-semibold mb-3">Top 3 Most Borrowed Books</h3>
                <ul>
                    <?php
                    // Query to fetch the top 3 most borrowed books WITH book_id
                    $topBorrowedQuery = "
                                    SELECT
                                        book.book_id,
                                        book.B_title,
                                        COUNT(borrow_book.book_copy) AS borrow_count
                                    FROM
                                        book
                                    INNER JOIN
                                        book_copies ON book.book_id = book_copies.book_id
                                    INNER JOIN
                                        borrow_book ON book_copies.book_copy = borrow_book.book_copy
                                    GROUP BY
                                        book.book_id, book.B_title
                                    ORDER BY
                                        borrow_count DESC
                                    LIMIT 3;
                                ";

                    $topBorrowedResult = $conn->query($topBorrowedQuery);

                    if ($topBorrowedResult && $topBorrowedResult->num_rows > 0):
                        while ($topBook = $topBorrowedResult->fetch_assoc()):
                    ?>
                            <li class="mb-2">
                                <a href="ViewBook.php?title=<?php echo htmlspecialchars($topBook['book_id']); ?>" class="hover:underline">
                                    <span class="font-medium"><?php echo htmlspecialchars($topBook['B_title']); ?></span>
                                </a> -
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
        <footer class="mt-auto">
            <?php include 'include/footer.php'; ?>
        </footer>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Load saved filters from localStorage
        const savedFilters = JSON.parse(localStorage.getItem('searchFilters')) || {};
        document.getElementById('searchInput').value = savedFilters.searchTerm || '';
        document.getElementById('materialType').value = savedFilters.materialType || 'all';
        document.getElementById('subType').value = savedFilters.subType || 'all';
        document.getElementById('ddcMainClass').value = savedFilters.ddcMainClass || 'all';

        const searchByOption = savedFilters.searchByOption || 'all';
        document.querySelector(`input[name="searchByOption"][value="${searchByOption}"]`).checked = true;

        // Save filters to localStorage and redirect to search_results.php
        document.getElementById('searchButton').addEventListener('click', function () {
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
        document.getElementById('resetButton').addEventListener('click', function () {
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
</script>

<style>
    .search-btn {
        background-color: <?= $button ?>;
        color: white;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s, color 0.3s;
    }

    .search-btn:hover {
        background-color: <?= $button_hover ?>;
        color: white;
    }

    .search-btn.active-btn {
        background-color: <?= $button_active ?>;
        color: white;
    }
</style>