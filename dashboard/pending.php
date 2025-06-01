<?php
    include 'config.php';

    // Fetch pending users from the database with pagination
    function getPendingUsers($page = 1, $limit = 10)
    {
        global $conn;
        $offset = ($page - 1) * $limit;
        $query = "
                        SELECT *
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
        <div class="container mx-auto px-4 py-6">

            <h2 class="text-3xl font-semibold mb-6">Pending Users</h2>
            <div class="mb-6 px-4 flex justify-between items-center overflow-x-auto">

                <table class="min-w-full bg-white border-collapse border  rounded-lg shadow-md">
                    <thead style="background: <?= $sidebar ?>; color : <?= $text1 ?>;" class="bg-gray-100 border-b">
                        <tr>
                            <th class="py-2 px-3 text-center  font-medium border text-sm">ID No</th>
                            <th class="py-2 px-3 text-left  font-medium border text-sm">First Name</th>
                            <th class="py-2 px-3 text-left  font-medium border text-sm">Last Name</th>
                            <th class="py-2 px-3 text-left  font-medium border text-sm">Role</th>
                            <th class="py-2 px-3 text-center  font-medium border text-sm">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="pendingUsersTable">
                    </tbody>
                </table>
            </div>
            <div id="paginationControls" class="flex justify-center mt-4 space-x-2">
            </div>
        </div>
        <footer class="mt-auto">
            <?php include 'include/footer.php'; ?>
        </footer>
    </div>

</div>

<div id="approvePopup" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
        <h3 class="text-lg font-semibold mb-4">Confirm Approval</h3>
        <p class="text-sm">Are you sure you want to approve this user?</p>
        <div class="mt-4 flex justify-end">
            <button id="approveConfirm" class="px-2 py-1 text-xs text-white bg-green-500 hover:bg-green-600 rounded mr-2">Confirm</button>
            <button id="approveCancel" class="px-2 py-1 text-xs bg-gray-200 hover:bg-gray-300 rounded">Cancel</button>
        </div>
    </div>
</div>

<div id="rejectPopup" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
        <h3 class="text-lg font-semibold mb-4">Confirm Rejection</h3>
        <p class="text-sm">Are you sure you want to reject this user?</p>
        <div class="mt-4 flex justify-end">
            <button id="rejectConfirm" class="px-2 py-1 text-xs text-white bg-red-500 hover:bg-red-600 rounded mr-2">Confirm</button>
            <button id="rejectCancel" class="px-2 py-1 text-xs bg-gray-200 hover:bg-gray-300 rounded">Cancel</button>
        </div>
    </div>
</div>

<div id="userDetailsPopup" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white p-4 rounded-lg shadow-lg max-w-xs w-full relative">
        <button id="userDetailsClose" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 focus:outline-none">
            <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
        <div id="userDetailsContent" class="space-y-2 text-sm">
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
                        row.setAttribute('data-utype', user.U_Type); // Store U_Type in data attribute
                        row.innerHTML = `
                            <td class="py-2 px-3 text-center text-sm">${user.IDno}</td>
                            <td class="py-2 px-3 text-sm">${user.Fname}</td>
                            <td class="py-2 px-3 text-sm">${user.Sname}</td>
                            <td class="py-2 px-3 text-sm">${user.U_Type}</td>
                            <td class="py-2 px-3 text-center space-x-2">
                                <button class="px-2 py-1 text-xs text-white bg-green-500 hover:bg-green-600 rounded approve-button" data-id="${user.IDno}">Approve</button>
                                <button class="px-2 py-1 text-xs text-white bg-red-500 hover:bg-red-600 rounded reject-button" data-id="${user.IDno}">Reject</button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });

                    // Reattach event listeners to the newly added buttons
                    attachButtonListeners();

                    // Generate pagination controls
                    const totalPages = Math.ceil(data.total / limit);
                    for (let i = 1; i <= totalPages; i++) {
                        const button = document.createElement('button');
                        button.textContent = i;
                        button.classList.add('px-2', 'py-1', 'rounded', 'border', 'hover:bg-gray-200', 'text-xs');
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
                    row.innerHTML = `<td colspan="5" class="py-4 text-center text-gray-600 text-sm">No pending users.</td>`;
                    tableBody.appendChild(row);
                }
                // Attach row click listeners after updating the table
                attachRowClickListeners();
            });
    }

  // Function to fetch user details and display in the modal
  function fetchUserDetails(userId) {
        // Find the user data from the initially fetched list
        fetch(`?action=fetch_pending_users&page=${currentPage}&limit=${limit}`)
            .then(response => response.json())
            .then(data => {
                const user = data.users.find(u => u.IDno === userId);
                if (user) {
                    console.log(user); // <--- ADDED FOR DEBUGGING
                    const userDetailsContent = document.getElementById('userDetailsContent');
                    userDetailsContent.innerHTML = `
                        <div class="grid grid-cols-2 gap-x-6 text-sm">
                            <div class="mb-2">
                                <strong class="block text-gray-700 text-xs font-semibold">ID No:</strong>
                                <span class="block text-black text-base overflow-y-auto max-h-20">${user.IDno}</span>
                            </div>
                            <div class="mb-2">
                                <strong class="block text-gray-700 text-xs font-semibold">First Name:</strong>
                                <span class="block text-black text-base overflow-y-auto max-h-20">${user.Fname}</span>
                            </div>
                          <div class="mb-2">
    <strong class="block text-gray-700 text-xs font-semibold">Sex:</strong>
    <span class="block text-black text-base overflow-y-auto max-h-20">
        ${
            user.gender === 'm' ? 'Male' :
            user.gender === 'f' ? 'Female' :
            user.gender === 'O' ? 'Other' :
            'Gender Unidentified'
        }
    </span>
</div>

                            <div class="mb-2">
                                <strong class="block text-gray-700 text-xs font-semibold">Middle Name:</strong>
                                <span class="block text-black text-base overflow-y-auto max-h-20">${user.Mname || 'N/A'}</span>
                            </div>
                            <div class="mb-2">
                                <strong class="block text-gray-700 text-xs font-semibold">Last Name:</strong>
                                <span class="block text-black text-base overflow-y-auto max-h-20">${user.Sname}</span>
                            </div>
                            <div class="mb-2">
                                <strong class="block text-gray-700 text-xs font-semibold">Email:</strong>
                                <span class="block text-black text-base overflow-y-auto max-h-20">${user.email}</span>
                            </div>
                            <div class="mb-2">
                                <strong class="block text-gray-700 text-xs font-semibold">Role:</strong>
                                <span class="block text-black text-base overflow-y-auto max-h-20">${user.U_Type}</span>
                            </div>
                            <div class="mb-2" id="collegeContainer">
                                <strong class="block text-gray-700 text-xs font-semibold">College:</strong>
                                <span class="block text-black text-base overflow-y-auto max-h-20">${user.college || 'N/A'}</span>
                            </div>
                            <div class="mb-2" id="courseContainer">
                                <strong class="block text-gray-700 text-xs font-semibold">College Course:</strong>
                                <span class="block text-black text-base overflow-y-auto max-h-20">${user.college_course || 'N/A'}</span>
                            </div>
                            <div class="mb-2" id="yearLevelContainer">
                                <strong class="block text-gray-700 text-xs font-semibold">College Year Level:</strong>
                                <span class="block text-black text-base overflow-y-auto max-h-20">${user.college_yrLVL || 'N/A'}</span>
                            </div>
                            <div class="mb-2" id="personnelTypeContainer">
                                <strong class="block text-gray-700 text-xs font-semibold">Personnel Type:</strong>
                                <span class="block text-black text-base overflow-y-auto max-h-20">${user.personnel_type || 'N/A'}</span>
                            </div>
                            <div></div>
                        </div>
                    `;

                    const courseContainer = document.getElementById('courseContainer');
                    const yearLevelContainer = document.getElementById('yearLevelContainer');
                    const collegeContainer = document.getElementById('collegeContainer');
                    const personnelTypeContainer = document.getElementById('personnelTypeContainer');

                    // Function to hide or show role-specific details
                    function updateRoleSpecificDetails(user) {
                        if (collegeContainer) collegeContainer.style.display = user.college ? 'block' : 'none';
                        if (courseContainer) courseContainer.style.display = user.college_course ? 'block' : 'none';
                        if (yearLevelContainer) yearLevelContainer.style.display = user.college_yrLVL ? 'block' : 'none';
                        if (personnelTypeContainer) personnelTypeContainer.style.display = user.personnel_type ? 'block' : 'none';
                    }

                    // Initially hide all
                    updateRoleSpecificDetails({});

                    if (user.U_Type === 'student') {
                        updateRoleSpecificDetails(user); // Show relevant student fields if they have data
                    } else if (['librarian', 'admin', 'faculty'].includes(user.U_Type)) {
                        updateRoleSpecificDetails(user); // Show relevant personnel fields if they have data
                    }

                    const userDetailsPopup = document.getElementById('userDetailsPopup');
                    // Increase the max-w-xs to make the popup wider
                    userDetailsPopup.querySelector('.bg-white').classList.remove('max-w-xs');
                    userDetailsPopup.querySelector('.bg-white').classList.add('max-w-md');
                    userDetailsPopup.classList.remove('hidden');
                    userDetailsPopup.style.display = 'flex'; // Ensure the popup is displayed as a flex container
                } else {
                    console.error('User details not found for ID:', userId);
                }
            })
            .catch(error => {
                console.error('Error fetching pending users:', error);
            });
    }

    // Function to attach click event listeners to the approve and reject buttons
    function attachButtonListeners() {
        // Open Approval Popup
        document.querySelectorAll('.approve-button').forEach(button => {
            button.addEventListener('click', function(event) {
                event.stopPropagation(); // Prevent row click when button is clicked
                selectedUserId = this.getAttribute('data-id');
                document.getElementById('approvePopup').classList.remove('hidden');
            });
        });

        // Open Rejection Popup
        document.querySelectorAll('.reject-button').forEach(button => {
            button.addEventListener('click', function(event) {
                event.stopPropagation(); // Prevent row click when button is clicked
                selectedUserId = this.getAttribute('data-id');
                document.getElementById('rejectPopup').classList.remove('hidden');
            });
        });
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
        window.location.href = `page/include/approve_user.php?id=${selectedUserId}`;
    });

    // Confirm Reject Action
    document.getElementById('rejectConfirm').addEventListener('click', function() {
        window.location.href = `page/include/reject_user.php?id=${selectedUserId}`;
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
    document.getElementById('userDetailsClose').addEventListener('click', function() {
        const userDetailsPopup = document.getElementById('userDetailsPopup');
        userDetailsPopup.classList.add('hidden');
        userDetailsPopup.style.display = 'none'; // Ensure the popup is hidden
    });

    // Ensure rows are clickable for showing user details
    function attachRowClickListeners() {
        document.querySelectorAll('#pendingUsersTable tr').forEach(row => {
            row.addEventListener('click', function() {
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