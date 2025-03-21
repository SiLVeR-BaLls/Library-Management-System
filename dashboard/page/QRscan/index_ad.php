<?php
    include '../../config.php'; // Include the database connection file

    $idno = $_SESSION['admin']['IDno'];
    $attendanceQuery = "SELECT a.ID, a.IDno, u.Fname, u.Sname, a.TIMEIN, a.TIMEOUT, a.LOGDATE, a.STATUS
                        FROM attendance a
                        JOIN users_info u ON a.IDno = u.IDno
                        ORDER BY a.LOGDATE DESC";
    $query = $conn->query($attendanceQuery);
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
          <!-- Search Inputs -->
<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
    <input 
        type="text" 
        id="searchInput" 
        placeholder="Search..." 
        class="w-full md:w-1/2 p-2 border rounded-lg" 
        onkeyup="searchTable()"
    >
    <input 
        type="date" 
        id="dateInput" 
        class="w-full md:w-1/3 p-2 border rounded-lg" 
        onchange="filterByDate()"
    >
    <button id="resetButton" class="bg-gray-500 text-white py-2 px-4 mt-4 rounded-lg">Reset</button>

</div>

            
            <table id="attendanceTable" class="min-w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-gray-700">User ID</th>
                        <th class="px-4 py-2 text-gray-700">First Name</th>
                        <th class="px-4 py-2 text-gray-700">Last Name</th>
                        <th class="px-4 py-2 text-gray-700">Time In</th>
                        <th class="px-4 py-2 text-gray-700">Time Out</th>
                        <th class="px-4 py-2 text-gray-700">Log Date</th>
                        <th class="px-4 py-2 text-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php while ($row = $query->fetch_assoc()) { ?>
                    <tr class="border-b">
                      
                        <td class="px-4 py-2">
                            <?php echo $row['IDno']; ?>
                        </td>
                        <td class="px-4 py-2">
                            <?php echo $row['Fname']; ?>
                        </td>
                        <td class="px-4 py-2">
                            <?php echo $row['Sname']; ?>
                        </td>
                        <td class="px-4 py-2">
                            <?php echo $row['TIMEIN']; ?>
                        </td>
                        <td class="px-4 py-2">
                            <?php echo $row['TIMEOUT']; ?>
                        </td>
                        <td class="px-4 py-2">
                            <?php echo date('d/m/Y', strtotime($row['LOGDATE'])); ?>
                        </td>
                        <td class="px-4 py-2">
                            <?php echo $row['STATUS'] == 1 ? 'Out' : 'In'; ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div id="pagination" class="flex justify-center mt-4">
                <button onclick="prevPage()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-l">Previous</button>
                <span id="pageInfo" class="px-4 py-2 bg-white border">Page 1</span>
                <button onclick="nextPage()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-r">Next</button>
            </div>
            <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded-lg mt-4" onclick="Export()">Export to
                Excel</button>
        </div>
    </div>

    <!-- Overlay to close sidebar when clicked outside -->
    <div id="overlay" class="overlay" onclick="closeNav()"></div>
    <!-- JavaScript for exporting data -->
    <script>
        function Export() {
            var conf = confirm("Please confirm if you wish to proceed in exporting the attendance into Excel file");
            if (conf == true) {
                window.open("export.php", '_blank');
            }
        }
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

        document.addEventListener("DOMContentLoaded", function () {
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

     let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
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

    <script>
        // JavaScript to handle pagination and search

        let currentPage = 1;
        const rowsPerPage = 10; // Number of rows per page

        function displayTablePage(page) {
            const table = document.getElementById("attendanceTable");
            const rows = table.getElementsByTagName("tr");
            const totalPages = Math.ceil((rows.length - 1) / rowsPerPage);
            if (page < 1) page = 1;
            if (page > totalPages) page = totalPages;

            for (let i = 1; i < rows.length; i++) {
                rows[i].style.display = (i > page * rowsPerPage || i <= (page - 1) * rowsPerPage) ? "none" : "";
            }
            document.getElementById("pageInfo").innerText = `Page ${page} of ${totalPages}`;
        }

        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                displayTablePage(currentPage);
            }
        }

        function nextPage() {
            const table = document.getElementById("attendanceTable");
            const rows = table.getElementsByTagName("tr");
            const totalPages = Math.ceil((rows.length - 1) / rowsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                displayTablePage(currentPage);
            }
        }

        function searchTable() {
            const input = document.getElementById("searchInput").value.toUpperCase();
            const table = document.getElementById("attendanceTable");
            const rows = table.getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) {
                let cells = rows[i].getElementsByTagName("td");
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    if (cells[j].innerText.toUpperCase().includes(input)) {
                        match = true;
                        break;
                    }
                }
                rows[i].style.display = match ? "" : "none";
            }
        }
        
        function searchTable() {
            const input = document.getElementById("searchInput").value.toUpperCase();
            const table = document.getElementById("attendanceTable");
            const rows = table.getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) {
                let cells = rows[i].getElementsByTagName("td");
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    if (cells[j].innerText.toUpperCase().includes(input)) {
                        match = true;
                        break;
                    }
                }
                rows[i].style.display = match ? "" : "none";
            }
        }
        function filterByDate() {
    const inputDate = document.getElementById("dateInput").value; // Get the date from the input
    const table = document.getElementById("attendanceTable");
    const rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) {
        let cells = rows[i].getElementsByTagName("td");
        let dateCell = cells[6]; // Assuming the date is in the 7th column (LOGDATE)

        if (dateCell) {
            const rowDate = dateCell.innerText.trim(); // Get the date from the row

            // Convert the row date (d/m/Y) to the format YYYY-MM-DD
            const [day, month, year] = rowDate.split('/'); // Assuming format is dd/mm/yyyy
            const formattedRowDate = `${year}-${month}-${day}`;

            // Compare the formatted row date with the input date
            if (inputDate === formattedRowDate) {
                rows[i].style.display = ""; // Show the row if the date matches
            } else {
                rows[i].style.display = "none"; // Hide the row if the date doesn't match
            }
        }
    }
}
document.getElementById("resetButton").addEventListener("click", function() {
    // Clear search input and date input
    document.getElementById("searchInput").value = "";
    document.getElementById("dateInput").value = "";

    // Reset table display
    const table = document.getElementById("attendanceTable");
    const rows = table.getElementsByTagName("tr");

    // Show all rows
    for (let i = 1; i < rows.length; i++) {
        rows[i].style.display = ""; // Show all rows
    }
});

        // Display initial page
        displayTablePage(currentPage);
    </script>


</body>

</html>