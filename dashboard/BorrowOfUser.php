<?php
include 'config.php';

$sql = "SELECT u.IDno, u.Fname, u.Sname, u.U_type, b.B_title
        FROM borrow_book bb
        JOIN book_copies bc ON bb.book_copy = bc.book_copy
        JOIN book b ON bc.book_id = b.book_id
        JOIN users_info u ON bb.IDno = u.IDno
        ORDER BY bb.borrow_id DESC";

$result = $conn->query($sql);
$totalBorrows = $result->num_rows;
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
        
        <div class="w-full h-auto mx-auto">
            <div class="container mx-auto px-4 py-6">
                 

                <div class="mb-4 flex justify-between items-center">
                    <label>
                        Show
                        <select id="entriesPerPage" class="border rounded px-2 py-1 mx-1">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        entries
                    </label>
                    <input type="text" id="searchInput" placeholder="Search..." class="border px-3 py-2 rounded shadow w-1/3" />

                    <p class="font-bold">
                        Total Borrowed Records: <span class="text-blue-600"><?= $totalBorrows ?></span>
                    </p>
                </div>

                <div class="mb-4">
                    <label class="mr-4 font-semibold">Filter by User Type:</label>
                    <label><input type="radio" name="userTypeFilter" value="all" checked class="mr-1">All</label>
                    <label class="ml-4"><input type="radio" name="userTypeFilter" value="staff" class="mr-1">Staff</label>
                    <label class="ml-4"><input type="radio" name="userTypeFilter" value="student" class="mr-1">Student</label>
                    <label class="ml-4"><input type="radio" name="userTypeFilter" value="faculty" class="mr-1">Faculty</label>
                </div>
                

                <?php if ($totalBorrows > 0): ?>
                    <?php
                    $grouped = [];
                    while ($row = $result->fetch_assoc()) {
                        $id = $row["IDno"];
                        if (!isset($grouped[$id])) {
                            $grouped[$id] = [
                                "Fname" => $row["Fname"],
                                "Sname" => $row["Sname"],
                                "U_type" => $row["U_type"],
                                "books" => []
                            ];
                        }
                        $grouped[$id]["books"][] = $row["B_title"];
                    }
                    ?>

                    <div class="overflow-x-auto bg-white rounded shadow">
                        <table id="borrowTable" class="w-full border-collapse border border-gray-300 mt-4 text-sm">
                            <thead>
                                <tr class="bg-gray-100 text-center cursor-pointer">
                                    <th class="border border-gray-300 p-2 w-2/5" onclick="sortTable(0)">Book Titles ⬍</th>
                                    <th class="border border-gray-300 p-2" onclick="sortTable(1)">ID Number ⬍</th>
                                    <th class="border border-gray-300 p-2" onclick="sortTable(2)">First Name ⬍</th>
                                    <th class="border border-gray-300 p-2" onclick="sortTable(3)">Last Name ⬍</th>
                                    <th class="border border-gray-300 p-2" onclick="sortTable(4)">User Type ⬍</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($grouped as $id => $user): ?>
                                    <tr class="text-center hover:bg-gray-50">
                                        <td class="border border-gray-300 p-2 text-left"><?= htmlspecialchars(implode(', ', $user["books"])) ?></td>
                                        <td class="border border-gray-300 p-2"><?= htmlspecialchars($id) ?></td>
                                        <td class="border border-gray-300 p-2"><?= htmlspecialchars($user["Fname"]) ?></td>
                                        <td class="border border-gray-300 p-2"><?= htmlspecialchars($user["Sname"]) ?></td>
                                        <td class="border border-gray-300 p-2"><?= htmlspecialchars($user["U_type"]) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center mt-4 space-x-2" id="paginationControls">
                        <button id="prevPage" onclick="changePage(-1)" class="bg-gray-300 px-4 py-2 rounded-l">Previous</button>
                        <span id="pageInfo" class="px-4 py-2 border">Page 1</span>
                        <button id="nextPage" onclick="changePage(1)" class="bg-gray-300 px-4 py-2 rounded-r">Next</button>
                    </div>

                    <script>
                        const rows = Array.from(document.querySelectorAll('#borrowTable tbody tr'));
                        const pageInfo = document.getElementById("pageInfo");
                        const entriesSelector = document.getElementById("entriesPerPage");
                        const searchInput = document.getElementById("searchInput");
                        const userTypeRadios = document.querySelectorAll('input[name="userTypeFilter"]');

                        let currentPage = 1;
                        let entriesPerPage = parseInt(entriesSelector.value);
                        let currentFilter = 'all';
                        let currentSearch = '';

                     function applyFilters() {
    currentSearch = searchInput.value.toLowerCase();
    const filteredRows = rows.filter(row => {
        const userType = row.cells[4].innerText.toLowerCase();
        const matchesType = currentFilter === 'all' ||
                            (currentFilter === 'staff' && (userType === 'admin' || userType === 'librarian')) ||
                            userType === currentFilter;
        const matchesSearch = row.innerText.toLowerCase().includes(currentSearch);
        return matchesType && matchesSearch;
    });

    rows.forEach(row => row.style.display = "none");
    const totalPages = Math.ceil(filteredRows.length / entriesPerPage);
    if (currentPage > totalPages) currentPage = totalPages > 0 ? totalPages : 1;
    const start = (currentPage - 1) * entriesPerPage;
    const end = start + entriesPerPage;
    filteredRows.slice(start, end).forEach(row => row.style.display = "");

    pageInfo.textContent = `Page ${currentPage} of ${totalPages || 1}`;
    document.getElementById("prevPage").disabled = currentPage === 1;
    document.getElementById("nextPage").disabled = currentPage === totalPages || totalPages === 0;
}


                        function changePage(direction) {
                            currentPage += direction;
                            applyFilters();
                        }

                        entriesSelector.addEventListener('change', () => {
                            entriesPerPage = parseInt(entriesSelector.value);
                            currentPage = 1;
                            applyFilters();
                        });

                        searchInput.addEventListener('input', () => {
                            currentPage = 1;
                            applyFilters();
                        });

                        userTypeRadios.forEach(radio => {
                            radio.addEventListener('change', function () {
                                currentFilter = this.value;
                                currentPage = 1;
                                applyFilters();
                            });
                        });

                        let sortDirection = true;
                        function sortTable(index) {
                            const tbody = document.querySelector('#borrowTable tbody');
                            const sorted = rows.sort((a, b) => {
                                const aText = a.cells[index].innerText.toLowerCase();
                                const bText = b.cells[index].innerText.toLowerCase();
                                return sortDirection ? aText.localeCompare(bText) : bText.localeCompare(aText);
                            });
                            sortDirection = !sortDirection;
                            sorted.forEach(row => tbody.appendChild(row));
                            applyFilters();
                        }

                        applyFilters(); // Initial display
                    </script>
                <?php else: ?>
                    <p class="mt-4 text-red-600 font-semibold">No borrowed book records found.</p>
                <?php endif; ?>
            </div>
        </div>
              <footer class="mt-auto">
            <?php include 'include/footer.php'; ?>
        </footer>
    </div>
</div>