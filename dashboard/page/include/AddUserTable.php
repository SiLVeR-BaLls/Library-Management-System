<?php
  // Configuration
  $db_host = 'localhost';
  $db_username = 'root';
  $db_password = '';
  $db_name = 'lms';

  // Create connection
  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    $message = "Connection failed: " . $conn->connect_error;
    $message_type = "error";
    $gender = $Sname = $username = ""; // Empty defaults
  } else {
    $message = "";
    $message_type = "";
    $gender = $Sname = $username = ""; // Empty defaults

    // Ensure the Program table exists
    $createTableQuery = "
      CREATE TABLE IF NOT EXISTS Program (
          id INT AUTO_INCREMENT PRIMARY KEY,
          name VARCHAR(255) NOT NULL UNIQUE
      )";
    $conn->query($createTableQuery);

    // Ensure the Department table exists
    $createDepartmentTableQuery = "
      CREATE TABLE IF NOT EXISTS Department (
          id INT AUTO_INCREMENT PRIMARY KEY,
          name VARCHAR(255) NOT NULL UNIQUE
      )";
    $conn->query($createDepartmentTableQuery);

    // Ensure the Course table exists
    $createCourseTableQuery = "
      CREATE TABLE IF NOT EXISTS Course (
          id INT AUTO_INCREMENT PRIMARY KEY,
          name VARCHAR(255) NOT NULL,
          program_id INT NOT NULL,
          FOREIGN KEY (program_id) REFERENCES Program(id) ON DELETE CASCADE
      )";
    $conn->query($createCourseTableQuery);

    // Handle adding a new Program
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['newProgram'])) {
      $newProgram = trim($_POST['newProgram']);
      if (!empty($newProgram)) {
        $checkProgramQuery = "SELECT * FROM Program WHERE name = ?";
        $stmt = $conn->prepare($checkProgramQuery);
        $stmt->bind_param("s", $newProgram);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
          $duplicateError = "Program name already exists!";
        } else {
          $insertProgramQuery = "INSERT INTO Program (name) VALUES (?)";
          $stmt = $conn->prepare($insertProgramQuery);
          if ($stmt) {
            $stmt->bind_param("s", $newProgram);
            $stmt->execute();
            $stmt->close();
          }
        }
      }
    }

    // Handle adding a new Department
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['newDepartment'])) {
      $newDepartment = trim($_POST['newDepartment']);
      if (!empty($newDepartment)) {
        $checkDepartmentQuery = "SELECT * FROM Department WHERE name = ?";
        $stmt = $conn->prepare($checkDepartmentQuery);
        $stmt->bind_param("s", $newDepartment);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
          $duplicateError = "Department name already exists!";
        } else {
          $insertDepartmentQuery = "INSERT INTO Department (name) VALUES (?)";
          $stmt = $conn->prepare($insertDepartmentQuery);
          if ($stmt) {
            $stmt->bind_param("s", $newDepartment);
            $stmt->execute();
            $stmt->close();
          }
        }
      }
    }


    // ... (Your database connection code remains the same)

    // Fetch Programs
    $programs = [];
    $fetchProgramsQuery = "SELECT id, name FROM Program";
    $result = $conn->query($fetchProgramsQuery);
    if ($result) {
      while ($row = $result->fetch_assoc()) {
        $programs[] = $row;
      }
    }

    // Fetch Departments
    $departments = [];
    $fetchDepartmentsQuery = "SELECT id, name FROM Department";
    $result = $conn->query($fetchDepartmentsQuery);
    if ($result) {
      while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
      }
    }

    // Fetch Courses
    $courses = [];
    $fetchCoursesQuery = "SELECT Course.id, Course.name, Program.name AS program_name FROM Course JOIN Program ON Course.program_id = Program.id";
    $result = $conn->query($fetchCoursesQuery);
    if ($result) {
      while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
      }
    }

    // ... (Your code for adding new data and user registration)


    // Handle adding a new Course
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['newCourse'])) {
      $newCourse = trim($_POST['newCourse']);
      $programId = $_POST['programId'] ?? null;
      if (!empty($newCourse) && !empty($programId)) {
        $checkCourseQuery = "SELECT * FROM Course WHERE name = ? AND program_id = ?";
        $stmt = $conn->prepare($checkCourseQuery);
        $stmt->bind_param("si", $newCourse, $programId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
          $duplicateError = "Course name already exists for the selected program!";
        } else {
          $insertCourseQuery = "INSERT INTO Course (name, program_id) VALUES (?, ?)";
          $stmt = $conn->prepare($insertCourseQuery);
          if ($stmt) {
            $stmt->bind_param("si", $newCourse, $programId);
            $stmt->execute();
            $stmt->close();
          }
        }
      }
    }

    // Fetch all programs for the dropdown
    $programs = [];
    $fetchProgramsQuery = "SELECT id, name FROM Program";
    $result = $conn->query($fetchProgramsQuery);
    if ($result) {
      while ($row = $result->fetch_assoc()) {
        $programs[] = $row;
      }
    }

    // Fetch all departments for the dropdown
    $departments = [];
    $fetchDepartmentsQuery = "SELECT id, name FROM Department";
    $result = $conn->query($fetchDepartmentsQuery);
    if ($result) {
      while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
      }
    }

    // Fetch all courses for the dropdown
    $courses = [];
    $fetchCoursesQuery = "SELECT Course.id, Course.name, Program.name AS program_name, Course.program_id FROM Course 
                            JOIN Program ON Course.program_id = Program.id";
    $result = $conn->query($fetchCoursesQuery);
    if ($result) {
      while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
      }
    }

    // Insert user data into tables
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Get POST data
      $Fname = $_POST['Fname'] ?? '';
      $Sname = $_POST['Sname'] ?? '';
      $Mname = $_POST['Mname'] ?? '';
      $Ename = $_POST['Ename'] ?? 'N/A';  // Default value
      $gender = $_POST['gender'] ?? '';  // Fixed name
      $DOB = $_POST['DOB'] ?? '';
      $email = $_POST['email'] ?? '';
      $contact = $_POST['contact'] ?? '';
      $username = $_POST['username'] ?? '';
      $password = $_POST['password'] ?? ''; // No hashing
      $IDno = $_POST['IDno'] ?? '';
      $U_Type = $_POST['U_Type'] ?? '';  // User Type
      $college = $_POST['college'] ?? '';  // Default value
      $course = $_POST['course'] ?? '';  // Default value
      $yrLVL = $_POST['yrLVL'] ?? '';  // Default value
      $personnel_type = $_POST['personnel_type'] ?? '';  // Default value
      $A_LVL = '3';
      $status_details = 'active';
      $status_log = 'approved';

      // Check if IDno already exists
      $query = "SELECT * FROM users_info WHERE IDno = ?";
      $stmt = $conn->prepare($query);
      if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
      }
      $stmt->bind_param("s", $IDno);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        $message = "Error: IDno already exists";
        $message_type = "error";
      } else {
        // Insert into users table
        // Insert query with 21 placeholders (one for each column)
        $sql = "INSERT INTO users_info (IDno, Fname, Sname, Mname, Ename, gender, photo, DOB, college, course, yrLVL, A_LVL, status_details, personnel_type, username, password, U_Type, status_log, email, contact) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
          die('MySQL prepare error: ' . $conn->error);
        }

        // Correctly bind 21 parameters
        $stmt->bind_param("ssssssssssssssssssss", $IDno, $Fname, $Sname, $Mname, $Ename, $gender, $photo, $DOB, $college, $course, $yrLVL, $A_LVL, $status_details, $personnel_type, $username, $password, $U_Type, $status_log, $email, $contact);
        $stmt->execute();

        $message = "Registration successful!";
        $message_type = "success";
      }

      // Close the statement
      $stmt->close();
    }

    // Close the connection
    $conn->close();
  }
?>

<div class="min-h-screen bg-[#f2f2f2]  justify-center items-center px-10">
  <center class="bg-green-100 p-4 rounded-md shadow-md">
    <h1 class="text-2xl font-bold">Add User</h1>
  </center>
  <form id="registration-form" action="" method="post" class="space-y-8">
    <!-- User Information Section -->
    <fieldset class="space-y-5 p-4 border border-gray-300 rounded-md">
      <legend class="text-lg font-semibold">User Information</legend>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
          <div>
            <label for="Fname" class="text-sm font-medium">Firstname</label>
            <input id="Fname" name="Fname" type="text" placeholder="Firstname"
              class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
          </div>
          <div>
            <label for="Sname" class="text-sm font-medium">Surname</label>
            <input id="Sname" name="Sname" type="text" placeholder="Surname"
              class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
          </div>
        </div>
        <div class="space-y-4">
          <div>
            <label for="Mname" class="text-sm font-medium">Middle Name</label>
            <input id="Mname" name="Mname" type="text" placeholder="Middle Name"
              class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
          </div>
          <div>
            <label for="Ename" class="text-sm font-medium">Extension</label>
            <input id="Ename" name="Ename" type="text" placeholder="Enter Extension"
              class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
          </div>
        </div>
      </div>
    </fieldset>

    <!-- Personal Information Section -->
    <fieldset class="space-y-5 p-4 border border-gray-300 rounded-md">
      <legend class="text-lg font-semibold">Personal Information</legend>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label for="Sex" class="text-sm font-medium">Sex</label>
          <select id="Sex" name="Sex" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
            <option value="" disabled selected>Select Sex</option>
            <option value="m">Male</option>
            <option value="f">Female</option>
          </select>
        </div>
        <div>
          <label for="DOB" class="text-sm font-medium">Birthdate</label>
          <input id="DOB" name="DOB" type="date" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
        </div>
      </div>
    </fieldset>

    <!-- Contact Information Section -->
    <fieldset class="space-y-5 p-4 border border-gray-300 rounded-md">
      <legend class="text-lg font-semibold">Contact Information</legend>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label for="contact" class="text-sm font-medium">Contact No. 1</label>
          <input id="contact" name="contact" type="text" placeholder="09*********" pattern="^\d{11}$"
            title="Please enter a valid 11-digit number" class="w-full mt-1 border-gray-300 rounded-md shadow-sm"
            required>
        </div>
        <div>
          <label for="email" class="text-sm font-medium">Email 1</label>
          <input id="email" name="email" type="email" placeholder="sample@gmail.com"
            class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
        </div>
      </div>
    </fieldset>

    <!-- Account and Site Information Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Account Information -->
      <fieldset class="space-y-5 p-4 border border-gray-300 rounded-md">
        <legend class="text-lg font-semibold">Account Information</legend>
        <div class="space-y-4">
          <div>
            <label for="IDno" class="text-sm font-medium">ID Number</label>
            <input id="IDno" name="IDno" type="text" placeholder="Enter ID (if Manual)"
              class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
          </div>
          <div>
            <label for="U_Type" class="text-sm font-medium">User Type</label>
            <select id="U_Type" name="U_Type" onchange="toggleUserType()"
              class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
              <option value="student" selected>Student</option>
              <option value="faculty">Faculty</option>
            </select>
          </div>
        </div>
      </fieldset>

      <!-- Site Information -->
      <fieldset class="space-y-5 p-4 border border-gray-300 rounded-md">
        <legend class="text-lg font-semibold">Site Information</legend>

        <div id="user-info" class="space-y-4">

          <!-- Program -->
          <div id="program-group">
            <label for="Program" class="text-sm font-medium">Program</label>
            <select id="Program" name="Program" class="form-select w-full" onchange="filterCourses()">
              <option value="" disabled selected>Select Program</option>
              <?php foreach ($programs as $program): ?>
                <option value="<?php echo $program['id']; ?>">
                  <?php echo htmlspecialchars($program['name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
            <a href="#" onclick="openPopup()" class="text-blue-600 underline">Add</a>
          </div>

          <!-- Department -->
          <div id="department-group" class="hidden">
            <label for="college" class="text-sm font-medium">Department</label>
            <select id="college" name="college" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
              <option value="" disabled selected>Select Department</option>
              <?php foreach ($departments as $department): ?>
                <option value="<?php echo $department['id']; ?>">
                  <?php echo htmlspecialchars($department['name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
            <a href="#" onclick="openDepartmentPopup(event)" class="text-blue-600 underline">Add</a>
          </div>

          <!-- Personnel Type -->
          <div id="personnel-group" class="hidden">
            <label for="personnel_type" class="text-sm font-medium">Personnel Type</label>
            <select id="personnel_type" name="personnel_type" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
              <option value="" disabled selected>Select Personnel Type</option>
              <option value="Teaching Personnel">Teaching Personnel</option>
              <option value="Non-Teaching Personnel">Non-Teaching Personnel</option>
            </select>
          </div>

          <!-- Course (Hidden for Faculty) -->
          <div id="course-group">
            <label for="course" class="text-sm font-medium">Course</label>
            <select id="course" name="course" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
              <option value="" disabled selected>Choose Program First</option>
            </select>
            <a href="#" id="addCourseLink" onclick="openCoursePopup(event)" class="pointer-events-none text-gray-400">Add</a>
          </div>

          <!-- Year and Section (Hidden for Faculty) -->
          <div id="yrLVL-group">
            <label for="yrLVL" class="text-sm font-medium">Year and Section</label>
            <select id="yrLVL" name="yrLVL" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
              <option value="" disabled selected>Select Year and Section</option>
              <!-- Dynamic Year/Section Options -->
              <?php for ($year = 1; $year <= 5; $year++): ?>
                <?php foreach (["A", "B", "C", "D"] as $section): ?>
                  <option value="<?php echo $year . ' ' . $section; ?>">
                    <?php echo $year . ' ' . $section; ?>
                  </option>
                <?php endforeach; ?>
              <?php endfor; ?>
            </select>
          </div>

        </div>
      </fieldset>

      <script>
        function toggleUserType() {
          const userType = document.getElementById('U_Type').value;
          const programGroup = document.getElementById('program-group');
          const departmentGroup = document.getElementById('department-group');
          const personnelGroup = document.getElementById('personnel-group');
          const courseGroup = document.getElementById('course-group');
          const yrLvlGroup = document.getElementById('yrLVL-group');

          if (userType === 'faculty') {
            // Show Department and Personnel Type, hide Program, Course, and Year/Section
            programGroup.style.display = 'none';
            departmentGroup.style.display = 'block';
            personnelGroup.style.display = 'block';
            courseGroup.style.display = 'none';
            yrLvlGroup.style.display = 'none';
          } else {
            // Show Program, Course, and Year/Section, hide Department and Personnel Type
            programGroup.style.display = 'block';
            departmentGroup.style.display = 'none';
            personnelGroup.style.display = 'none';
            courseGroup.style.display = 'block';
            yrLvlGroup.style.display = 'block';
          }
        }

        // Initial check on page load
        window.onload = toggleUserType;

        function filterCourses() {
          const selectedProgramId = document.getElementById("Program").value;
          const courseDropdown = document.getElementById("course");
          const addCourseLink = document.getElementById("addCourseLink");

          // Clear the course dropdown
          courseDropdown.innerHTML = "";

          if (!selectedProgramId) {
            // If no program is selected, show "Choose Program First"
            const defaultOption = document.createElement("option");
            defaultOption.value = "";
            defaultOption.textContent = "Choose Program First";
            defaultOption.disabled = true;
            defaultOption.selected = true;
            courseDropdown.appendChild(defaultOption);

            // Disable the "Add Course" link
            addCourseLink.classList.add("pointer-events-none", "text-gray-400");
            addCourseLink.classList.remove("text-blue-600", "underline");
            return;
          }

          // Enable the "Add Course" link
          addCourseLink.classList.remove("pointer-events-none", "text-gray-400");
          addCourseLink.classList.add("text-blue-600", "underline");

          // Add a default "Select Course" option
          const defaultOption = document.createElement("option");
          defaultOption.value = "";
          defaultOption.textContent = "Select Course";
          defaultOption.disabled = true;
          defaultOption.selected = true;
          courseDropdown.appendChild(defaultOption);

          // Show only courses that match the selected program
          const courseOptions = document.querySelectorAll("#course-options option");
          courseOptions.forEach(option => {
            if (option.dataset.programId === selectedProgramId) {
              courseDropdown.appendChild(option.cloneNode(true));
            }
          });
        }

        function openCoursePopup(event) {
          const selectedProgramId = document.getElementById("Program").value;
          if (!selectedProgramId) {
            alert("Please select a program before adding a course.");
            return;
          }
          if (event) event.preventDefault(); // Prevent default link behavior
          document.getElementById("coursePopupContainer").classList.remove("hidden");
        }
      </script>

      <!-- Hidden Course Options -->
      <div id="course-options" class="hidden">
        <?php foreach ($courses as $course): ?>
          <option value="<?php echo $course['id']; ?>" data-program-id="<?php echo $course['program_id']; ?>">
            <?php echo htmlspecialchars($course['name']); ?>
          </option>
        <?php endforeach; ?>
      </div>


    </div>

    <!-- Password Section -->
    <fieldset class="space-y-5 p-4 border border-gray-300 rounded-md">
      <legend class="text-lg font-semibold">Password Information</legend>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label for="username" class="text-sm font-medium">Username</label>
          <input id="username" name="username" type="text" placeholder="Enter Username"
            class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div class="relative">
          <label for="password" class="text-sm font-medium">Password</label>
          <input id="password" name="password" type="password" placeholder="Enter Password"
            class="w-full mt-1 border-gray-300 rounded-md shadow-sm pr-10" required>
          <!-- Password toggle button (on the right side of the input) -->
          <span id="password-toggle"
            class="show-password absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer"
            onclick="togglePasswordVisibility('password', 'password-toggle')">📚</span>
        </div>
      </div>
    </fieldset>

    <!-- Submit Button -->
    <div class="text-center">
      <button type="submit" id="submitBtn"
        class="px-6 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:ring-4 focus:ring-blue-300">Submit</button>
    </div>
  </form>

  <button id="displayDataButton" class="px-6 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:ring-4 focus:ring-blue-300">
  Display Data
  </button>
  <div id="displayDataModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden" style="z-index: 10000;">
    <div class="modal-content bg-white p-6 rounded-lg shadow-lg w-full max-w-5xl">
        <h2 class="text-xl font-bold mb-4">Added Data</h2>

        <div class="flex space-x-4">
            <div class="w-full">
                <h3 class="text-lg font-semibold mb-2">Departments</h3>
                <div id="filteredDepartmentsList" class="overflow-y-auto max-h-96">
                    <?php foreach ($departments as $department): ?>
                        <div class="border-b py-2 flex justify-between items-center" data-department-id="<?php echo $department['id']; ?>">
                            <?php echo htmlspecialchars($department['name']); ?>
                            <button class="bg-red-500 text-white px-2 py-1 rounded" data-type="department" data-id="<?php echo $department['id']; ?>" onclick="confirmRemove(this)">-</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="w-full">
                <h3 class="text-lg font-semibold mb-2">Programs</h3>
                <div id="filteredProgramsList" class="overflow-y-auto max-h-96">
                    <?php foreach ($programs as $program): ?>
                        <div class="border-b py-2 flex justify-between items-center" data-program-id="<?php echo $program['id']; ?>">
                            <?php echo htmlspecialchars($program['name']); ?>
                            <button class="bg-red-500 text-white px-2 py-1 rounded" data-type="program" data-id="<?php echo $program['id']; ?>" onclick="confirmRemove(this)">-</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="w-full">
                <h3 class="text-lg font-semibold mb-2">Courses</h3>
                <div class="mb-4">
                    <label for="courseFilter" class="block text-sm font-medium">Filter by Program:</label>
                    <select id="courseFilter" class="w-full border rounded px-3 py-2">
                        <?php foreach ($programs as $program): ?>
                            <option value="<?php echo $program['id']; ?>"><?php echo htmlspecialchars($program['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div id="filteredCoursesList" class="overflow-y-auto max-h-96">
                    <?php foreach ($courses as $course): ?>
                        <div class="border-b py-2 flex justify-between items-center" data-program-id="<?php echo $course['program_id']; ?>">
                            <?php echo htmlspecialchars($course['name']); ?>
                            <button class="bg-red-500 text-white px-2 py-1 rounded" data-type="course" data-id="<?php echo $course['id']; ?>" onclick="confirmRemove(this)">-</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="confirmationModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden" style="z-index: 10001;">
    <div class="modal-content bg-white p-6 rounded-lg shadow-lg w-96">
        <p>Are you sure you want to remove this item?</p>
        <div class="flex justify-end mt-4">
            <button id="confirmRemoveButton" class="bg-red-500 text-white px-4 py-2 rounded mr-2">Remove</button>
            <button id="cancelRemoveButton" class="bg-gray-400 text-white px-4 py-2 rounded">Cancel</button>
        </div>
    </div>
</div>
</div>

<style>
  /* ...existing styles... */
  #popupContainer {
    z-index: 9999;
    /* Ensure the popup is above other elements like the sidebar */
  }
</style>

<!-- Program Popup -->
<div id="popupContainer" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
  <div id="popupBox" class="bg-white p-6 rounded-lg shadow-lg w-80">
    <h2 class="text-xl font-bold mb-2">Add New Program</h2>
    <form id="programForm" method="post" action="">
      <input type="text" name="newProgram" id="popupInput" class="w-full border rounded px-3 py-2 mb-4" placeholder="Enter Program Name" required>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closePopup(event)" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 transition">Cancel</button>
        <button type="button" onclick="submitProgramForm(event)" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Confirm</button>
      </div>
    </form>
  </div>
</div>

<!-- Department Popup -->
<div id="departmentPopupContainer" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
  <div id="departmentPopupBox" class="bg-white p-6 rounded-lg shadow-lg w-80">
    <h2 class="text-xl font-bold mb-2">Add New Department</h2>
    <form id="departmentForm" method="post" action="">
      <input type="text" name="newDepartment" id="departmentPopupInput" class="w-full border rounded px-3 py-2 mb-4" placeholder="Enter Department Name" required>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeDepartmentPopup(event)" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 transition">Cancel</button>
        <button type="button" onclick="submitDepartmentForm(event)" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Confirm</button>
      </div>
    </form>
  </div>
</div>

<!-- Course Popup -->
<div id="coursePopupContainer" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
  <div id="coursePopupBox" class="bg-white p-6 rounded-lg shadow-lg w-80">
    <h2 class="text-xl font-bold mb-2">Add New Course</h2>
    <form id="courseForm" method="post" action="">
      <div class="mb-4">
        <label for="programId" class="block text-sm font-medium">Program</label>
        <select name="programId" id="programId" class="w-full border rounded px-3 py-2" required>
          <option value="" disabled selected>Select Program</option>
          <?php foreach ($programs as $program): ?>
            <option value="<?php echo $program['id']; ?>">
              <?php echo htmlspecialchars($program['name']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <input type="text" name="newCourse" id="coursePopupInput" class="w-full border rounded px-3 py-2 mb-4" placeholder="Enter Course Name" required>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeCoursePopup(event)" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 transition">Cancel</button>
        <button type="button" onclick="submitCourseForm(event)" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Confirm</button>
      </div>
    </form>
  </div>
</div>

<!-- Duplicate Error Popup -->
<?php if (isset($duplicateError)): ?>
  <div id="duplicateErrorPopup" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-80">
      <h2 class="text-xl font-bold mb-4 text-red-600">Error</h2>
      <p class="text-gray-700 mb-4"><?php echo htmlspecialchars($duplicateError); ?></p>
      <div class="flex justify-end">
        <button onclick="closeDuplicateErrorPopup()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Close</button>
      </div>
    </div>
  </div>
  <script>
    function closeDuplicateErrorPopup() {
      document.getElementById("duplicateErrorPopup").style.display = "none";
    }
  </script>
<?php endif; ?>

<script>
  function openPopup(event) {
    if (event) event.preventDefault(); // Prevent default link behavior
    document.getElementById("popupContainer").classList.remove("hidden");
  }

  function closePopup(event) {
    if (event) event.preventDefault(); // Prevent default button behavior
    document.getElementById("popupContainer").classList.add("hidden");
  }

  function submitProgramForm(event) {
    if (event) event.preventDefault(); // Prevent default form submission
    document.getElementById("programForm").submit(); // Submit the form programmatically
  }

  function openDepartmentPopup(event) {
    if (event) event.preventDefault(); // Prevent default link behavior
    document.getElementById("departmentPopupContainer").classList.remove("hidden");
  }

  function closeDepartmentPopup(event) {
    if (event) event.preventDefault(); // Prevent default button behavior
    document.getElementById("departmentPopupContainer").classList.add("hidden");
  }

  function submitDepartmentForm(event) {
    if (event) event.preventDefault(); // Prevent default form submission
    document.getElementById("departmentForm").submit(); // Submit the form programmatically
  }

  function openCoursePopup(event) {
    const selectedProgramId = document.getElementById("Program").value;
    if (!selectedProgramId) {
      alert("Please select a program before adding a course.");
      return;
    }
    if (event) event.preventDefault(); // Prevent default link behavior
    document.getElementById("coursePopupContainer").classList.remove("hidden");
  }

  function closeCoursePopup(event) {
    if (event) event.preventDefault(); // Prevent default button behavior
    document.getElementById("coursePopupContainer").classList.add("hidden");
  }

  function submitCourseForm(event) {
    if (event) event.preventDefault(); // Prevent default form submission
    document.getElementById("courseForm").submit(); // Submit the form programmatically
  }

  // Close program popup if clicked outside the box
  document.getElementById("popupContainer").addEventListener("click", function(event) {
    if (event.target === this) closePopup(event);
  });

  // Close department popup if clicked outside the box
  document.getElementById("departmentPopupContainer").addEventListener("click", function(event) {
    if (event.target === this) closeDepartmentPopup(event);
  });

  // Close course popup if clicked outside the box
  document.getElementById("coursePopupContainer").addEventListener("click", function(event) {
    if (event.target === this) closeCoursePopup(event);
  });
</script>




<script>
document.addEventListener('DOMContentLoaded', function() {
    // Display Data Modal
    document.getElementById('displayDataButton').addEventListener('click', function() {
        document.getElementById('displayDataModal').classList.remove('hidden');
    });

    // Close Modal (using a generic class)
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                this.classList.add('hidden');
            }
        });
    });

    // Course Filter
    document.getElementById('courseFilter').addEventListener('change', function() {
        const selectedProgramId = this.value;
        const courseDivs = document.querySelectorAll('#filteredCoursesList div');
        courseDivs.forEach(div => {
            const divProgramId = div.dataset.programId;
            if (divProgramId === selectedProgramId) {
                div.style.display = '';
            } else {
                div.style.display = 'none';
            }
        });
    });
});

function confirmRemove(button) {
    const itemType = button.dataset.type;
    const itemId = button.dataset.id;

    if (window.confirm('Are you sure you want to remove this item?')) {
        // Add your PHP/AJAX removal logic here
        console.log('Remove', itemType, itemId);

        // Example removal from the display:
        const divToRemove = document.querySelector(`[data-<span class="math-inline">\{itemType\}\-id\="</span>{itemId}"]`);
        if (divToRemove) {
            divToRemove.remove();
        }
    }
}
</script>