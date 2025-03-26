<!-- user_status.php -->
<?php
    include '../config.php';

    // Fetch all users with status_log = 'approved'
    $query = "SELECT IDno, Fname, Sname, U_Type, status_details 
            FROM users_info 
            WHERE status_log = 'approved'";
    $result = mysqli_query($conn, $query);
?>

<title>User Status</title>
<!-- Main Content Area with Sidebar and BrowseBook Section -->
<div class="flex">
    <!-- Sidebar PHP Logic -->
    <div class="sidebar">
        <?php include $sidebars[$userType] ?? ''; ?>
    </div>
    <!-- BrowseBook Content Section -->
    <div class="flex flex-col w-screen">
        <!-- Header at the Top -->
        <?php include 'include/header.php'; ?>

        <!-- BrowseBook php and script -->
        <div class="container px-6  py-3 z-1 mx-auto">
            <h2 class="text-3xl font-semibold mb-2 text-gray-800">User Status</h2>

            <!-- Bulk Action Buttons -->
            <div class=" flex gap-2">
                </div>
                
                <!-- Status Buttons (Now Using Radio Buttons) -->
                <div class="mb-4 flex gap-2 justify-between">
                    <div class=" flex flex-col gap-2 justify-center">
                        <!-- Search Bar -->
                        <div class="flex justify-center items-center w-full md:w-auto">
                            <div class="flex w-full justify-center">
                                <input type="text" id="searchInput" placeholder="Search..."
                                    class="border border-gray-300 rounded px-4 py-2 w-full focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                <select id="searchCategory" class="border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-blue-400">
                                    <option value="all">All</option>
                                    <option value="IDno">IDno</option>
                                    <option value="Fname">First Name</option>
                                    <option value="Sname">Last Name</option>
                                    <option value="course">Course</option>
                                    <option value="college">College</option>
                                    <option value="yrLVL">Year</option>
                                </select>
                            </div>
                        </div>

                       <!-- Status Buttons -->
                        <div class="flex gap-2">
                            <button onclick="updateStatus('Active')" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-all w-32">Set Active</button>
                            <button onclick="updateStatus('Inactive')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition-all w-32">Set Inactive</button>
                            <button onclick="updateStatus('restricted')" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-all w-32">Set Restricted</button>
                        </div>
                    </div>

                    <div class=" flex flex-col gap-2 justify-center">
                        <!-- Status Radio Buttons -->
                        <div class="radio-input flex rounded-lg  border-gray-200 bg-gray-100 overflow-hidden ">
                            <label class="flex-1 text-center px-4 py-2 font-semibold text-black cursor-pointer transition-all hover:bg-blue-300">
                                <input type="radio" name="statusFilter" value="all" id="allRadio" class="hidden" checked>
                                <span>All</span>
                            </label>
                            <label class="flex-1 text-center px-4 py-2 font-semibold text-black cursor-pointer transition-all hover:bg-blue-300">
                                <input type="radio" name="statusFilter" value="active" id="activeRadio" class="hidden">
                                <span>Active</span>
                            </label>
                            <label class="flex-1 text-center px-4 py-2 font-semibold text-black cursor-pointer transition-all hover:bg-blue-300">
                                <input type="radio" name="statusFilter" value="inactive" id="inactiveRadio" class="hidden">
                                <span>Inactive</span>
                            </label>
                            <label class="flex-1 text-center px-4 py-2 font-semibold text-black cursor-pointer transition-all hover:bg-blue-300">
                                <input type="radio" name="statusFilter" value="restricted" id="restrictedRadio" class="hidden">
                                <span>Restricted</span>
                            </label>
                        </div>

                        <!-- User Type Filters -->
                        <div class="flex justify-center items-center w-full md:w-auto ">
                            <div class="radio-input flex rounded-lg  border-gray-200 bg-gray-100 overflow-hidden">
                                <label class="flex-1 text-center px-4 py-2 font-semibold text-black cursor-pointer transition-all hover:bg-blue-300">
                                    <input type="radio" name="userType" value="all" id="allRadio" class="hidden" checked>
                                    <span>All</span>
                                </label>
                                <label class="flex-1 text-center px-4 py-2 font-semibold text-black cursor-pointer transition-all hover:bg-blue-300">
                                    <input type="radio" name="userType" value="admin" id="adminRadio" class="hidden">
                                    <span>Admin</span>
                                </label>
                                <label class="flex-1 text-center px-4 py-2 font-semibold text-black cursor-pointer transition-all hover:bg-blue-300">
                                    <input type="radio" name="userType" value="student" id="studentRadio" class="hidden">
                                    <span>Student</span>
                                </label>
                                <label class="flex-1 text-center px-4 py-2 font-semibold text-black cursor-pointer transition-all hover:bg-blue-300">
                                    <input type="radio" name="userType" value="librarian" id="librarianRadio" class="hidden">
                                    <span>Librarian</span>
                                </label>
                                <label class="flex-1 text-center px-4 py-2 font-semibold text-black cursor-pointer transition-all hover:bg-blue-300">
                                    <input type="radio" name="userType" value="faculty" id="facultyRadio" class="hidden">
                                    <span>Faculty</span>
                                </label>
                            </div>
                        </div>
                    </div>

                </div>


            </div>


            <!-- Table Container -->
            <div class="shadow-md rounded-lg p-4">
                <table id="userTable" class="bg-white min-w-full shadow-md rounded-lg  border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr class="text-gray-700">
                            <th class="py-3 px-4 text-center">
                                <input type="checkbox" id="selectAll" onclick="toggleAll(this)">
                            </th>
                            <th class="py-3 px-4 text-center">ID No</th>
                            <th class="py-3 px-4">First Name</th>
                            <th class="py-3 px-4">Last Name</th>
                            <th class="py-3 px-4">Role</th>
                            <th class="py-3 px-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <tr class="user-row border-b hover:bg-gray-50" data-user-type="<?php echo $row['U_Type']; ?>" data-status="<?php echo $row['status_details']; ?>">
                                <td class="py-3 px-4 text-center">
                                <input type="checkbox" class="userCheckbox" value="<?php echo $row['IDno']; ?>" <?php echo ''; ?>>
                                </td>
                                <td class="py-3 px-4 text-center"><?php echo htmlspecialchars($row['IDno']); ?></td>
                                <td class="py-3 px-4"><?php echo htmlspecialchars($row['Fname']); ?></td>
                                <td class="py-3 px-4"><?php echo htmlspecialchars($row['Sname']); ?></td>
                                <td class="py-3 px-4"><?php echo htmlspecialchars($row['U_Type']); ?></td>
                                <td class="py-3 px-4 text-center"><?php echo htmlspecialchars($row['status_details']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

            <!-- Pagination Controls -->
            <div class="pagination-controls flex justify-center items-center space-x-4 my-6 flex-col md:flex-row md:space-x-6">
                <button id="prevBtn" onclick="prevPage()" class="btn-pagination px-6 py-2 bg-gray-800 text-white rounded-lg cursor-pointer disabled:bg-gray-400 disabled:cursor-not-allowed transition duration-300 hover:bg-gray-600" disabled>Previous</button>
                <span id="pageInfo" class="text-lg text-gray-600 font-medium">Page 1 of X</span>
                <button id="nextBtn" onclick="nextPage()" class="btn-pagination px-6 py-2 bg-gray-800 text-white rounded-lg cursor-pointer transition duration-300 hover:bg-gray-600">Next</button>
            </div>
        </div>

        <!-- Footer at the Bottom -->
        <footer class="mt-auto">
            <?php include 'include/footer.php'; ?>
        </footer>
    </div>
</div>

<!-- DataTables Initialization -->
<script>
    let currentPage = 1;
    const rowsPerPage = 10;
    let filteredRows = [];

    // Select All Function
    function toggleAll(source) {
        let checkboxes = document.querySelectorAll('.userCheckbox');
        checkboxes.forEach(checkbox => {
            if (!checkbox.disabled) checkbox.checked = source.checked;
        });
    }

    // Update Status Function
    function updateStatus(status) {
        let selectedUsers = [];
        document.querySelectorAll('.userCheckbox:checked').forEach(checkbox => {
            selectedUsers.push(checkbox.value);
        });

        if (selectedUsers.length === 0) {
            alert("No users selected!");
            return;
        }

        let formData = new FormData();
        formData.append('status', status);
        formData.append('users', JSON.stringify(selectedUsers));

        fetch('update_status.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            })
            .catch(error => console.error('Error:', error));
    }

    // Filter Rows based on User Type and Search Term
    function filterRows() {
        const userType = document.querySelector('input[name="userType"]:checked').value;
        const statusFilter = document.querySelector('input[name="statusFilter"]:checked').value;
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const searchCategory = document.getElementById('searchCategory').value;

        const rows = document.querySelectorAll('.user-row');
        filteredRows = [];

        rows.forEach(row => {
            const rowType = row.getAttribute('data-user-type');
            const rowStatus = row.getAttribute('data-status');
            const rowData = row.querySelectorAll('td');
            let matchesSearch = false;

            if (searchCategory === 'all') {
                rowData.forEach(cell => {
                    if (cell.innerText.toLowerCase().includes(searchTerm)) {
                        matchesSearch = true;
                    }
                });
            } else {
                const searchValue = rowData[getSearchCategoryIndex(searchCategory)].innerText.toLowerCase();
                matchesSearch = searchValue.includes(searchTerm);
            }

            // Apply filters: User Type, Status, and Search Term
            if (
                (userType === 'all' || rowType === userType) &&
                (statusFilter === 'all' || rowStatus === statusFilter) &&
                matchesSearch
            ) {
                row.style.display = "";
                filteredRows.push(row);
            } else {
                row.style.display = "none";
            }
        });

        updatePagination();
    }

    function getSearchCategoryIndex(category) {
        switch (category) {
            case 'IDno':
                return 1; // IDno column (second column in the table)
            case 'Fname':
                return 2; // First Name column
            case 'Sname':
                return 3; // Last Name column
            case 'course':
                return 4; // Course column (not visible in the current table)
            case 'college':
                return 5; // College column (not visible in the current table)
            case 'yrLVL':
                return 6; // Year Level column (not visible in the current table)
            default:
                return 1; // Default to the first column (IDno)
        }
    }


    // Update Pagination
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

    // Event Listeners for Filters
    document.querySelectorAll('input[name="userType"]').forEach(function(radio) {
        radio.addEventListener('change', filterRows);
    });

    document.querySelectorAll('input[name="statusFilter"]').forEach(function(radio) {
        radio.addEventListener('change', filterRows);
    });

    document.getElementById('searchInput').addEventListener('input', filterRows);
    document.getElementById('searchCategory').addEventListener('change', filterRows);

    // Initialize on Page Load
    window.addEventListener('DOMContentLoaded', function() {
        const savedUserType = localStorage.getItem('selectedUserType') || 'all';
        document.querySelector(`#${savedUserType}Radio`).checked = true;
        const savedStatusFilter = localStorage.getItem('selectedStatusFilter') || 'all';
        document.querySelector(`#${savedStatusFilter}Radio`).checked = true;
        filterRows();
    });

    // Highlight row and toggle checkbox on row click
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.user-row');
        rows.forEach(row => {
            row.addEventListener('click', function(event) {
                // Prevent toggling if the click is on the checkbox itself
                if (event.target.type === 'checkbox') return;

                const checkbox = row.querySelector('.userCheckbox');
                checkbox.checked = !checkbox.checked;

                // Highlight or reset row background based on checkbox state
                if (checkbox.checked) {
                    row.style.backgroundColor = '#D1FAE5'; // Light green for selected
                } else {
                    row.style.backgroundColor = ''; // Reset to default
                }
            });
        });

        // Ensure checkbox click also highlights the row
        const checkboxes = document.querySelectorAll('.userCheckbox');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const row = this.closest('tr');
                if (this.checked) {
                    row.style.backgroundColor = '#D1FAE5'; // Light green for selected
                } else {
                    row.style.backgroundColor = ''; // Reset to default
                }
            });
        });
    });

    // Highlight row on checkbox click
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.userCheckbox');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const row = this.closest('tr');
                if (this.checked) {
                    row.style.backgroundColor = '#D1FAE5'; // Light green for selected
                } else {
                    row.style.backgroundColor = ''; // Reset to default
                }
            });
        });
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
