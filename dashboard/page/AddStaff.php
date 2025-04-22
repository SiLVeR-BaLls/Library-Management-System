<?php
include '../config.php';

if ($conn->connect_error) {
    echo "Connection failed.";
    exit();
} else {
    $message = "";
    $message_type = "";

    // Ensure no output is sent before header() calls
    ob_start();

    // Fetch Colleges
    $colleges = [];
    $result = $conn->query("SELECT id, name FROM College");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $colleges[] = $row;
        }
    }

    // Fetch Departments
    $departments = [];
    $result = $conn->query("SELECT id, name FROM Department");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row;
        }
    }

    // Fetch Courses
    $courses = [];
    $result = $conn->query("SELECT Course.id, Course.name, Course.max_year, Course.college_id, College.name AS college_name FROM Course JOIN College ON Course.college_id = College.id");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }
    } else {
        error_log("Error fetching courses: " . $conn->error); // Log any database errors
    }

    // Ensure the courses array is valid and safely encoded for JavaScript
    $jsonCourses = json_encode($courses, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    if ($jsonCourses === false) {
        error_log("JSON Encode Error: " . json_last_error_msg());
        $jsonCourses = '[]'; // Fallback to an empty array
    }
    ?>
    <script>
      const courses = <?php echo $jsonCourses; ?>;
    </script>
    <?php

    // Insert new staff data
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $Fname = $_POST['Fname'] ?? '';
        $Sname = $_POST['Sname'] ?? '';
        $Mname = $_POST['Mname'] ?? '';
        $Ename = $_POST['Ename'] ?? 'N/A';
        $gender = $_POST['gender'] ?? '';
        $DOB = $_POST['DOB'] ?? '';
        $email = $_POST['email'] ?? '';
        $contact = $_POST['contact'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $IDno = $_POST['IDno'] ?? '';
        $department = $_POST['department'] ?? '';
        $personnel_type = $_POST['personnel_type'] ?? '';
        $A_LVL = '3';
        $status_details = 'active';
        $status_log = 'approved';
        $U_Type = 'librarian';
        $photo = ''; // Optional, depending on form

        // Check if IDno already exists
        $stmt = $conn->prepare("SELECT * FROM users_info WHERE IDno = ?");
        if ($stmt === false) {
            echo "<script>
                    alert('Error: Unable to prepare statement.');
                    window.location.href = 'AddStaff.php?error=statement';
                  </script>";
            exit();
        }
        $stmt->bind_param("s", $IDno);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Duplication error alert
            echo "<script>
                    alert('Duplication Error: IDno \"$IDno\" already exists.');
                    window.location.href = 'AddStaff.php?error=duplicate';
                  </script>";
            exit();
        } else {
            $sql = "INSERT INTO users_info (IDno, Fname, Sname, Mname, Ename, gender, photo, DOB, department, A_LVL, status_details, personnel_type, username, password, U_Type, status_log, email, contact) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                echo "<script>
                        alert('Error: Unable to prepare statement.');
                        window.location.href = 'AddStaff.php?error=statement';
                      </script>";
                exit();
            }

            $stmt->bind_param("ssssssssssssssssss", $IDno, $Fname, $Sname, $Mname, $Ename, $gender, $photo, $DOB, $department, $A_LVL, $status_details, $personnel_type, $username, $password, $U_Type, $status_log, $email, $contact);
            if ($stmt->execute()) {
                // Success alert and redirect
                echo "<script>
                        alert('Success: Staff \"$Fname $Sname\" added successfully.');
                        window.location.href = 'AddStaff.php?success=true';
                      </script>";
                exit();
            } else {
                echo "<script>
                        alert('Error: Unable to add staff.');
                        window.location.href = 'AddStaff.php?error=add';
                      </script>";
                exit();
            }
        }

        $stmt->close();
    }

    ob_end_flush(); // Ensure output buffering is flushed
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

    <?php include 'include/AddStaffTable.php'; ?>

    <!-- Footer at the Bottom -->
    <footer>
      <?php include 'include/footer.php'; ?>
    </footer>
  </div>
</div>
<script>
  // Function to toggle password visibility
  function togglePasswordVisibility(inputId, toggleId) {
    const input = document.getElementById(inputId);
    const toggle = document.getElementById(toggleId);
    if (input.type === "password") {
      input.type = "text";
      toggle.textContent = "🙈"; // Change icon to indicate visibility
    } else {
      input.type = "password";
      toggle.textContent = "📚"; // Change icon back to indicate hidden
    }
  }
</script>
<script src="../../Registration/js/script.js"></script>