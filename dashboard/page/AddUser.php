<?php
include '../config.php';
?>

<title>Add User</title>

<?php

  // Check connection
  if ($conn->connect_error) {
      $message = "Connection failed: " . $conn->connect_error;
      $message_type = "error";
      $gender = $Sname = $username = ""; // Empty defaults
  } else {
      $message = "";
      $message_type = "";
      $gender = $Sname = $username = ""; // Empty defaults

      // 1. Handle saving max_year for course
      if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_max_year'])) {
          $courseIdToUpdate = $_POST['course_id'];
          $newMaxValue = $_POST['new_value'];

          $updateQuery = "UPDATE Course SET max_year = ? WHERE id = ?";
          $stmt = $conn->prepare($updateQuery);
          if ($stmt) {
              $stmt->bind_param("ii", $newMaxValue, $courseIdToUpdate);
              if ($stmt->execute()) {
                  echo "<p style='color: green;'>Max year saved successfully for course ID: " . $courseIdToUpdate . "</p>";
              } else {
                  echo "<p style='color: red;'>Error saving max year for course ID: " . $courseIdToUpdate . " - " . $stmt->error . "</p>";
              }
              $stmt->close();
          } else {
              echo "<p style='color: red;'>Error preparing statement: " . $conn->error . "</p>";
          }
      }

      // 2. Add Program
      if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['newProgram'])) {
          $newProgram = trim($_POST['newProgram']);
          if (!empty($newProgram)) {
              $checkProgramQuery = "SELECT * FROM Program WHERE name = ?";
              $stmt = $conn->prepare($checkProgramQuery);
              $stmt->bind_param("s", $newProgram);
              $stmt->execute();
              $result = $stmt->get_result();
              if ($result->num_rows > 0) {
                  $message = "Program name already exists!";
                  $message_type = "error";
              } else {
                  $insertProgramQuery = "INSERT INTO Program (name) VALUES (?)";
                  $stmt = $conn->prepare($insertProgramQuery);
                  if ($stmt) {
                      $stmt->bind_param("s", $newProgram);
                      $stmt->execute();
                      $stmt->close();
                      $message = "Program added successfully!";
                      $message_type = "success";
                  }
              }
          }
          header("Location: " . $_SERVER['PHP_SELF']);
          exit();
      }

      // 3. Add Department
      if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['newDepartment'])) {
          $newDepartment = trim($_POST['newDepartment']);
          if (!empty($newDepartment)) {
              $checkDepartmentQuery = "SELECT * FROM Department WHERE name = ?";
              $stmt = $conn->prepare($checkDepartmentQuery);
              $stmt->bind_param("s", $newDepartment);
              $stmt->execute();
              $result = $stmt->get_result();
              if ($result->num_rows > 0) {
                  $message = "Department name already exists!";
                  $message_type = "error";
              } else {
                  $insertDepartmentQuery = "INSERT INTO Department (name) VALUES (?)";
                  $stmt = $conn->prepare($insertDepartmentQuery);
                  if ($stmt) {
                      $stmt->bind_param("s", $newDepartment);
                      $stmt->execute();
                      $stmt->close();
                      $message = "Department added successfully!";
                      $message_type = "success";
                  }
              }
          }
          header("Location: " . $_SERVER['PHP_SELF']);
          exit();
      }

      // 4. Add Course
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
                  $message = "Course name already exists for the selected program!";
                  $message_type = "error";
              } else {
                  $insertCourseQuery = "INSERT INTO Course (name, program_id) VALUES (?, ?)";
                  $stmt = $conn->prepare($insertCourseQuery);
                  if ($stmt) {
                      $stmt->bind_param("si", $newCourse, $programId);
                      if ($stmt->execute()) {
                          $stmt->close();
                          $message = "Course added successfully!";
                          $message_type = "success";
                      } else {
                          $message = "Database error: " . $stmt->error;
                          $message_type = "error";
                      }
                  } else {
                      $message = "Error preparing statement: " . $conn->error;
                      $message_type = "error";
                  }
              }
          } else {
              $message = "Course name and program must be selected.";
              $message_type = "error";
          }
          header("Location: " . $_SERVER['PHP_SELF']);
          exit();
      }

      // 5. Remove Entity
      if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_id']) && isset($_POST['remove_type'])) {
          $removeId = $_POST['remove_id'];
          $removeType = $_POST['remove_type'];
          $success = false;
          $message = "";
          $message_type = "";

          switch ($removeType) {
              case 'program':
                  $deleteQuery = "DELETE FROM Program WHERE id = ?";
                  break;
              case 'department':
                  $deleteQuery = "DELETE FROM Department WHERE id = ?";
                  break;
              case 'course':
                  $deleteQuery = "DELETE FROM Course WHERE id = ?";
                  break;
              default:
                  $message = "Invalid remove type.";
                  $message_type = "error";
                  header("Location: " . $_SERVER['PHP_SELF']);
                  exit();
          }

          $stmt = $conn->prepare($deleteQuery);
          if ($stmt) {
              $stmt->bind_param("i", $removeId);
              if ($stmt->execute()) {
                  $success = true;
                  $message = ucfirst($removeType) . " removed successfully.";
                  $message_type = "success";
              } else {
                  $message = "Error removing " . $removeType . ": " . $stmt->error;
                  $message_type = "error";
              }
              $stmt->close();
          } else {
              $message = "Error preparing statement: " . $conn->error;
              $message_type = "error";
          }

          header("Location: " . $_SERVER['PHP_SELF']);
          exit();
      }

      // 6. Fetch Programs
      $programs = [];
      $result = $conn->query("SELECT id, name FROM Program");
      if ($result) {
          while ($row = $result->fetch_assoc()) {
              $programs[] = $row;
          }
      }

      // 7. Fetch Departments
      $departments = [];
      $result = $conn->query("SELECT id, name FROM Department");
      if ($result) {
          while ($row = $result->fetch_assoc()) {
              $departments[] = $row;
          }
      }

      // 8. Fetch Courses
      $courses = [];
      $result = $conn->query("SELECT Course.id, Course.name, Course.max_year, Program.name AS program_name, Course.program_id FROM Course JOIN Program ON Course.program_id = Program.id");
      if ($result) {
          while ($row = $result->fetch_assoc()) {
              $courses[] = $row;
          }
      }

      // 9. Insert new user data
      if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['newProgram']) && !isset($_POST['newDepartment']) && !isset($_POST['newCourse']) && !isset($_POST['remove_id']) && !isset($_POST['save_max_year'])) {
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
          $U_Type = $_POST['U_Type'] ?? '';
          $college = $_POST['college'] ?? '';
          $course = $_POST['course'] ?? '';
          $yrLVL = $_POST['yrLVL'] ?? '';
          $personnel_type = $_POST['personnel_type'] ?? '';
          $A_LVL = '3';
          $status_details = 'active';
          $status_log = 'approved';
          $photo = ''; // Optional, depending on form

          // Check if IDno already exists
          $stmt = $conn->prepare("SELECT * FROM users_info WHERE IDno = ?");
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
              $sql = "INSERT INTO users_info (IDno, Fname, Sname, Mname, Ename, gender, photo, DOB, college, course, yrLVL, A_LVL, status_details, personnel_type, username, password, U_Type, status_log, email, contact) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = $conn->prepare($sql);
              if ($stmt === false) {
                  die('MySQL prepare error: ' . $conn->error);
              }

              $stmt->bind_param("ssssssssssssssssssss", $IDno, $Fname, $Sname, $Mname, $Ename, $gender, $photo, $DOB, $college, $course, $yrLVL, $A_LVL, $status_details, $personnel_type, $username, $password, $U_Type, $status_log, $email, $contact);
              $stmt->execute();

              $message = "Registration successful!";
              $message_type = "success";
          }

          $stmt->close();
          header("Location: " . $_SERVER['PHP_SELF']);
          exit();
      }
  }
?>


<!-- Main Content Area with Sidebar and BrowseBook Section -->
<div class="flex ">
  <!-- Sidebar Section -->
          <?php include $sidebars[$userType] ?? ''; ?>
  <!-- BrowseBook Content Section -->
  <div class="flex-grow ">
    <!-- Header at the Top -->
    <?php include 'include/header.php'; ?>

    <?php include 'include/AddUserTable.php'; ?>


    <!-- Footer at the Bottom -->
    <footer>
      <?php include 'include/footer.php'; ?>
    </footer>
  </div>
</div>
<script src="../../Registration/js/script.js"></script>