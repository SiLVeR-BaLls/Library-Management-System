<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Digital Library Registration</title>
  <link rel="stylesheet" href="css/sign_up1.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
  <header>
    <p><strong>DIGITAL LIBRARY MANAGEMENT SYSTEM</strong></p>

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
                <label for="Sex">Sex</label>
                <select class="box" name="Sex" id="Sex">
                  <option value="" disabled selected>Select Sex</option>
                  <option value="m">Male</option>
                  <option value="f">Female</option>
                  <option value="o">Other</option>
                </select>
              </div>
              <div class="text-group">
                <label for="DOB">Birthdate</label>
                <input id="DOB" name="DOB" class="box" type="date" required>
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
                  title="Please enter a valid 11-digit number" pattern="^09\d{9}$">
                <span id="contact-message"></span> <!-- Message for contact format validation -->
              </div>

              <div class="text-group">
                <label for="email">Email</label>
                <input id="email" name="email" class="box" type="email" placeholder="sample@gmail.com">
                <span id="email-message"></span> <!-- Message for email format validation -->
              </div>

              <script>
                // Function to validate contact number format
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


              <script>
                // Function to validate ID number format
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
                        'ID number must follow the format: yyyy-xxxx-X (year-code-letter)' :
                        'ID number must be alphanumeric and can include dashes.',
                      confirmButtonText: 'OK'
                    });
                  }
                });
              </script>


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

              <!-- Personnel Type (only visible for faculty) -->
              <div class="text-group" id="personnel-group" style="display: flex; display: none;">
                <label for="personnel_type" style="flex: 1;">Personnel Type</label>
                <select class="box" id="personnel_type" name="personnel_type">
                  <option value="" selected disabled>Select Personnel Type</option>
                  <option value="Teaching Personnel">Teaching Personnel</option>
                  <option value="Non-Teaching Personnel">Non-Teaching Personnel</option>
                </select>
              </div>


              <!-- Program (College) -->
              <div class="text-group" style="display: flex;">
                <label for="college" style="flex: 1;">Program</label>
                <select class="box" id="college" name="college" required>
                  <option value="" selected disabled>Select College</option>
                  <option value="cas">College of Arts and Sciences</option>
                  <option value="cea">College of Engineering and Architecture</option>
                  <option value="coe">College of Education</option>
                  <option value="cit">College of Industrial Technology</option>
                </select>
              </div>

              <!-- Course (only visible for students) -->
              <div class="text-group" id="course-group" style="display: flex;">
                <label for="course" style="flex: 1;">Course</label>
                <select class="box" id="course" name="course">
                  <option value="" selected disabled>Select Course</option>
                  <option value="course1">Course 1</option>
                  <option value="course2">Course 2</option>
                  <option value="course3">Course 3</option>
                </select>
              </div>

              <!-- Year and Section (only visible for students) -->
              <div class="text-group" id="yrLVL-group" style="display: flex;">
                <label for="yrLVL" style="flex: 1;">Year and Section</label>
                <select class="box" id="yrLVL" name="yrLVL">
                  <option value="" selected disabled>Select Year and Section</option>
                  <?php for ($year = 1; $year <= 5; $year++): ?>
                    <?php foreach (['A', 'B', 'C', 'D'] as $section): ?>
                      <option value="<?php echo $year . ' ' . $section; ?>" <?php echo (isset($user['yrLVL']) &&
                                                                              $user['yrLVL'] == "$year $section") ? 'selected' : ''; ?>>
                        <?php echo $year . ' ' . $section; ?>
                      </option>
                    <?php endforeach; ?>
                  <?php endfor; ?>
                </select>
              </div>


            </div>
          </div>
        </div>

        <!-- JavaScript function to toggle between student and faculty info -->
        <script>
          function toggleUserType() {
            const userType = document.getElementById("U_Type").value;
            const courseGroup = document.getElementById("course-group");
            const yrLVLGroup = document.getElementById("yrLVL-group");
            const personnelGroup = document.getElementById("personnel-group");

            if (userType === "faculty") {
              // For faculty: Hide Course and Year & Section, and show Personnel Type
              courseGroup.style.display = "none";
              yrLVLGroup.style.display = "none";
              personnelGroup.style.display = "flex"; // Show Personnel Type
            } else {
              // For student: Show Course and Year & Section, and hide Personnel Type
              courseGroup.style.display = "flex";
              yrLVLGroup.style.display = "flex";
              personnelGroup.style.display = "none"; // Hide Personnel Type
            }
          }

          // Call the toggle function on page load to ensure the correct form is displayed
          window.onload = function() {
            toggleUserType();
          };
        </script>


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
                  <input id="password" name="password" class="box" type="password" placeholder="Ex.'2000-0000-A'">
                  <div class="pup_upwarn">
                    <div class="popup" onclick="myFunction()">!
                      <span class="popuptext" id="myPopup">the password should be 8 letters lengths<br>
                        atleast 1 small and capital letter<br>
                        with aleast 1 special characters and 1 numbers
                      </span>
                    </div>
                    <span id="password-toggle" class="show-password" onclick="togglePasswordVisibility('password', 'password-toggle')">📚</span>
                  </div>
                </div>
                <small id="password-message"></small>
              </div>
              <script>
                // When the user clicks on div, open the popup
                function myFunction() {
                  var popup = document.getElementById("myPopup");
                  popup.classList.toggle("show");
                }
              </script>
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

              <script>
                // Function to validate password
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