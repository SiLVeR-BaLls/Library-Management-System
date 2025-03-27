<?php


    // Initialize the query
    $usersQuery = "
        SELECT *
        FROM users_info
        WHERE users_info.status_log = 'approved'";

    // Apply U_Type filter if selected
    if (isset($_GET['U_Type']) && !empty($_GET['U_Type'])) {
        $U_Type = mysqli_real_escape_string($conn, $_GET['U_Type']);
        $usersQuery .= " AND users_info.U_Type = '$U_Type'";
    }

    // Fetch users without pagination (no LIMIT clause)
    $usersResult = mysqli_query($conn, $usersQuery);

    // Handle user deletion
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        // Get the IDno of the user to delete from the form
        $idToDelete = $_POST['id']; // Get the IDno of the user from the form

        // Ensure $idToDelete is safe to use (sanitize)
        $idToDelete = mysqli_real_escape_string($conn, $idToDelete);

        // Delete SQL query
        $deleteSql = "DELETE FROM users_info WHERE IDno = ?";

        if (!$conn) {
            die("Database connection failed: " . mysqli_connect_error());
        }

        $deleteStmt = $conn->prepare($deleteSql);

        // Check if prepare statement is successful
        if ($deleteStmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        // Bind the IDno parameter for the deletion
        $deleteStmt->bind_param("s", $idToDelete); // 's' means the parameter is a string

        // Execute the deletion
        if ($deleteStmt->execute()) {
            // Redirect to the table page after deletion
            header('Location: BrowseUser.php ');
            // End output buffering and flush the output
ob_end_flush();
            exit(); // Make sure to call exit() after a header redirect
        } else {
            // Handle the error if the deletion failed
            echo "Error deleting user: " . $deleteStmt->error;
        }
        // Close the prepared statement
        $deleteStmt->close();
}


?>

<div class="container mx-auto px-4 py-6 ">
<h2 class="text-3xl font-semibold mb-6">Browse Users</h2>

  <div class="flex flex-row  items-center my-4 space-y-4">
    <div class="flex flex-wrap justify-center items-center gap-4 w-full">
      <!-- Search Bar -->
      <div class="flex justify-center items-center w-full md:w-auto">
        <div class="flex w-full justify-center">
          <input type="text" id="searchInput" placeholder="Search..."
            class="border rounded px-4 py-2 w-full focus:ring-2 focus:ring-blue-400 focus:outline-none">
          <select id="searchCategory" class="border  rounded px-4 py-2 focus:ring-2 focus:ring-blue-400">
            <option value="IDno">IDno</option>
            <option value="Fname">First Name</option>
            <option value="Sname">Last Name</option>
            <option value="course">Course</option>
            <option value="college">College</option>
            <option value="yrLVL">Year</option>
          </select>
        </div>
      </div>

      <!-- Filter Bar -->
      <div class="flex justify-center items-center w-full md:w-auto">
        <div class="radio-input flex rounded-lg border-2 border-gray-200 bg-gray-100 overflow-hidden ">
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

  <!-- Table -->
  <div class="overflow-x-auto bg-white shadow-md rounded-lg">
    <table id="usersTable" class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-100">
        <tr style="background: <?= $sidebar?>; color : <?= $text1 ?>;">
          <th class="px-4 py-3 text-left text-xs font-medium  uppercase tracking-wider">IDno</th>
          <th class="px-4 py-3 text-left text-xs font-medium  uppercase tracking-wider">First Name</th>
          <th class="px-4 py-3 text-left text-xs font-medium  uppercase tracking-wider">Last Name</th>
          <th class="px-4 py-3 text-left text-xs font-medium  uppercase tracking-wider">Course</th>
          <th class="px-4 py-3 text-left text-xs font-medium  uppercase tracking-wider">U_Type</th>
          <th class="px-4 py-3 text-left text-xs font-medium  uppercase tracking-wider">Year</th>
          <th class="px-4 py-3 text-left text-xs font-medium  uppercase tracking-wider">College</th>
          <th class="px-4 py-3 text-left text-xs font-medium  uppercase tracking-wider">Action</th>
        </tr>
      </thead>
      <tbody id="tableBody" class="bg-white  divide-gray-200">
        <?php while ($row = mysqli_fetch_assoc($usersResult)): ?>
          <tr class="user-row" data-user-type="<?php echo htmlspecialchars($row['U_Type']); ?>">
            <td class="px-4 py-2 "><?php echo htmlspecialchars($row['IDno']); ?></td>
            <td class="px-4 py-2 "><?php echo htmlspecialchars($row['Fname']); ?></td>
            <td class="px-4 py-2 "><?php echo htmlspecialchars($row['Sname']); ?></td>
            <td class="px-4 py-2 "><?php echo htmlspecialchars($row['course']); ?></td>
            <td class="px-4 py-2 "><?php echo htmlspecialchars($row['U_Type']); ?></td>
            <td class="px-4 py-2 "><?php echo htmlspecialchars($row['yrLVL']); ?></td>
            <td class="px-4 py-2 "><?php echo htmlspecialchars($row['college']); ?></td>
            <td>
            <form method="POST" action="" 
  class="px-4 py-2  flex space-x-2">
    <a href="user_details.php?id=<?php echo htmlspecialchars($row['IDno']); ?>" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">View</a>
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['IDno']); ?>">
    <button type="submit" class="bg-red-500 w-full text-white px-3 py-1 rounded text-sm">Delete</button>
</form>

            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <div class="pagination-controls flex justify-center items-center space-x-4 my-6 flex-col md:flex-row md:space-x-6">
    <button id="prevBtn" onclick="prevPage()" class="btn-pagination px-6 py-2 bg-gray-800 text-white rounded-lg cursor-pointer disabled:bg-gray-400 disabled:cursor-not-allowed transition duration-300 hover:bg-gray-600" disabled>Previous</button>
    <span id="pageInfo" class="text-lg text-gray-600 font-medium">Page 1 of X</span> <!-- Placeholder for page info -->
    <button id="nextBtn" onclick="nextPage()" class="btn-pagination px-6 py-2 bg-gray-800 text-white rounded-lg cursor-pointer transition duration-300 hover:bg-gray-600">Next</button>
  </div>
</div>

<script>
  // JavaScript for managing search and pagination
  let currentPage = 1;
  const rowsPerPage = 10; // Number of rows per page
  let filteredRows = []; // Holds the filtered rows after applying both userType filter and search filter

  // Handle UserType filter changes
  document.querySelectorAll('input[name="userType"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
      const userType = this.value; // Get the selected user type value

      // Save the selected userType in localStorage
      localStorage.setItem('selectedUserType', userType);

      // Apply the filter immediately to the rows
      filterRows();
    });
  });

  // Handle search input and category filter
  document.getElementById('searchInput').addEventListener('input', filterRows);
  document.getElementById('searchCategory').addEventListener('change', filterRows);

  // Function to apply the filter to the rows based on userType and search term
  function filterRows() {
    const userType = document.querySelector('input[name="userType"]:checked').value;
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const searchCategory = document.getElementById('searchCategory').value;

    const rows = document.querySelectorAll('.user-row');
    filteredRows = [];

    rows.forEach(row => {
      const rowType = row.getAttribute('data-user-type');
      const rowData = row.querySelectorAll('td');
      const searchValue = rowData[getSearchCategoryIndex(searchCategory)].innerText.toLowerCase();

      // Apply userType and search term filter
      if ((userType === 'all' || rowType === userType) && searchValue.includes(searchTerm)) {
        row.style.display = ""; // Show matching rows
        filteredRows.push(row); // Add to the filtered list
      } else {
        row.style.display = "none"; // Hide non-matching rows
      }
    });

    // Update pagination with filtered rows
    updatePagination();
  }

  // Get the index of the selected search category
  function getSearchCategoryIndex(category) {
    switch (category) {
      case 'IDno':
        return 0;
      case 'Fname':
        return 1;
      case 'Sname':
        return 2;
      case 'course':
        return 3;
      case 'college':
        return 4;
      case 'yrLVL':
        return 5;
      default:
        return 0;
    }
  }

  // Function to update pagination based on filtered rows
  function updatePagination() {
    const totalPages = Math.ceil(filteredRows.length / rowsPerPage); // Adjust for filtered rows
    if (currentPage < 1) currentPage = 1;
    if (currentPage > totalPages) currentPage = totalPages;

    // Hide all rows and then display the appropriate ones for the current page
    filteredRows.forEach((row, index) => {
      row.style.display = (index >= (currentPage - 1) * rowsPerPage && index < currentPage * rowsPerPage) ? "" : "none";
    });

    // Update page info text
    document.getElementById("pageInfo").innerText = `Page ${currentPage} of ${totalPages}`;
    updatePageControls(); // Update page control buttons
  }

  // Function to go to the previous page
  function prevPage() {
    if (currentPage > 1) {
      currentPage--;
      updatePagination();
    }
  }

  // Function to go to the next page
  function nextPage() {
    const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
    if (currentPage < totalPages) {
      currentPage++;
      updatePagination();
    }
  }

  // Function to update the pagination controls (Previous, Next)
  function updatePageControls() {
    const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
    document.getElementById("prevBtn").disabled = currentPage === 1;
    document.getElementById("nextBtn").disabled = currentPage === totalPages;
  }

  // On page load, apply the saved filter from localStorage
  window.addEventListener('DOMContentLoaded', function() {
    const savedUserType = localStorage.getItem('selectedUserType') || 'all';
    document.querySelector(`#${savedUserType}Radio`).checked = true;

    // Apply the filter to the rows based on the saved userType
    filterRows();
  });

  // Call this on page load to initialize the table view
  document.addEventListener("DOMContentLoaded", function() {
    updatePagination(); // Initialize the table with current page and filter
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
