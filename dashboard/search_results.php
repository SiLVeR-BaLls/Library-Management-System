<?php
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

    // Define the main DDC ranges for the dropdown
    $mainDDCClasses = [
        'all' => 'Display All Books',
        'other' => 'Books Other',
        '000-099' => 'General Works & Computer Science',
        '100-199' => 'Philosophy & Psychology',
        '200-299' => 'Religion',
        '300-399' => 'Social Sciences',
        '400-499' => 'Language',
        '500-599' => 'Pure Sciences',
        '600-699' => 'Technology (Applied Sciences)',
        '700-799' => 'The Arts',
        '800-899' => 'Literature & Rhetoric',
        '900-999' => 'History & Geography'
    ];
?>

<div class="flex">
    <div class="sidebar">
        <?php if (!empty($userType) && isset($sidebars[$userType]) && file_exists($sidebars[$userType])) {
            include $sidebars[$userType]; }
        ?>
</div>

    <div class="flex flex-col w-screen">
        <?php include 'include/header.php'; ?>

        <div class="p-6 flex flex-col">
            <div class="flex flex-row justify-between">
                <div class="mb-6 w-13 grid grid-cols-1 md:grid-cols-2 gap-2">
                    <button onclick="window.location.href='index.php';" class="py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                        &larr; Return
                    </button>
                    <p><strong>Search Result:</strong> <span id="totalResults">0</span></p>
                    <p><strong>Keyword:</strong> <span id="filterKeyword">N/A</span></p>
                    <p><strong>Search Type:</strong> <span id="filterSearchType">All</span></p>
                    <p><strong>Material Type:</strong> <span id="filterMaterialType">All</span></p>
                    <p><strong>Subtype:</strong> <span id="filterSubType">All</span></p>
                </div>
                <p><strong>DDC Main Class:</strong> <span id="filterDDCMainClass">All</span></p>

                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex flex-col md:flex-row gap-4">
                        <input type="text" id="searchInput" placeholder="Search..." 
                            class="form-input block w-full md:w-64 px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-gray-700 text-sm">
                        <select id="searchType"
                            class="form-select block px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-gray-700 text-sm">
                            <option value="all">All</option>
                            <option value="title">Title</option>
                            <option value="author">Author</option>
                            <option value="coauthor">Co-Author</option>
                           
                        </select>
                        <button id="clearSearch" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">Clear</button>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto rounded-lg shadow-md">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-800 text-white">
                            <th class="p-4 text-left">Photo</th>
                            <th class="p-4 text-left">Title</th>
                            <th class="p-4 text-left">Author</th>
                            <th class="p-4 text-center">Material Type</th>
                            <th class="p-4 text-center">Subtype</th>
                            <th class="p-4 text-center">Copies</th>
                        </tr>
                    </thead>
                    <tbody id="bookTableBody" class="bg-white">
                        <!-- Rows will be dynamically added here -->
                    </tbody>
                </table>

                
            </div>
            <div class="flex justify-center items-center space-x-2 mt-3 flex-col md:flex-row md:space-x-4">
                <button id="prevBtn" onclick="prevPage()" class="btn-pagination px-4 py-1 bg-gray-800 text-white rounded-lg cursor-pointer disabled:bg-gray-400 disabled:cursor-not-allowed transition duration-300 hover:bg-gray-600" disabled>Previous</button>
                <span id="pageInfo" class="text-sm text-gray-600 font-medium">Page 1 of X</span>
                <button id="nextBtn" onclick="nextPage()" class="btn-pagination px-4 py-1 bg-gray-800 text-white rounded-lg cursor-pointer transition duration-300 hover:bg-gray-600">Next</button>
            </div>
          
        </div>

        <div class="mt-auto">
            <?php include 'include/footer.php'; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rowsPerPage = 10;
        let currentPage = 1;
        let filteredBooks = [];
        let allBooks = []; // Store the original list of books

        // Map DDC ranges to their names
        const ddcMainClassNames = {
            '000-099': 'General Works & Computer Science',
            '100-199': 'Philosophy & Psychology',
            '200-299': 'Religion',
            '300-399': 'Social Sciences',
            '400-499': 'Language',
            '500-599': 'Pure Sciences',
            '600-699': 'Technology (Applied Sciences)',
            '700-799': 'The Arts',
            '800-899': 'Literature & Rhetoric',
            '900-999': 'History & Geography',
            'other': 'Books Other',
            'all': 'Display All Books'
        };

        // Load filters from localStorage and display them
        function loadFilters() {
            const filters = JSON.parse(localStorage.getItem('searchFilters')) || {};
            document.getElementById('filterKeyword').textContent = filters.searchTerm || 'N/A';
            document.getElementById('filterSearchType').textContent = filters.searchByOption || 'All';
            document.getElementById('filterMaterialType').textContent = filters.materialType || 'All';
            document.getElementById('filterSubType').textContent = filters.subType || 'All';

            // Map DDC number to name and display it
            const ddcName = ddcMainClassNames[filters.ddcMainClass] || 'All';
            document.getElementById('filterDDCMainClass').textContent = ddcName;
        }

        // Fetch books based on filters from localStorage
        function fetchBooks() {
            const filters = JSON.parse(localStorage.getItem('searchFilters')) || {};
            fetch('fetch_books.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(filters)
            })
            .then(response => response.json())
            .then(data => {
                allBooks = data; // Store the original list of books
                filteredBooks = [...allBooks]; // Initialize filteredBooks with allBooks
                displayTablePage(currentPage);
            });
        }

        function displayTablePage(pageNumber) {
            const startIndex = (pageNumber - 1) * rowsPerPage;
            const endIndex = startIndex + rowsPerPage;
            const rowsToDisplay = filteredBooks.slice(startIndex, endIndex);

            const tableBody = document.getElementById('bookTableBody');
            tableBody.innerHTML = '';

            rowsToDisplay.forEach(book => {
                const row = document.createElement('tr');
                row.className = 'border-b border-gray-200 hover:bg-gray-100 cursor-pointer';
                
                row.addEventListener('click', createRowClickHandler(book.book_id));

              row.innerHTML = `
    <td class="p-4">
        ${book.photo ? 
            `<img src="../pic/Book/${book.photo}" alt="Book Cover" class="w-16 h-auto">` : 
            `<img src="../pic/default/book.jpg" alt="Default Book Cover" class="w-16 h-auto">`
        }
    </td>
    <td class="p-4">${book.title}</td>
    <td class="p-4">${book.author || 'N/A'}</td>
    <td class="p-4 text-center">${book.MT || 'N/A'}</td>
    <td class="p-4 text-center">${book.ST || 'N/A'}</td>
    <td class="p-4 text-center">
        ${book.available_copies > 0 ?
            '<div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center">✔</div>' :
            '<div class="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center">✖</div>'}
    </td>
        `;
                tableBody.appendChild(row);
            });

            const totalPages = Math.max(1, Math.ceil(filteredBooks.length / rowsPerPage));
            document.getElementById('pageInfo').textContent = `Page ${pageNumber} of ${totalPages}`;
            document.getElementById('prevBtn').disabled = pageNumber <= 1;
            document.getElementById('nextBtn').disabled = pageNumber >= totalPages;

            // Update total results count
            document.getElementById('totalResults').textContent = filteredBooks.length;
        }

        function nextPage() {
            const totalPages = Math.max(1, Math.ceil(filteredBooks.length / rowsPerPage));
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

        // Ensure buttons are clickable by adding event listeners
        document.getElementById('nextBtn').addEventListener('click', nextPage);
        document.getElementById('prevBtn').addEventListener('click', prevPage);

        // Filter books dynamically based on search input
        function filterBooks() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const searchType = document.getElementById('searchType').value;

            filteredBooks = allBooks.filter(book => {
                switch (searchType) {
                    case 'title':
                        return book.title.toLowerCase().includes(searchTerm);
                    case 'author':
                        return book.author && book.author.toLowerCase().includes(searchTerm);
                    case 'coauthor':
                        return book.coauthor && book.coauthor.toLowerCase().includes(searchTerm);
                    case 'MT':
                        return book.MT && book.MT.toLowerCase().includes(searchTerm);
                    case 'ST':
                        return book.ST && book.ST.toLowerCase().includes(searchTerm);
                    default:
                        return book.title.toLowerCase().includes(searchTerm) ||
                               (book.author && book.author.toLowerCase().includes(searchTerm)) ||
                               (book.coauthor && book.coauthor.toLowerCase().includes(searchTerm)) ||
                               (book.MT && book.MT.toLowerCase().includes(searchTerm)) ||
                               (book.ST && book.ST.toLowerCase().includes(searchTerm));
                }
            });

            currentPage = 1; // Reset to the first page
            displayTablePage(currentPage);
        }

        document.getElementById('searchInput').addEventListener('input', filterBooks);
        document.getElementById('searchType').addEventListener('change', filterBooks);

        document.getElementById('clearSearch').addEventListener('click', function () {
            document.getElementById('searchInput').value = '';
            document.getElementById('searchType').value = 'all';
            filteredBooks = [...allBooks]; // Reset filteredBooks to the original list
            currentPage = 1; // Reset to the first page
            displayTablePage(currentPage);
        });

        loadFilters();
        fetchBooks();
    });
    
</script>

<script>
    // Prevent click if user not logged in
    const isLoggedIn = <?= json_encode($isLoggedIn); ?>;

    function createRowClickHandler(bookId) {
        return function () {
            if (!isLoggedIn) {
                alert("Log in first");
            } else {
                window.location.href = 'ViewBook.php?title=' + encodeURIComponent(bookId);
            }
        };
    }

</script>