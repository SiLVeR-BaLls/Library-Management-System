<?php
    include 'config.php';

    if ($conn->connect_error) {
        echo "Connection failed.";
        exit();
    } else {
        $message = "";
        $message_type = "";
        $gender = $Sname = $username = ""; // Empty defaults

        // Ensure no output is sent before header() calls
        ob_start();

        // Handle saving max_year for course
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_max_year'])) {
            // Clear any previous output
            ob_clean();

            $courseIdToUpdate = $_POST['course_id'];
            $newMaxValue = $_POST['new_value'];

            // Fetch course and college details
            $courseQuery = "SELECT Course.name AS course_name, College.name AS college_name 
                            FROM Course 
                            JOIN College ON Course.college_id = College.id 
                            WHERE Course.id = ?";
            $stmt = $conn->prepare($courseQuery);
            if ($stmt) {
                $stmt->bind_param("i", $courseIdToUpdate);
                $stmt->execute();
                $result = $stmt->get_result();
                $courseDetails = $result->fetch_assoc();
                $stmt->close();
            }

            $updateQuery = "UPDATE Course SET max_year = ? WHERE id = ?";
            $stmt = $conn->prepare($updateQuery);
            if ($stmt) {
                $stmt->bind_param("ii", $newMaxValue, $courseIdToUpdate);
                if ($stmt->execute()) {
                    echo "Max year saved successfully for course \"" . htmlspecialchars($courseDetails['course_name']) . 
                        "\" in college \"" . htmlspecialchars($courseDetails['college_name']) . "\".";
                } else {
                    echo "Error saving max year for course \"" . htmlspecialchars($courseDetails['course_name']) . 
                        "\" in college \"" . htmlspecialchars($courseDetails['college_name']) . "\" - " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
            exit(); // Stop further script execution
        }

        // Add College (previously Program)
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['newProgram'])) {
            $newCollege = trim($_POST['newProgram']);
            if (!empty($newCollege)) {
                $checkCollegeQuery = "SELECT name FROM College WHERE name = ?";
                $stmt = $conn->prepare($checkCollegeQuery);
                if ($stmt) {
                    $stmt->bind_param("s", $newCollege);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result && $result->num_rows > 0) {
                        echo "<script>alert('Duplication error in College: \"$newCollege\" already exists.');</script>";
                    } else {
                        $insertCollegeQuery = "INSERT INTO College (name) VALUES (?)";
                        $stmt = $conn->prepare($insertCollegeQuery);
                        if ($stmt) {
                            $stmt->bind_param("s", $newCollege);
                            $stmt->execute();
                            $stmt->close();
                            echo "<script>alert('Success: College \"$newCollege\" added successfully.');</script>";
                        }
                    }
                } else {
                    echo "<script>alert('Error: Unable to prepare statement.');</script>";
                }
            }
            echo "<script>window.history.back();</script>"; // Redirect back to the previous page
            exit();
        }

        // Add Department
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['newDepartment'])) {
            $newDepartment = trim($_POST['newDepartment']);
            if (!empty($newDepartment)) {
                $checkDepartmentQuery = "SELECT name FROM Department WHERE name = ?";
                $stmt = $conn->prepare($checkDepartmentQuery);
                if ($stmt) {
                    $stmt->bind_param("s", $newDepartment);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result && $result->num_rows > 0) {
                        echo "<script>alert('Duplication error in Department: \"$newDepartment\" already exists.');</script>"; // Use alert for duplication
                    } else {
                        $insertDepartmentQuery = "INSERT INTO Department (name) VALUES (?)";
                        $stmt = $conn->prepare($insertDepartmentQuery);
                        if ($stmt) {
                            $stmt->bind_param("s", $newDepartment);
                            $stmt->execute();
                            $stmt->close();
                            echo "<script>alert('Success: Department \"$newDepartment\" added successfully.');</script>"; // Use alert for success
                        }
                    }
                } else {
                    echo "<script>alert('Error: Unable to prepare statement.');</script>"; // Use alert for errors
                }
            }
            echo "<script>window.history.back();</script>"; // Redirect back to the previous page
            exit();
        }

        // Add Course
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['newCourse'])) {
            $newCourse = trim($_POST['newCourse']);
            $collegeId = $_POST['programId'] ?? null;
            if (!empty($newCourse) && !empty($collegeId)) {
                $checkCourseQuery = "SELECT name FROM Course WHERE name = ? AND college_id = ?";
                $stmt = $conn->prepare($checkCourseQuery);
                if ($stmt) {
                    $stmt->bind_param("si", $newCourse, $collegeId);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result && $result->num_rows > 0) {
                        echo "<script>
                                alert('Duplication error in Course: \"$newCourse\" already exists for the selected college.');
                            </script>";
                    } else {
                        $insertCourseQuery = "INSERT INTO Course (name, college_id) VALUES (?, ?)";
                        $stmt = $conn->prepare($insertCourseQuery);
                        if ($stmt) {
                            $stmt->bind_param("si", $newCourse, $collegeId);
                            $stmt->execute();
                            $stmt->close();
                            echo "<script>
                                    alert('Success: Course \"$newCourse\" added successfully.');
                                    document.getElementById('courseForm').reset(); // Clear the form
                                </script>";
                        }
                    }
                } else {
                    echo "<script>
                            alert('Error: Unable to prepare statement.');
                        </script>";
                }
            } else {
                echo "<script>
                        alert('Error: Course name and college must be selected.');
                    </script>";
            }
            echo "<script>window.history.back();</script>"; // Redirect back to the previous page
            exit();
        }

        // Remove Entity
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_id']) && isset($_POST['remove_type'])) {
            $removeId = intval($_POST['remove_id']); // Ensure the ID is an integer
            $removeType = $_POST['remove_type'];

            switch ($removeType) {
                case 'college':
                    $deleteQuery = "DELETE FROM College WHERE id = ?";
                    break;
                case 'course':
                    $deleteQuery = "DELETE FROM Course WHERE id = ?";
                    break;
                case 'department':
                    $deleteQuery = "DELETE FROM Department WHERE id = ?";
                    break;
                default:
                    echo "Invalid remove type.";
                    exit();
            }

            $stmt = $conn->prepare($deleteQuery);
            if ($stmt) {
                $stmt->bind_param("i", $removeId);
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        echo "Success: $removeType removed successfully.";
                    } else {
                        echo "Error: Unable to remove $removeType. It may be referenced by other data.";
                    }
                } else {
                    echo "Error: Unable to remove $removeType. " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Error: Unable to prepare statement. " . $conn->error;
            }
            exit();
        }

        // Fetch Colleges (previously Programs)
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

    // Insert new user data
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
        $college = $_POST['college'] ?? ''; // Directly use the selected college name
        $course = $_POST['course'] ?? ''; // Use the selected course name
        $department = $_POST['department'] ?? ''; // Directly use the selected department name
        $yrLVL = $_POST['yrLVL'] ?? '';
        $personnel_type = $_POST['personnel_type'] ?? '';
        $A_LVL = '3';
        $status_details = 'active';
        $status_log = 'approved';
        $photo = ''; // Optional, depending on form

        // Check if IDno already exists
        $stmt = $conn->prepare("SELECT * FROM users_info WHERE IDno = ?");
        if ($stmt === false) {
            echo "<script>alert('Error: Unable to prepare statement.');</script>";
            exit();
        }
        $stmt->bind_param("s", $IDno);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Duplication error alert
            echo "<script>alert('Duplication Error: IDno \"$IDno\" already exists.');</script>";
        } else {
            $sql = "INSERT INTO users_info (IDno, Fname, Sname, Mname, Ename, gender, photo, DOB, college, course, department, yrLVL, A_LVL, status_details, personnel_type, username, password, U_Type, status_log, email, contact) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                echo "<script>alert('Error: Unable to prepare statement.');</script>";
                exit();
            }

            // Bind the course name instead of the course ID
            $stmt->bind_param("sssssssssssssssssssss", $IDno, $Fname, $Sname, $Mname, $Ename, $gender, $photo, $DOB, $college, $course, $department, $yrLVL, $A_LVL, $status_details, $personnel_type, $username, $password, $U_Type, $status_log, $email, $contact);
            if ($stmt->execute()) {
                // Success alert
                echo "<script>alert('Success: User \"$Fname $Sname\" added successfully.');</script>";
                echo "<script>window.location.href = 'AddUser.php?success=true';</script>";
            } else {
                echo "<script>alert('Error: Unable to add user.');</script>";
            }
        }

        $stmt->close();
    }

    ob_end_flush(); // Ensure output buffering is flushed
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

    <?php include 'page/include/AddUserTable.php'; ?>

        <footer class="mt-auto">
            <?php include 'include/footer.php'; ?>
        </footer>
    </div>
</div>