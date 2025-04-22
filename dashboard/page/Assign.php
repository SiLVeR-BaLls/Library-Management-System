<?php
    include '../config.php';

    // Fetch user data from the database where status_log is approved and U_Type is librarian or admin
    $sql = "SELECT u.IDno, u.Fname, u.Sname, u.U_Type 
                FROM users_info u
                WHERE u.status_log = 'approved' AND u.U_Type IN ('librarian', 'admin')";

    $result = $conn->query($sql);

    // Check if there are any results
    if ($result->num_rows > 0) {
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    } else {
        echo "No results found";
    }

    // Check if a POST request to update role is made
    if (isset($_POST['IDno']) && isset($_POST['U_Type'])) {
        $IDno = $_POST['IDno'];
        $U_Type = $_POST['U_Type'];

        // Prepare and execute the update statement
        $sql = "UPDATE users_info SET U_Type = ? WHERE IDno = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $U_Type, $IDno);

        if ($stmt->execute()) {
            // Return success response if update is successful
            echo "Role updated successfully!";
        } else {
            // Return error response if update fails
            echo "Error updating role: " . $conn->error;
        }

        $stmt->close();
    }
?>
<style>
    .action-btn {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 20px;
        text-align: center;
        display: block;
        cursor: pointer;

    }

    .action-btn.enabled {
        opacity: 1;
    }
</style>

<!-- Main Content Area with Sidebar and BrowseBook Section -->
<div class="flex">
    <!-- Sidebar Section -->
    <?php include $sidebars[$userType] ?? ''; ?>
    <!-- BrowseBook Content Section -->
    <div class="flex-grow ">
        <!-- Header at the Top -->
        <?php include 'include/header.php'; ?>
        <!-- content area -->
        <div class="container mx-auto px-4 py-6 ">
        <h2 class="text-3xl font-semibold mb-6">User Role Management</h2>

            <form id="role-update-form" method="POST" class="space-y-6">
                <!-- Search Input -->
                <div class="flex justify-center">
                    <input type="text" id="searchInput" placeholder="Search by ID, First Name, or Surname..." class="w-full max-w-lg px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <!-- Table -->
                <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                    <table class="min-w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-200 " style="background: <?= $sidebar ?>; color : <?= $text1 ?>;">
                                <th class="py-3 px-6 text-left border-b">ID</th>
                                <th class="py-3 px-6 text-left border-b">First Name</th>
                                <th class="py-3 px-6 text-left border-b">Surname</th>
                                <th class="py-3 px-6 text-left border-b">Role</th>
                                <th class="py-3 px-6 text-center border-b">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-gray-100">
                                    <td class="py-4 px-6 border-b"><?= $user['IDno'] ?></td>
                                    <td class="py-4 px-6 border-b"><?= $user['Fname'] ?></td>
                                    <td class="py-4 px-6 border-b"><?= $user['Sname'] ?></td>
                                    <td class="py-4 px-6 border-b">
                                        <select class="border border-gray-300 rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 role-select" name="U_Type[<?= $user['IDno'] ?>]" data-id="<?= $user['IDno'] ?>" data-current-role="<?= $user['U_Type'] ?>">
                                            <option value="admin" <?= $user['U_Type'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                            <option value="faculty" <?= $user['U_Type'] == 'faculty' ? 'selected' : '' ?>>Faculty</option>
                                            <option value="librarian" <?= $user['U_Type'] == 'librarian' ? 'selected' : '' ?>>Librarian</option>
                                        </select>
                                    </td>
                                    <td class="py-4 px-6 text-center border-b">
                                        <button type="button" class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-green-600 disabled:bg-gray-300 disabled:cursor-not-allowed action-btn" data-id="<?= $user['IDno'] ?>" disabled>Confirm</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex justify-center items-center mt-6 space-x-4">
                    <button id="prevBtn" onclick="prevPage()" class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow-sm hover:bg-blue-600 disabled:bg-gray-300 disabled:cursor-not-allowed" disabled>Previous</button>
                    <span id="pageInfo" class="text-lg text-gray-600 font-medium">Page 1 of X</span>
                    <button id="nextBtn" onclick="nextPage()" class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow-sm hover:bg-blue-600 disabled:bg-gray-300 disabled:cursor-not-allowed">Next</button>
                </div>
            </form>
        </div>
        <footer>
            <?php include 'include/footer.php'; ?>
        </footer>
    </div>
</div>

<script>
    // JavaScript for managing search and pagination
    let currentPage = 1;
    const rowsPerPage = 10; // Number of rows per page
    const rows = Array.from(document.querySelectorAll("tbody tr")); // All rows in the table

    // Function to filter rows based on search input
    function filterRows() {
        const searchTerm = document.getElementById("searchInput").value.toLowerCase();
        return rows.filter(row => {
            const id = row.querySelector("td:nth-child(1)").innerText.toLowerCase();
            const firstName = row.querySelector("td:nth-child(2)").innerText.toLowerCase();
            const surname = row.querySelector("td:nth-child(3)").innerText.toLowerCase();
            return id.includes(searchTerm) || firstName.includes(searchTerm) || surname.includes(searchTerm);
        });
    }

    // Function to update pagination and display rows
    function updatePagination() {
        const filteredRows = filterRows();
        const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
        currentPage = Math.max(1, Math.min(currentPage, totalPages));

        rows.forEach(row => row.style.display = "none"); // Hide all rows
        filteredRows.slice((currentPage - 1) * rowsPerPage, currentPage * rowsPerPage).forEach(row => row.style.display = ""); // Show rows for the current page

        document.getElementById("pageInfo").innerText = `Page ${currentPage} of ${totalPages || 1}`;
        document.getElementById("prevBtn").disabled = currentPage === 1;
        document.getElementById("nextBtn").disabled = currentPage === totalPages || totalPages === 0;
    }

    // Event listeners for search and pagination
    document.getElementById("searchInput").addEventListener("input", () => {
        currentPage = 1; // Reset to the first page on search
        updatePagination();
    });

    function prevPage() {
        if (currentPage > 1) {
            currentPage--;
            updatePagination();
        }
    }

    function nextPage() {
        const filteredRows = filterRows();
        const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            updatePagination();
        }
    }

    // Initialize pagination on page load
    document.addEventListener("DOMContentLoaded", updatePagination);
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all the select elements and action buttons
        const roleSelects = document.querySelectorAll('.role-select');
        const actionBtns = document.querySelectorAll('.action-btn');

        // Attach change event listeners to each select element
        roleSelects.forEach(select => {
            select.addEventListener('change', function() {
                const userId = this.getAttribute('data-id');
                const selectedRole = this.value;
                const currentRole = this.getAttribute('data-current-role');

                // Find the corresponding action button
                const actionBtn = document.querySelector(`.action-btn[data-id="${userId}"]`);

                // Enable the confirm button if the role has changed
                if (selectedRole !== currentRole) {
                    actionBtn.disabled = false; // Enable button
                    actionBtn.classList.remove('disabled:bg-gray-300', 'disabled:cursor-not-allowed');
                } else {
                    actionBtn.disabled = true; // Disable button
                    actionBtn.classList.add('disabled:bg-gray-300', 'disabled:cursor-not-allowed');
                }
            });
        });

        // Handle the confirm button click
        actionBtns.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-id');
                const selectedRole = document.querySelector(`.role-select[data-id="${userId}"]`).value;

                // Debug: Log the data being sent
                console.log("Sending data:", {
                    IDno: userId,
                    U_Type: selectedRole
                });

                // Prepare the form data for sending
                const formData = new FormData();
                formData.append('IDno', userId);
                formData.append('U_Type', selectedRole);

                // Send the data to the PHP backend
                fetch(window.location.href, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text()) // Get the response text from PHP
                    .then(data => {
                        console.log("Server response:", data); // Log the response from the server
                        if (data.includes('Role updated successfully')) {
                            alert("Role updated successfully!"); // Show success message
                            button.disabled = true; // Disable the button after successful update
                            button.classList.add('disabled:bg-gray-300', 'disabled:cursor-not-allowed');
                        } else {
                            alert('Error updating role.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error); // Log any errors
                        alert('Error updating role.');
                    });
            });
        });
    });
</script>

<script>
    // JavaScript for managing search functionality
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("searchInput");
        const rows = document.querySelectorAll("tbody tr");

        searchInput.addEventListener("input", function() {
            const searchTerm = searchInput.value.toLowerCase();

            rows.forEach(row => {
                const id = row.querySelector("td:nth-child(1)").innerText.toLowerCase();
                const firstName = row.querySelector("td:nth-child(2)").innerText.toLowerCase();
                const surname = row.querySelector("td:nth-child(3)").innerText.toLowerCase();

                // Check if the search term matches any of the columns
                if (id.includes(searchTerm) || firstName.includes(searchTerm) || surname.includes(searchTerm)) {
                    row.style.display = ""; // Show matching rows
                } else {
                    row.style.display = "none"; // Hide non-matching rows
                }
            });
        });
    });
</script>