<?php
include '../config.php';

// Fetch pending users from the database
function getPendingUsers()
{
    global $conn;
    $query = "
            SELECT IDno, Fname, Sname, U_Type 
            FROM users_info
            WHERE status_log = 'pending'
        ";
    $result = $conn->query($query);

    $users = [];
    if ($result->num_rows > 0) {
        while ($user = $result->fetch_assoc()) {
            $users[] = $user;
        }
    }
    return $users;
}

// Handle AJAX requests to get updated pending users (for dynamic updates)
if (isset($_GET['action']) && $_GET['action'] === 'fetch_pending_users') {
    echo json_encode(getPendingUsers());
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
        <div class="container mx-auto px-4 py-6">

            <h2 class="text-3xl font-semibold mb-6">Pending Users</h2>
            <div class="mb-6 px-4 flex justify-between items-center overflow-x-auto">

                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="py-3 px-4 text-center text-gray-700 font-medium">ID No</th>
                            <th class="py-3 px-4 text-left text-gray-700 font-medium">First Name</th>
                            <th class="py-3 px-4 text-left text-gray-700 font-medium">Last Name</th>
                            <th class="py-3 px-4 text-left text-gray-700 font-medium">Role</th>
                            <th class="py-3 px-4 text-center text-gray-700 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="pendingUsersTable">
                        <!-- Table rows will be injected here via JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Footer at the Bottom -->
        <footer class="bg-blue-600 text-white mt-auto">
            <?php include 'include/footer.php'; ?>
        </footer>
        </div>

    </div>

<!-- Approval Popup -->
<div id="approvePopup" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
        <h3 class="text-lg font-semibold mb-4">Confirm Approval</h3>
        <p>Are you sure you want to approve this user?</p>
        <div class="mt-4 flex justify-end">
            <button id="approveConfirm" class="px-4 py-2 text-white bg-green-500 hover:bg-green-600 rounded mr-2">Confirm</button>
            <button id="approveCancel" class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded">Cancel</button>
        </div>
    </div>
</div>

<!-- Rejection Popup -->
<div id="rejectPopup" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
        <h3 class="text-lg font-semibold mb-4">Confirm Rejection</h3>
        <p>Are you sure you want to reject this user?</p>
        <div class="mt-4 flex justify-end">
            <button id="rejectConfirm" class="px-4 py-2 text-white bg-red-500 hover:bg-red-600 rounded mr-2">Confirm</button>
            <button id="rejectCancel" class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded">Cancel</button>
        </div>
    </div>
</div>

<script>
    let selectedUserId = null;

    // Function to fetch and update the pending users table
    function updatePendingUsers() {
        fetch('?action=fetch_pending_users')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('pendingUsersTable');
                tableBody.innerHTML = ''; // Clear the current table

                if (data.length > 0) {
                    data.forEach(user => {
                        const row = document.createElement('tr');
                        row.classList.add('border-b', 'hover:bg-gray-50');
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
                    });

                    // Reattach event listeners to the newly added buttons
                    attachButtonListeners();
                } else {
                    const row = document.createElement('tr');
                    row.innerHTML = `<td colspan="5" class="py-4 text-center text-gray-600">No pending users.</td>`;
                    tableBody.appendChild(row);
                }
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
        if (event.target === approvePopup || event.target === rejectPopup) {
            approvePopup.classList.add('hidden');
            rejectPopup.classList.add('hidden');
        }
    });

    // Initially load pending users
    updatePendingUsers();

    // Auto-update the pending users every 5 seconds
    setInterval(updatePendingUsers, 5000);
</script>
