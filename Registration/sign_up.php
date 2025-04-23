<?php
// Configuration
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'lms';

// Create connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    echo "Connection failed.";
    exit();
}

// Fetch Colleges
$colleges = [];
$result = $conn->query("SELECT id, name FROM College ORDER BY name ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $colleges[] = $row;
    }
} else {
    echo "Error fetching colleges: " . $conn->error;
}

// Fetch Departments
$departments = [];
$result = $conn->query("SELECT id, name FROM Department ORDER BY name ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
} else {
    echo "Error fetching departments: " . $conn->error;
}

// Fetch Courses
$courses = [];
$result = $conn->query("SELECT Course.id, Course.name, Course.college_id, Course.max_year, College.name AS college_name 
                        FROM Course 
                        JOIN College ON Course.college_id = College.id 
                        ORDER BY Course.name ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
} else {
    echo "Error fetching courses: " . $conn->error;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Library Registration</title>
  <link rel="stylesheet" href="css/sign_up1.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
  <header>
    <p><strong> LIBRARY MANAGEMENT SYSTEM</strong></p>

  </header>

  <main>
    <form id="registration-form" action="php/SignConnect.php" method="post" class="container">
      <div class="plus">
        <div class="form-step form-step-active">
          <p class="top"><b>User Information</b></p>

          <div class="group1">
            <div class="text-group">
              <label for="Sname">Surname</label>
              <input id="Sname" name="Sname" class="box" type="text" placeholder="Surname" required>
            </div>
            <div class="text-group">
              <label for="Fname">Firstname</label>
              <input id="Fname" name="Fname" class="box" type="text" placeholder="Firstname" required>
            </div>
            <div class="text-group">
              <label for="Mname">Middle Name</label>
              <input id="Mname" name="Mname" class="box" type="text" placeholder="Middle Name" required>
            </div>
          </div>

          <div class="group-1">
            <div class="group-box">
              <p class="tile">Basic Information</p>
              <div class="text-group">
                <label for="gender">Sex</label>
                <select class="box" name="gender" id="gender" required>
                  <option value="" disabled selected>Select Sex</option>
                  <option value="m">Male</option>
                  <option value="f">Female</option>
                  <option value="o">Other</option>
                </select>
              </div>
              <div class="text-group">
                <label for="DOB">Birthdate</label>
                <input id="DOB" name="DOB" type="date" class="box" required
                  max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>" 
                  value="<?php echo date('Y-m-d', strtotime('-18 years')); ?>"> <!-- Set default date -->
                <small style="color:gray;">Birthdate cannot be 18 - below!</small>
              </div>
              <div class="text-group">
                <label for="Ename">Extension</label>
                <input class="box" name="Ename" id="Ename" placeholder="Enter Extension">
              </div>
            </div>

            <!-- Address Section -->
            <div class="group-box">
              <p class="tile">Address</p>
              <div class="text-group">
                <label for="municipality">Municipality/City</label>
                <input id="municipality" name="municipality" class="box" type="text"
                  placeholder="Enter Municipality/City">
              </div>

              <div class="text-group">
                <label for="barangay">Barangay</label>
                <input id="barangay" name="barangay" class="box" type="text" placeholder="Enter Barangay">
              </div>
              <div class="text-group">
                <label for="province">Province</label>
                <input id="province" name="province" class="box" type="text" placeholder="Enter Province">
              </div>
            </div>

            <!-- Contact Information Section -->
            <div class="group-box">
              <p class="tile">Contact</p>
              <div class="text-group">
                <label for="contact">Contact No.</label>
                <input id="contact" name="contact" class="box" type="tel" placeholder="09*********"
                  title="Please enter a valid 11-digit number" pattern="^09\d{9}$" required>
                <span id="contact-message"></span> <!-- Message for contact format validation -->
              </div>

              <div class="text-group">
                <label for="email">Email</label>
                <input id="email" name="email" class="box" type="email" placeholder="sample@gmail.com" required>
                <span id="email-message"></span> <!-- Message for email format validation -->
              </div>



            </div>
          </div>
        </div>


        <!-- siteinfo -->
        <div class="form-step">
          <p class="top"><b class="tile">Site Information</b></p>

          <div class="group-1">
            <div class="group-box">
              <p class="tile">Account Information</p>

              <div class="text-group">
                <label for="IDno">ID Number:</label>
                <input type="text" id="IDno" name="IDno" class="box" placeholder="Enter ID (if Manual)" required>
              </div>




              <div class="text-group">
                <label for="U_Type">User Type</label>
                <select class="box" name="U_Type" id="U_Type" onchange="toggleUserType()">
                  <option value="student" selected>Student</option>
                  <option value="faculty">Faculty</option>
                </select>
              </div>
            </div>

            <!-- Group container for Program, Course, Year & Section, and Personnel Type -->
            <div class="group-box" id="user-info" style="display: flex; flex-direction: column;">
              <p class="tile">User Information</p>


              <!-- College -->
              <div id="program-group">
                <label for="college">College</label>
                <div class="text-group">
                  <select id="college" name="college" class="box" >
                    <option value="" disabled selected>Select College</option>
                    <?php foreach ($colleges as $college): ?>
                      <option value="<?php echo htmlspecialchars($college['id']); ?>">
                        <?php echo htmlspecialchars($college['name']); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>


              <!-- Department -->
              <div id="department-group">
                <label for="department">Department</label>
                <div class="text-group">

                  <select id="department" name="department" class="box">
                    <option value="" disabled selected>Select Department</option>
                    <?php foreach ($departments as $department): ?>
                      <option value="<?php echo htmlspecialchars($department['name']); ?>">
                        <?php echo htmlspecialchars($department['name']); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <!-- Personnel Type -->
              <div id="personnel-group" class="hidden">
                <label for="personnel_type">Personnel Type</label>
                <div class="text-group">
                  <select id="personnel_type" name="personnel_type" class="box">
                    <option value="" disabled selected>Select Personnel Type</option>
                    <option value="Teaching Personnel">Teaching Personnel</option>
                    <option value="Non-Teaching Personnel">Non-Teaching Personnel</option>
                  </select>
                </div>
              </div>



              <!-- Course (Hidden for Faculty) -->
              <div id="course-group">
                <label for="course">Course</label>
                <div class="text-group">
                  <select id="course" name="course" class="box">
                    <option value="" disabled selected>Select Course</option>
                    <?php if (!empty($courses)): ?>
                      <?php foreach ($courses as $course): ?>
                        <option value="<?php echo htmlspecialchars($course['id']); ?>" data-college-id="<?php echo htmlspecialchars($course['college_id']); ?>" data-max-year="<?php echo htmlspecialchars($course['max_year']); ?>">
                          <?php echo htmlspecialchars($course['name']); ?>
                        </option>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <option value="" disabled>No courses available</option>
                    <?php endif; ?>
                  </select>
                </div>
              </div>

              <!-- Year and Section (Dynamic based on course table) -->
              <div id="yrLVL-group">
                <label for="yrLVL" class="text-sm font-medium">Year and Section</label>
                <div class="text-group">
                  <select id="yrLVL" name="yrLVL" class="box">
                    <option value="" disabled selected>Select Year and Section</option>
                  </select>
                </div>
              </div>


              <!-- Hidden Course Options -->
              <template id="course-options">
                <?php foreach ($courses as $course): ?>
                  <option value="<?php echo htmlspecialchars($course['name']); ?>" data-program-id="<?php echo htmlspecialchars($course['college_id']); ?>">
                    <?php echo htmlspecialchars($course['name']); ?>
                  </option>
                <?php endforeach; ?>
              </template>
            </div>
          </div>
        </div>




        <div class="form-step">
          <p class="top"><b>User Information</b></p>

          <div class="group-1">
            <div class="group-box">
              <p class="title">Account Information</p>

              <div class="text-group">
                <label for="username">Username</label>
                <input id="username" name="username" class="box" type="text" placeholder="Enter Username">
              </div>

              <div class="text-group">
                <label for="password">Password</label>
                <div class="pass">
                  <input id="password" name="password" class="box" type="password" placeholder="Ex.Johncruz@123'">
                  <div class="pup_upwarn">
                    <div>
                      Ex. Johncruz@123
                    </div>
                    <span id="password-toggle" class="show-password" onclick="togglePasswordVisibility('password', 'password-toggle')">📚</span>
                  </div>
                </div>
                <small id="password-message"></small>
              </div>


              <style>
                /* Popup container - can be anything you want */
                .popup {
                  position: relative;
                  display: inline-block;
                  cursor: pointer;
                  -webkit-user-select: none;
                  -moz-user-select: none;
                  -ms-user-select: none;
                  user-select: none;
                }

                /* The actual popup */
                .popup .popuptext {
                  visibility: hidden;
                  width: 160px;
                  background-color: #555;
                  color: #fff;
                  text-align: center;
                  border-radius: 6px;
                  padding: 8px 0;
                  position: absolute;
                  z-index: 1;
                  bottom: 125%;
                  left: 50%;
                  margin-left: -80px;
                }

                /* Popup arrow */
                .popup .popuptext::after {
                  content: "";
                  position: absolute;
                  top: 100%;
                  left: 50%;
                  margin-left: -5px;
                  border-width: 5px;
                  border-style: solid;
                  border-color: #555 transparent transparent transparent;
                }

                /* Toggle this class - hide and show the popup */
                .popup .show {
                  visibility: visible;
                  -webkit-animation: fadeIn 1s;
                  animation: fadeIn 1s;
                }

                /* Add animation (fade in the popup) */
                @-webkit-keyframes fadeIn {
                  from {
                    opacity: 0;
                  }

                  to {
                    opacity: 1;
                  }
                }

                @keyframes fadeIn {
                  from {
                    opacity: 0;
                  }

                  to {
                    opacity: 1;
                  }
                }

                .warning {
                  margin-left: 10px;
                  /* Moves the circle outside the input box */
                  background-color: #f2f2f2;
                  color: black;
                  font-weight: bold;
                  font-size: 14px;
                  width: 24px;
                  height: 24px;
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  border-radius: 50%;
                  border: 1px solid #ccc;
                  cursor: pointer;
                }
              </style>
              <div class="text-group">
                <label for="password-repeat">Repeat Password</label>
                <div class="pass">
                  <input id="password-repeat" name="password-repeat" class="box" type="password" placeholder="Repeat Password">
                  <span id="password-repeat-toggle" class="show-password" onclick="togglePasswordVisibility('password-repeat', 'password-repeat-toggle')">📚</span>
                </div>
                <small id="password-repeat-message"></small>
              </div>


            </div>
          </div>
        </div>



      </div>

      <div id="error-message" class="error"></div>

      <div class="button-container">
        <button type="button" class="button" id="prevBtn" disabled>Previous</button>
        <button type="button" class="button" id="nextBtn">Next</button>
        <button type="submit" class="button" id="submitBtn" style="display:none;">Submit</button>
      </div>
      <p>Do you have an account? <a href="log_in.php" class="but">Log In</a></p>
    </form>
  </main>
</body>

<script>
  function toggleUserType() {
    const userType = document.getElementById('U_Type').value;
    const programGroup = document.getElementById('program-group'); // College
    const departmentGroup = document.getElementById('department-group');
    const personnelGroup = document.getElementById('personnel-group');
    const courseGroup = document.getElementById('course-group');
    const yrLvlGroup = document.getElementById('yrLVL-group');

    if (userType === 'faculty') {
      // Show Department and Personnel Type, hide College, Course, and Year/Section
      if (programGroup) programGroup.style.display = 'none';
      if (courseGroup) courseGroup.style.display = 'none';
      if (yrLvlGroup) yrLvlGroup.style.display = 'none';
      if (departmentGroup) departmentGroup.style.display = 'block';
      if (personnelGroup) personnelGroup.style.display = 'block';
    } else if (userType === 'student') {
      // Show College, Course, and Year/Section, hide Department and Personnel Type
      if (programGroup) programGroup.style.display = 'block';
      if (courseGroup) courseGroup.style.display = 'block';
      if (yrLvlGroup) yrLvlGroup.style.display = 'block';
      if (departmentGroup) departmentGroup.style.display = 'none';
      if (personnelGroup) personnelGroup.style.display = 'none';
    } else {
      // Default state: hide all optional fields
      if (programGroup) programGroup.style.display = 'none';
      if (courseGroup) courseGroup.style.display = 'none';
      if (yrLvlGroup) yrLvlGroup.style.display = 'none';
      if (departmentGroup) departmentGroup.style.display = 'none';
      if (personnelGroup) personnelGroup.style.display = 'none';
    }
  }

  function filterCourses() {
    const selectedCollegeId = document.getElementById("college").value; // Get the selected college ID
    const courseDropdown = document.getElementById("course");

    // Clear the course dropdown
    courseDropdown.innerHTML = '<option value="" disabled selected>Select Course</option>';

    // Populate the course dropdown based on the selected college
    <?php foreach ($courses as $course): ?>
      if (selectedCollegeId === "<?php echo $course['college_id']; ?>") {
        const option = document.createElement("option");
        option.value = "<?php echo htmlspecialchars($course['id']); ?>"; // Use course ID as value
        option.textContent = "<?php echo htmlspecialchars($course['name']); ?>";
        option.setAttribute("data-max-year", "<?php echo htmlspecialchars($course['max_year']); ?>");
        courseDropdown.appendChild(option);
      }
    <?php endforeach; ?>

    // Clear the year and section dropdown when college changes
    const yrLvlDropdown = document.getElementById("yrLVL");
    yrLvlDropdown.innerHTML = '<option value="" disabled selected>Select Year and Section</option>';
  }

  function updateYearAndSection() {
    const courseDropdown = document.getElementById("course");
    const selectedCourseId = courseDropdown.value; // Get the selected course ID
    const yrLvlDropdown = document.getElementById("yrLVL");

    // Clear the year level dropdown
    yrLvlDropdown.innerHTML = '<option value="" disabled selected>Select Year and Section</option>';

    // Find the selected course's max_year
    const selectedCourse = courseDropdown.options[courseDropdown.selectedIndex];
    const maxYear = selectedCourse.getAttribute("data-max-year");

    // Populate year and section options dynamically
    if (selectedCourseId) {
      const maxYearValue = parseInt(maxYear, 10);

      // If max_year is 0, loop 1 to 4; if max_year is 1, loop 1 to 5
      const years = maxYearValue === 0 ? 4 : 5;

      for (let year = 1; year <= years; year++) {
        ["A", "B", "C", "D"].forEach(section => {
          const option = document.createElement("option");
          option.value = `${year}${section}`; // Format as 1A, 2B, etc.
          option.textContent = `${year}${section}`; // Display as 1A, 2B, etc.
          yrLvlDropdown.appendChild(option);
        });
      }
    }
  }

  // Attach event listeners
  document.getElementById("college").addEventListener("change", filterCourses);
  document.getElementById("course").addEventListener("change", updateYearAndSection);

  // Initial setup
  document.addEventListener("DOMContentLoaded", () => {
    toggleUserType();
    filterCourses();
  });
</script>

<script>
  function validateContact() {
    const contact = document.getElementById('contact').value;
    const contactMessage = document.getElementById('contact-message');
    const contactField = document.getElementById('contact');

    // Regular expression to match the contact number format: 11 digits starting with 09
    const contactRegex = /^09\d{9}$/;

    if (contactRegex.test(contact)) {
      contactField.classList.remove('invalid');
      contactField.classList.add('valid');
      contactMessage.textContent = "Contact number is valid";
      contactMessage.classList.remove('error-message');
      contactMessage.classList.add('success-message');
    } else {
      contactField.classList.remove('valid');
      contactField.classList.add('invalid');
      contactMessage.textContent = "Please enter a valid 11-digit contact number";
      contactMessage.classList.remove('success-message');
      contactMessage.classList.add('error-message');
    }
  }

  // Function to validate email format
  function validateEmail() {
    const email = document.getElementById('email').value;
    const emailMessage = document.getElementById('email-message');
    const emailField = document.getElementById('email');

    // Regular expression to match a standard email format
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

    if (emailRegex.test(email)) {
      emailField.classList.remove('invalid');
      emailField.classList.add('valid');
      emailMessage.textContent = "Email address is valid";
      emailMessage.classList.remove('error-message');
      emailMessage.classList.add('success-message');
    } else {
      emailField.classList.remove('valid');
      emailField.classList.add('invalid');
      emailMessage.textContent = "Please enter a valid email address";
      emailMessage.classList.remove('success-message');
      emailMessage.classList.add('error-message');
    }
  }

  // Add event listener for real-time contact number validation
  document.getElementById('contact').addEventListener('input', validateContact);

  // Add event listener for real-time email validation
  document.getElementById('email').addEventListener('input', validateEmail);

  // Add event listener to form submission to prevent submission if any field is invalid
  document.querySelector('form').addEventListener('submit', function(event) {
    const contact = document.getElementById('contact').value;
    const email = document.getElementById('email').value;

    // If contact or email is empty, show the respective error message
    if (!contact) {
      event.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'Contact is required',
        text: 'Please enter your contact number.',
        confirmButtonText: 'OK'
      });
    } else if (!email) {
      event.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'Email is required',
        text: 'Please enter your email address.',
        confirmButtonText: 'OK'
      });
    } else if (!/^09\d{9}$/.test(contact) || !/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/.test(email)) {
      event.preventDefault(); // Stop form submission
      Swal.fire({
        icon: 'error',
        title: 'Invalid Contact or Email Format',
        text: 'Please ensure both the contact number and email address are valid.',
        confirmButtonText: 'OK'
      });
    }
  });
</script>

<!-- // Function to validate ID number format -->
<script>
  function validateID() {
    const id = document.getElementById('IDno').value;
    const idField = document.getElementById('IDno');
    const userType = document.getElementById('U_Type').value;

    let idRegex;

    // Define regex based on user type
    if (userType === "student") {
      // Student ID format: yyyy-xxxx-X
      idRegex = /^\d{4}-\d{4}-[A-Za-z]{1}$/;
    } else if (userType === "faculty") {
      // Faculty ID format: flexible (e.g., xx-yyyy or any other format)
      idRegex = /^[A-Za-z0-9-]+$/;
    }

    if (!idRegex.test(id)) {
      idField.classList.add('invalid');
    } else {
      idField.classList.remove('invalid');
    }
  }

  // Add event listener for real-time ID validation
  document.getElementById('IDno').addEventListener('input', validateID);

  // Add event listener to form submission to prevent submission if ID format is invalid
  document.querySelector('form').addEventListener('submit', function(event) {
    const id = document.getElementById('IDno').value;
    const userType = document.getElementById('U_Type').value;

    let idRegex;

    // Define regex based on user type
    if (userType === "student") {
      idRegex = /^\d{4}-\d{4}-[A-Za-z]{1}$/;
    } else if (userType === "faculty") {
      idRegex = /^[A-Za-z0-9-]+$/;
    }

    // If ID is not in valid format, prevent form submission
    if (!idRegex.test(id)) {
      event.preventDefault(); // Stop form submission
      Swal.fire({
        icon: 'error',
        title: 'Invalid ID Format',
        text: userType === "student" ?
          'ID number must follow the format: yyyy-xxxx-X (year-code-letter)' : 'ID number must be alphanumeric and can include dashes.',
        confirmButtonText: 'OK'
      });
    }
  });
</script>

<!-- // When the user clicks on div, open the popup -->
<script>
  function myFunction() {
    var popup = document.getElementById("myPopup");
    popup.classList.toggle("show");
  }
</script>

<!-- // Function to validate password -->
<script>
  function validatePassword() {
    const password = document.getElementById('password').value;
    const passwordRepeat = document.getElementById('password-repeat').value;
    const passwordField = document.getElementById('password');
    const passwordRepeatField = document.getElementById('password-repeat');

    // Updated regex to include special characters like '-', '_', and others
    const passwordRegex = /^(?=.*[A-Z])(?=.*[!@#$%^&*(),.?":{}|<>_\-])[A-Za-z\d!@#$%^&*(),.?":{}|<>_\-]{8,}$/;

    // Password format validation
    if (!passwordRegex.test(password)) {
      passwordField.classList.add('invalid');
    } else {
      passwordField.classList.remove('invalid');
    }

    // Validate password confirmation
    if (password !== passwordRepeat) {
      passwordRepeatField.classList.add('invalid');
    } else {
      passwordRepeatField.classList.remove('invalid');
    }
  }

  // Add event listeners to both password fields for real-time validation
  document.getElementById('password').addEventListener('input', validatePassword);
  document.getElementById('password-repeat').addEventListener('input', validatePassword);

  // Add event listener to form submission to check password validity
  document.querySelector('form').addEventListener('submit', function(event) {
    const password = document.getElementById('password').value;
    const passwordRepeat = document.getElementById('password-repeat').value;

    // Check if the password is valid format
    if (!/^(?=.*[A-Z])(?=.*[!@#$%^&*(),.?":{}|<>_\-])[A-Za-z\d!@#$%^&*(),.?":{}|<>_\-]{8,}$/.test(password)) {
      event.preventDefault(); // Stop form submission
      // SweetAlert2 Popup for invalid password format
      Swal.fire({
        icon: 'error',
        title: 'Invalid Password Format',
        text: 'Password does not meet the required format',
        confirmButtonText: 'OK'
      });
    } else if (password !== passwordRepeat) {
      event.preventDefault(); // Stop form submission
      // SweetAlert2 Popup for password mismatch
      Swal.fire({
        icon: 'error',
        title: 'Password Mismatch',
        text: 'Passwords do not match',
        confirmButtonText: 'OK'
      });
    }
  });
</script>


<script src="js/script.js"></script>



<!-- Add the following CSS for validation feedback -->
<style>
  .valid {
    background-color: #d4edda;
    border-color: #28a745;
  }

  .invalid {
    background-color: #f8d7da;
    border-color: #dc3545;
  }

  input:invalid {
    border-color: red;
  }

  input:valid {
    border-color: green;
  }

  small {
    display: block;
    font-size: 12px;
    margin-top: 5px;
  }

  .error-message {
    color: #dc3545;
    font-size: 14px;
    margin-top: 10px;
    /* Red for error message */
  }

  .success-message {
    color: #28a745;
    /* Green for success message */
  }
</style>
<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</html>