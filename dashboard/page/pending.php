<?php
    include '../config.php';

    // Fetch pending users from the database with pagination
    function getPendingUsers($page = 1, $limit = 10)
    {
        global $conn;
        $offset = ($page - 1) * $limit;
        $query = "
                SELECT IDno, Fname, Sname, U_Type 
                FROM users_info
                WHERE status_log = 'pending'
                LIMIT $limit OFFSET $offset
            ";
        $result = $conn->query($query);

        $users = [];
        if ($result->num_rows > 0) {
            while ($user = $result->fetch_assoc()) {
                $users[] = $user;
            }
        }

        // Get total count of pending users
        $countQuery = "SELECT COUNT(*) as total FROM users_info WHERE status_log = 'pending'";
        $countResult = $conn->query($countQuery);
        $total = $countResult->fetch_assoc()['total'];

        return ['users' => $users, 'total' => $total];
    }

    // Handle AJAX requests to get updated pending users (for dynamic updates)
    if (isset($_GET['action']) && $_GET['action'] === 'fetch_pending_users') {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        echo json_encode(getPendingUsers($page, $limit));
        exit;
    }
?>

<!-- Main Content Area with Sidebar and BrowseBook Section -->
<div class="flex">
    <!-- Sidebar Section -->
    <?php include $sidebars[$userType] ?? ''; ?>
    <!-- BrowseBook Content Section -->
    <div class="flex-grow">
        <!-- Header at the Top -->
        <?php include 'include/header.php'; ?>
        <div  class="container mx-auto px-4 py-6">

            <h2 class="text-3xl font-semibold mb-6">Pending Users</h2>
            <div class="mb-6 px-4 flex justify-between items-center overflow-x-auto">

                <table  class="min-w-full bg-white border-collapse border  rounded-lg shadow-md">
                    <thead style="background: <?= $sidebar?>; color : <?= $text1 ?>;" class="bg-gray-100 border-b">
                        <tr>
                            <th class="py-3 px-4 text-center  font-medium border ">ID No</th>
                            <th class="py-3 px-4 text-left  font-medium border ">First Name</th>
                            <th class="py-3 px-4 text-left  font-medium border ">Last Name</th>
                            <th class="py-3 px-4 text-left  font-medium border ">Role</th>
                            <th class="py-3 px-4 text-center  font-medium border ">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="pendingUsersTable">
                        <!-- Table rows will be injected here via JavaScript -->
                    </tbody>
                </table>
            </div>
            <!-- Pagination Controls -->
            <div id="paginationControls" class="flex justify-center mt-4 space-x-2">
                <!-- Pagination buttons will be injected here via JavaScript -->
            </div>
        </div>
        <!-- Footer at the Bottom -->
        <footer>
            <?php include 'include/footer.php'; ?>
        </footer>
        </div>

    </div>

<!-- Approval Popup -->
<div id="approvePopup" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
        <h3 class="text-lg font-semibold mb-4">Confirm Approval</h3>
        <p>Are you sure you want to approve this user?</p>
        <div class="mt-4 flex justify-end">
            <button id="approveConfirm" class="px-4 py-2 text-white bg-green-500 hover:bg-green-600 rounded mr-2">Confirm</button>
            <button id="approveCancel" class="px-4 py-2  bg-gray-200 hover:bg-gray-300 rounded">Cancel</button>
        </div>
    </div>
</div>

<!-- Rejection Popup -->
<div id="rejectPopup" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
        <h3 class="text-lg font-semibold mb-4">Confirm Rejection</h3>
        <p>Are you sure you want to reject this user?</p>
        <div class="mt-4 flex justify-end">
            <button id="rejectConfirm" class="px-4 py-2 text-white bg-red-500 hover:bg-red-600 rounded mr-2">Confirm</button>
            <button id="rejectCancel" class="px-4 py-2  bg-gray-200 hover:bg-gray-300 rounded">Cancel</button>
        </div>
    </div>
</div>

<!-- User Details Popup -->
<div id="userDetailsPopup" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
        <h3 class="text-lg font-semibold mb-4">User Details</h3>
        <div id="userDetailsContent" class="space-y-2">
            <!-- User details will be injected here via JavaScript -->
        </div>
        <div class="mt-4 flex justify-end">
            <button id="userDetailsClose" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">Close</button>
        </div>
    </div>
</div>

<script>
    let selectedUserId = null;
    let currentPage = 1;
    const limit = 10;

    // Function to fetch and update the pending users table
    function updatePendingUsers(page = 1) {
        fetch(`?action=fetch_pending_users&page=${page}&limit=${limit}`)
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('pendingUsersTable');
                const paginationControls = document.getElementById('paginationControls');
                tableBody.innerHTML = ''; // Clear the current table
                paginationControls.innerHTML = ''; // Clear pagination controls

                if (data.users.length > 0) {
                    data.users.forEach(user => {
                        const row = document.createElement('tr');
                        row.classList.add('border-b', 'hover:bg-gray-50', 'cursor-pointer');
                        row.setAttribute('data-id', user.IDno);
                        row.innerHTML = `
                            <td class="py-3 px-4 text-center">${user.IDno}</td>
                            <td class="py-3 px-4">${user.Fname}</td>
                            <td class="py-3 px-4">${user.Sname}</td>
                            <td class="py-3 px-4">${user.U_Type}</td>
                            <td class="py-3 px-4 text-center space-x-2">
                                <button class="px-3 py-1 text-sm text-white bg-green-500 hover:bg-green-600 rounded approve-button" data-id="${user.IDno}">Approve</button>
                                <button class="px-3 py-1 text-sm text-white bg-red-500 hover:bg-red-600 rounded reject-button" data-id="${user.IDno}">Reject</button>
                            </td>
                        `;
                        tableBody.appendChild(row);

                        // Attach click event to show user details
                        row.addEventListener('click', function () {
                            fetchUserDetails(user.IDno);
                        });
                    });

                    // Reattach event listeners to the newly added buttons
                    attachButtonListeners();

                    // Generate pagination controls
                    const totalPages = Math.ceil(data.total / limit);
                    for (let i = 1; i <= totalPages; i++) {
                        const button = document.createElement('button');
                        button.textContent = i;
                        button.classList.add('px-3', 'py-1', 'rounded', 'border', 'hover:bg-gray-200');
                        if (i === page) {
                            button.classList.add('bg-blue-500', 'text-white');
                        }
                        button.addEventListener('click', () => {
                            currentPage = i;
                            updatePendingUsers(i);
                        });
                        paginationControls.appendChild(button);
                    }
                } else {
                    const row = document.createElement('tr');
                    row.innerHTML = `<td colspan="5" class="py-4 text-center text-gray-600">No pending users.</td>`;
                    tableBody.appendChild(row);
                }
            });
    }

    // Function to fetch user details and display in the modal
    function fetchUserDetails(userId) {
        fetch(`include/get_user_details.php?id=${userId}`)
            .then(response => response.json())
            .then(data => {
                const userDetailsContent = document.getElementById('userDetailsContent');
                userDetailsContent.innerHTML = `
                    <p><strong>ID No:</strong> ${data.IDno}</p>
                    <p><strong>First Name:</strong> ${data.Fname}</p>
                    <p><strong>Last Name:</strong> ${data.Sname}</p>
                    <p><strong>Email:</strong> ${data.Email}</p>
                    <p><strong>College:</strong> ${data.College}</p>
                    <p><strong>Role:</strong> ${data.U_Type}</p>
                `;
                const userDetailsPopup = document.getElementById('userDetailsPopup');
                userDetailsPopup.classList.remove('hidden');
                userDetailsPopup.style.display = 'flex'; // Ensure the popup is displayed as a flex container
            })
            .catch(error => {
                console.error('Error fetching user details:', error);
            });
    }

    // Function to attach click event listeners to the approve and reject buttons
    function attachButtonListeners() {
        // Open Approval Popup
        document.querySelectorAll('.approve-button').forEach(button => {
            button.addEventListener('click', function() {
                selectedUserId = this.getAttribute('data-id');
                document.getElementById('approvePopup').classList.remove('hidden');
            });
        });

        // Open Rejection Popup
        document.querySelectorAll('.reject-button').forEach(button => {
            button.addEventListener('click', function() {
                selectedUserId = this.getAttribute('data-id');
                document.getElementById('rejectPopup').classList.remove('hidden');
            });
        });

        attachRowClickListeners();
    }

    // Close Popup (Cancel)
    document.getElementById('approveCancel').addEventListener('click', function() {
        document.getElementById('approvePopup').classList.add('hidden');
    });
    document.getElementById('rejectCancel').addEventListener('click', function() {
        document.getElementById('rejectPopup').classList.add('hidden');
    });

    // Confirm Approve Action
    document.getElementById('approveConfirm').addEventListener('click', function() {
        window.location.href = `include/approve_user.php?id=${selectedUserId}`;
    });

    // Confirm Reject Action
    document.getElementById('rejectConfirm').addEventListener('click', function() {
        window.location.href = `include/reject_user.php?id=${selectedUserId}`;
    });

    // Close Popup if clicked outside
    window.addEventListener('click', function(event) {
        const approvePopup = document.getElementById('approvePopup');
        const rejectPopup = document.getElementById('rejectPopup');
        const userDetailsPopup = document.getElementById('userDetailsPopup');
        if (event.target === approvePopup || event.target === rejectPopup || event.target === userDetailsPopup) {
            approvePopup.classList.add('hidden');
            rejectPopup.classList.add('hidden');
            userDetailsPopup.classList.add('hidden');
            userDetailsPopup.style.display = 'none'; // Ensure the popup is hidden
        }
    });

    // Close User Details Popup
    document.getElementById('userDetailsClose').addEventListener('click', function () {
        const userDetailsPopup = document.getElementById('userDetailsPopup');
        userDetailsPopup.classList.add('hidden');
        userDetailsPopup.style.display = 'none'; // Ensure the popup is hidden
    });

    // Ensure rows are clickable for showing user details
    function attachRowClickListeners() {
        document.querySelectorAll('#pendingUsersTable tr').forEach(row => {
            row.addEventListener('click', function () {
                const userId = this.getAttribute('data-id');
                if (userId) {
                    fetchUserDetails(userId);
                }
            });
        });
    }

    // Initially load pending users
    updatePendingUsers();

    // Auto-update the pending users every 5 seconds
    setInterval(() => updatePendingUsers(currentPage), 5000);
</script>
