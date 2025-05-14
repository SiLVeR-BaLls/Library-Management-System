<?php
include '../../config.php';

$idno = $_SESSION['librarian']['IDno'];

$attendanceQuery = "   SELECT a.ID, a.IDno, u.Fname, u.Sname, a.TIMEIN, a.TIMEOUT, a.LOGDATE, a.STATUS , u.U_type
    FROM attendance a
    JOIN users_info u ON a.IDno = u.IDno
    ORDER BY a.LOGDATE DESC, a.TIMEIN DESC";
$query = $conn->query($attendanceQuery);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Attendance Records</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0-alpha.1/dist/tailwind.min.css" rel="stylesheet">
    <style>
        #attendanceTableWrapper {
            background-color: white;
            padding: 20px;
            height: 100%;
            overflow-y: auto;
            color: black;
        }
    </style>
</head>

<body class="bg-gray-50">

    <div class="flex items-center justify-center bg-gray-200 p-4 shadow-md">
        <a href="../index.php">
            <img src="../../../Registration/pic/logo wu.png" alt="Logo" class="h-12 w-12 mr-4">
        </a>
        <strong class="text-lg font-semibold text-gray-800">Digital Library Management System</strong>
    </div>

    <div class="container mx-auto">
        <div class="flex justify-center items-center flex-wrap min-h-[80vh] pt-8">
            <div class="w-full md:w-full p-4 bg-white rounded-lg shadow-lg">
                <h2 class="text-xl font-medium text-gray-600 mb-4">Attendance Records</h2>

                <div class="flex justify-center items-center w-full md:w-auto mb-4">
                    <div class="radio-input flex rounded-lg border-2 border-gray-200 bg-gray-100 overflow-hidden ">
                        <label class="flex-1 text-center px-4 py-2 font-semibold text-black cursor-pointer transition-all hover:bg-blue-300">
                            <input type="radio" name="userType" value="all" id="allRadio" class="hidden" checked>
                            <span>All</span>
                        </label>
                        <label class="flex-1 text-center px-4 py-2 font-semibold text-black cursor-pointer transition-all hover:bg-blue-300">
                            <input type="radio" name="userType" value="staff" id="staffRadio" class="hidden">
                            <span>Staff</span>
                        </label>
                        <label class="flex-1 text-center px-4 py-2 font-semibold text-black cursor-pointer transition-all hover:bg-blue-300">
                            <input type="radio" name="userType" value="student" id="studentRadio" class="hidden">
                            <span>Student</span>
                        </label>
                        <label class="flex-1 text-center px-4 py-2 font-semibold text-black cursor-pointer transition-all hover:bg-blue-300">
                            <input type="radio" name="userType" value="faculty" id="facultyRadio" class="hidden">
                            <span>Faculty</span>
                        </label>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
                    <input type="text" id="searchInput" placeholder="Search..." class="w-full md:w-1/2 p-2 border rounded-lg" onkeyup="renderTable()">
                    <select id="searchCategory" class="w-full md:w-1/4 p-2 border rounded-lg">
                        <option value="IDno">User ID</option>
                        <option value="Fname">First Name</option>
                        <option value="Sname">Last Name</option>
                    </select>
                    <input type="date" id="dateInput" class="w-full md:w-1/4 p-2 border rounded-lg" onchange="renderTable()">
                    <button id="resetButton" class="bg-gray-500 text-white py-2 px-4 rounded-lg" onclick="resetFilters()">Reset</button>
                </div>

                <table id="attendanceTable" class="min-w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-gray-700">User ID</th>
                            <th class="px-4 py-2 text-gray-700">First Name</th>
                            <th class="px-4 py-2 text-gray-700">Last Name</th>
                            <th class="px-4 py-2 text-gray-700">Time In</th>
                            <th class="px-4 py-2 text-gray-700">Log Date</th>
                            <th class="px-4 py-2 text-gray-700">Time Out</th>
                            <th class="px-4 py-2 text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php while ($row = $query->fetch_assoc()) { ?>
                            <tr class="border-b user-row" data-user-type="<?php echo htmlspecialchars($row['U_type']); ?>">
                                <td class="px-4 py-2"><?php echo $row['IDno']; ?></td>
                                <td class="px-4 py-2"><?php echo $row['Fname']; ?></td>
                                <td class="px-4 py-2"><?php echo $row['Sname']; ?></td>
                                <td class="px-4 py-2"><?php echo $row['TIMEIN']; ?></td>
                                <td class="px-4 py-2"><?php echo date('d/m/Y', strtotime($row['LOGDATE'])); ?></td>
                                <td class="px-4 py-2"><?php echo $row['TIMEOUT']; ?></td>
                                <td class="px-4 py-2"><?php echo $row['STATUS'] == 1 ? 'Out' : 'In'; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <div id="pagination" class="flex justify-center mt-4">
                    <button id="prevBtn" onclick="prevPage()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-l" disabled>Previous</button>
                    <span id="pageInfo" class="px-4 py-2 bg-white border">Page 1</span>
                    <button id="nextBtn" onclick="nextPage()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-r">Next</button>
                </div>

                <button class="bg-green-500 text-white py-2 px-4 rounded-lg mt-4" onclick="Export()">Export to Excel</button>
            </div>
        </div>
    </div>

    <script>
        function Export() {
            if (confirm("Confirm to export attendance as Excel file?")) {
                window.open("export.php", '_blank');
            }
        }

        const rows = Array.from(document.querySelectorAll("#tableBody tr"));
        const rowsPerPage = 10;
        let currentPage = 1;

        document.querySelectorAll("input[name='userType']").forEach(radio => {
            radio.addEventListener("change", renderTable);
        });

        function renderTable() {
            const userType = document.querySelector("input[name='userType']:checked").value;
            const searchValue = document.getElementById("searchInput").value.toLowerCase();
            const searchCategory = document.getElementById("searchCategory").value;
            const dateValue = document.getElementById("dateInput").value;

            const filteredRows = rows.filter(row => {
                const rowUserType = row.dataset.userType.toLowerCase();
                const cells = row.querySelectorAll("td");
                const idNo = cells[0].textContent.toLowerCase();
                const fName = cells[1].textContent.toLowerCase();
                const sName = cells[2].textContent.toLowerCase();
                const logDateText = cells[4].textContent;
                const logDateParts = logDateText.split('/'); // format is dd/mm/yyyy
                const logDate = `${logDateParts[2]}-${logDateParts[1]}-${logDateParts[0]}`; // convert to YYYY-MM-DD for comparison

                let matchesUserType = false;
                if (userType === "all") {
                    matchesUserType = true;
                } else if (userType === "staff") {
                    matchesUserType = (rowUserType === "admin" || rowUserType === "librarian");
                } else {
                    matchesUserType = (rowUserType === userType);
                }

                let matchesSearch = true;
                if (searchValue) {
                    if (searchCategory === "IDno") {
                        matchesSearch = idNo.includes(searchValue);
                    } else if (searchCategory === "Fname") {
                        matchesSearch = fName.includes(searchValue);
                    } else if (searchCategory === "Sname") {
                        matchesSearch = sName.includes(searchValue);
                    }
                }

                let matchesDate = true;
                if (dateValue) {
                    matchesDate = (logDate === dateValue);
                }

                return matchesUserType && matchesSearch && matchesDate;
            });

            // Pagination
            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = startIndex + rowsPerPage;

            rows.forEach(row => row.style.display = "none"); // hide all first
            filteredRows.slice(startIndex, endIndex).forEach(row => row.style.display = "");

            document.getElementById("pageInfo").textContent = `Page ${currentPage} of ${Math.ceil(filteredRows.length / rowsPerPage)}`;

            document.getElementById("prevBtn").disabled = (currentPage === 1);
            document.getElementById("nextBtn").disabled = (endIndex >= filteredRows.length);
        }

        function resetFilters() {
            document.getElementById("searchInput").value = "";
            document.getElementById("searchCategory").value = "IDno";
            document.getElementById("dateInput").value = "";
            document.getElementById("allRadio").checked = true;
            currentPage = 1;
            renderTable();
        }

        function nextPage() {
            currentPage++;
            renderTable();
        }

        function prevPage() {
            currentPage--;
            renderTable();
        }

        renderTable();
    </script>

</body>

</html>