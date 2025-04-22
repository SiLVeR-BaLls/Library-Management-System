<?php
    include '../../config.php'; // Include the database connection file

    $idno = $_SESSION['admin']['IDno'];

    $attendanceQuery = "
    SELECT a.ID, a.IDno, u.Fname, u.Sname, a.TIMEIN, a.TIMEOUT, a.LOGDATE, a.STATUS , u.U_type
    FROM attendance a
    JOIN users_info u ON a.IDno = u.IDno
    ORDER BY a.LOGDATE DESC, a.TIMEIN DESC";

    $query = $conn->query($attendanceQuery);$query = $conn->query($attendanceQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>QR Code | Log in</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0-alpha.1/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Sidebar styles */
        #mySidebar {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            background-color: #111;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 0px;
            box-shadow: 4px 0px 6px rgba(0, 0, 0, 0.2);
        }

        #mySidebar a {
            padding: 8px 32px;
            text-decoration: none;
            font-size: 25px;
            color: #818181;
            display: block;
            transition: 0.3s;
        }

        #mySidebar a:hover {
            color: #f1f1f1;
        }

        #attendanceTableWrapper {
            display: none;
            background-color: white;
            padding: 20px;
            height: 100%;
            overflow-y: auto;
            color: black;
        }

        /* Overlay styling */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 999;
        }
    </style>
</head>

<body class="bg-gray-50 place-content-center">
    <!-- Header Section -->
    <div class="flex items-center justify-center bg-gray-200 p-4 shadow-md">
        <a href="../index.php">
            <img src="../../../Registration/pic/logo wu.png" alt="Logo" class="h-12 w-12 mr-4">
        </a>
        <strong class="text-lg font-semibold text-gray-800">Digital Library Management System</strong>
    </div>

    <!-- Main Content Area -->
    <div class="container mx-auto">
        <div class="flex justify-center items-center flex-wrap min-h-[80vh] pt-8">
            <!-- QR Code Scanner -->
            <div class="w-full md:w-5/6 p-4 bg-white rounded-lg shadow-lg" id="divvideo" style="height: 550px;">
                <video id="preview" class="w-full rounded-lg shadow-md"
                    style="height: 450px; object-fit: cover;"></video>
                <br>
                <button id="startCameraButton" class="bg-green-500 text-white py-1 px-2 rounded-lg mt-2">Start
                    Camera</button>
                <button id="stopCameraButton" class="bg-red-500 text-white py-1 px-2 rounded-lg mt-2 hidden">Stop
                    Camera</button>
            </div>

            <!-- QR Code Form -->
            <div class="w-full md:w-1/6 p-4">
                <button id="openSidebarButton" class="bg-blue-500 text-white py-2 px-4 m-4 rounded-lg">Attendance Record</button>
                <form id="qrForm" action="insert.php" method="post" class="bg-white p-6 rounded-lg shadow-lg">
                    <label for="studentID" class="text-lg text-gray-600">ENTER ID:</label>
                    <input type="text" name="studentID" id="text" placeholder="Enter ID"
                        class="w-full p-3 mt-2 border rounded-lg focus:ring-2 focus:ring-green-300" autofocus>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div id="attendanceTableWrapper">
        <h2 class="text-xl font-medium text-gray-600 mb-4">Attendance Records</h2>

<div class="flex justify-center items-center w-full md:w-auto mb-4">
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

    <!-- Overlay to close sidebar when clicked outside -->
    <div id="overlay" class="overlay" onclick="closeNav()"></div>
    <!-- JavaScript for exporting data -->
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
                const logDate = `${logDateParts[2]}-${logDateParts[1]}-${logDateParts[0]}`; // convert to yyyy-mm-dd for comparison

                let matchesUserType = (userType === "all" || rowUserType === userType);
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

    <!-- JavaScript -->
    <script>
        // Sidebar and overlay
        function openNav() {
            document.getElementById("mySidebar").style.width = "90%";
            document.getElementById("attendanceTableWrapper").style.display = "block";
            document.getElementById("overlay").style.display = "block";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
            document.getElementById("attendanceTableWrapper").style.display = "none";
            document.getElementById("overlay").style.display = "none";
        }
        document.getElementById("openSidebarButton").addEventListener("click", openNav);

        document.addEventListener("DOMContentLoaded", function() {
            // Sidebar and overlay functions
            function openNav() {
                document.getElementById("mySidebar").style.width = "90%";
                document.getElementById("attendanceTableWrapper").style.display = "block";
                document.getElementById("overlay").style.display = "block";
            }

            function closeNav() {
                document.getElementById("mySidebar").style.width = "0";
                document.getElementById("attendanceTableWrapper").style.display = "none";
                document.getElementById("overlay").style.display = "none";
            }
            document.getElementById("openSidebarButton").addEventListener("click", openNav);

            let scanner = new Instascan.Scanner({
                video: document.getElementById('preview')
            });
            let activeCamera = null;

            function startCamera() {
                Instascan.Camera.getCameras().then(function(cameras) {
                    if (cameras.length > 0) {
                        activeCamera = cameras[0];
                        scanner.start(activeCamera);
                        document.getElementById('startCameraButton').style.display = 'none';
                        document.getElementById('stopCameraButton').style.display = 'block';
                    } else {
                        alert('No cameras found');
                    }
                }).catch(function(e) {
                    console.error(e);
                });
            }

            document.getElementById('startCameraButton').addEventListener('click', function() {
                if (!activeCamera) {
                    startCamera();
                }
            });

            document.getElementById('stopCameraButton').addEventListener('click', function() {
                if (activeCamera) {
                    scanner.stop();
                    activeCamera = null;
                    document.getElementById('startCameraButton').style.display = 'block';
                    document.getElementById('stopCameraButton').style.display = 'none';
                }
            });

            scanner.addListener('scan', function(c) {
                document.getElementById('text').value = c;
                document.forms[0].submit();
            });

            // Start the camera by default
            startCamera();
        });
    </script>



</body>

</html>