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
          <label for="gender" class="text-sm font-medium">Sex</label>
          <select id="gender" name="gender" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
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
            <a href="#" onclick="openProgramPopup()" class="text-blue-600 underline">Add</a>
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

          <!-- Year and Section (Dynamic based on course table) -->
          <div id="yrLVL-group">
            <label for="yrLVL" class="text-sm font-medium">Year and Section</label>
                   <!-- Year and Section (Hidden for Faculty) -->
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
                <input type="number" id="max_year_<?php echo $course['id']; ?>" name="max_year_<?php echo $course['id']; ?>" value="<?php echo htmlspecialchars($course['max_year'] ?? ''); ?>" data-original-value="<?php echo htmlspecialchars($course['max_year'] ?? ''); ?>" min="0" max="1" step="1" onwheel="this.blur()" oninput="this.value = (this.value === '0' || this.value === '1') ? this.value : ''; enableSaveButton(<?php echo $course['id']; ?>)">
                <button id="saveButton_<?php echo $course['id']; ?>" onclick="saveMaxYear(<?php echo $course['id']; ?>)" disabled>Save</button>
                <button class="bg-red-500 text-white px-2 py-1 rounded ml-2" data-type="course" data-id="<?php echo $course['id']; ?>" onclick="confirmRemove(this)">-</button>
              </div>
            <?php endforeach; ?>
          </div>

          <script>
            function enableSaveButton(courseId) {
              const inputElement = document.getElementById('max_year_' + courseId);
              const saveButton = document.getElementById('saveButton_' + courseId);

              if (inputElement.value !== inputElement.dataset.originalValue) {
                saveButton.disabled = false;
              } else {
                saveButton.disabled = true;
              }
            }

            function saveMaxYear(courseId) {
              const newValue = document.getElementById('max_year_' + courseId).value;
              const saveButton = document.getElementById('saveButton_' + courseId);

              // Disable the button during saving
              saveButton.disabled = true;
              saveButton.textContent = 'Saving...';

              fetch('', {
                  method: 'POST',
                  headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                  },
                  body: 'save_max_year=true&course_id=' + courseId + '&new_value=' + newValue
                })
                .then(response => response.text())
                .then(data => {
                  console.log('Server response:', data);
                  saveButton.textContent = 'Save'; // Revert button text
                  const inputElement = document.getElementById('max_year_' + courseId);
                  inputElement.dataset.originalValue = newValue; // Update original value
                  saveButton.disabled = true; // Disable after successful save
                  // Optionally update the UI to show success
                })
                .catch(error => {
                  console.error('Error saving max year:', error);
                  saveButton.textContent = 'Error'; // Show error on button
                  saveButton.disabled = false; // Re-enable on error
                  // Optionally update the UI to show an error
                });
            }
          </script>

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
      <input type="text" name="newCourse" id="coursePopupInput" class="w-full border rounded px-3 py-2 mb-4"
        placeholder="Enter Course Name" required>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeCoursePopup(event)"
          class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 transition">Cancel
        </button>
        <button type="button" onclick="submitCourseForm(event)"
          class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Confirm
        </button>
      </div>
    </form>
  </div>
</div>




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
  function submitCourseForm(event) {
    event.preventDefault(); // Prevent the default form submission

    const form = document.getElementById('courseForm');
    const formData = new FormData(form);

    fetch(window.location.href, { // Send to the same page
        method: 'POST',
        body: formData,
      })
      .then(response => response.json()) // Expect JSON response
      .then(data => {
        if (data.status === 'success') {
          const newCourse = data.course;
          const filteredCoursesList = document.getElementById('filteredCoursesList');
          const courseFilter = document.getElementById('courseFilter');
          const selectedFilterProgramId = courseFilter.value;

          // Create a new div for the added course
          const courseDiv = document.createElement('div');
          courseDiv.classList.add('border-b', 'py-2', 'flex', 'justify-between', 'items-center');
          courseDiv.dataset.programId = newCourse.program_id;
          courseDiv.innerHTML = `
                              ${newCourse.name}
                              <button class="bg-red-500 text-white px-2 py-1 rounded" data-type="course" data-id="${newCourse.id}" onclick="confirmRemove(this)">-</button>
                          `;

          // Add the new course to the displayed list if it matches the current filter
          if (selectedFilterProgramId === '' || selectedFilterProgramId === String(newCourse.program_id)) {
            filteredCoursesList.appendChild(courseDiv);
          }

          closeCoursePopup(event); // Close the popup
          // Optionally, provide a success message to the user
          console.log(data.message);
        } else if (data.status === 'error') {
          document.getElementById('duplicateErrorPopup').querySelector('.text-gray-700').textContent = data.message;
          document.getElementById('duplicateErrorPopup').style.display = 'flex';
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding the course.');
      });
  }

  function openCoursePopup(event) {
    const selectedProgramId = document.getElementById("Program").value;
    if (!selectedProgramId) {
      alert("Please select a program before adding a course.");
      return;
    }
    if (event) event.preventDefault(); // Prevent default link behavior

    // Set the programId in the popup form to the currently selected program
    document.getElementById('programId').value = selectedProgramId;

    document.getElementById("coursePopupContainer").classList.remove("hidden");
  }

  function closeCoursePopup(event) {
    document.getElementById("coursePopupContainer").classList.add("hidden");
    // Optionally clear the course input field after closing
    document.getElementById('coursePopupInput').value = '';
  }

  function filterCourses() {
    const selectedProgramId = document.getElementById("Program").value;
    const courseDropdown = document.getElementById("course");
    const addCourseLink = document.getElementById("addCourseLink");
    const filteredCoursesList = document.getElementById('filteredCoursesList');
    const allCourseDivs = filteredCoursesList.querySelectorAll('div');

    allCourseDivs.forEach(div => {
      if (selectedProgramId === '' || div.dataset.programId === selectedProgramId) {
        div.style.display = '';
      } else {
        div.style.display = 'none';
      }
    });
  }

  // Filter the displayed course list based on the selected program in the filter dropdown
  document.getElementById('courseFilter').addEventListener('change', function() {
    const selectedProgramId = this.value;
    const courses = document.querySelectorAll('#filteredCoursesList > div');

    courses.forEach(courseDiv => {
      const divProgramId = courseDiv.dataset.programId;
      if (selectedProgramId === '' || divProgramId === selectedProgramId) {
        courseDiv.style.display = '';
      } else {
        courseDiv.style.display = 'none';
      }
    });
  });

  // Initially filter the course list when the page loads
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('courseFilter').dispatchEvent(new Event('change'));
  });

  function confirmRemove(button) {
    const removeId = button.dataset.id;
    const removeType = button.dataset.type;

    if (confirm(`Are you sure you want to remove this ${removeType}?`)) {
      fetch(window.location.href, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `remove_id=${removeId}&remove_type=${removeType}`,
        })
        .then(response => {
          // Instead of trying to parse JSON, check if the redirect happened
          if (response.redirected) {
            window.location.reload(); // Force a page reload after the redirect
          } else {
            // If not redirected (which shouldn't happen with your PHP),
            // you might want to handle potential errors differently.
            console.error('Deletion might have failed or no redirect occurred.');
            alert('Deletion might have failed.');
          }
        })
        .catch(error => {
          console.error('Fetch error:', error);
          alert('An error occurred during the fetch.');
        });
    }
  }
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Display Data Modal (assuming this is still relevant in your full context)
    const displayDataButton = document.getElementById('displayDataButton');
    if (displayDataButton) {
      displayDataButton.addEventListener('click', function() {
        const displayDataModal = document.getElementById('displayDataModal');
        if (displayDataModal) {
          displayDataModal.classList.remove('hidden');
        }
      });
    }

    // Close Modal (using a generic class)
    document.querySelectorAll('.modal').forEach(modal => {
      modal.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
          this.classList.add('hidden');
        }
      });
    });

    // Course Filter (already present above, but ensuring it's here)
    const courseFilterElement = document.getElementById('courseFilter');
    if (courseFilterElement) {
      courseFilterElement.addEventListener('change', function() {
        const selectedProgramId = this.value;
        const courseDivs = document.querySelectorAll('#filteredCoursesList div');
        courseDivs.forEach(div => {
          const divProgramId = div.dataset.programId;
          if (selectedProgramId === '' || divProgramId === selectedProgramId) {
            div.style.display = '';
          } else {
            div.style.display = 'none';
          }
        });
      });
      // Initially filter on load
      courseFilterElement.dispatchEvent(new Event('change'));
    }
  });
</script>

<script>
  function openProgramPopup(event) {
    if (event) event.preventDefault(); // Prevent default link behavior
    const popupContainer = document.getElementById("popupContainer");
    if (popupContainer) {
      popupContainer.classList.remove("hidden");
    }
  }

  function closePopup(event) {
    if (event) event.preventDefault(); // Prevent default button behavior
    const popupContainer = document.getElementById("popupContainer");
    if (popupContainer) {
      popupContainer.classList.add("hidden");
    }
  }

  function submitProgramForm(event) {
    if (event) event.preventDefault(); // Prevent default form submission
    const programForm = document.getElementById("programForm");
    if (programForm) {
      programForm.submit(); // Submit the form programmatically
    }
  }

  function openDepartmeevntPopup(event) {
    if (event) event.preventDefault(); // Prevent default link behavior
    const departmentPopupContainer = document.getElementById("departmentPopupContainer");
    if (departmentPopupContainer) {
      departmentPopupContainer.classList.remove("hidden");
    }
  }

  function closeDepartmentPopup(event) {
    if (event) event.preventDefault(); // Prevent default button behavior
    const departmentPopupContainer = document.getElementById("departmentPopupContainer");
    if (departmentPopupContainer) {
      departmentPopupContainer.classList.add("hidden");
    }
  }

  function submitDepartmentForm(event) {
    if (event) event.preventDefault(); // Prevent default form submission
    const departmentForm = document.getElementById("departmentForm");
    if (departmentForm) {
      departmentForm.submit(); // Submit the form programmatically
    }
  }

  function openCoursePopup(event) { // Declared twice, keeping the first one
    const selectedProgramId = document.getElementById("Program").value;
    if (!selectedProgramId) {
      alert("Please select a program before adding a course.");
      return;
    }
    if (event) event.preventDefault(); // Prevent default link behavior
    const coursePopupContainer = document.getElementById("coursePopupContainer");
    if (coursePopupContainer) {
      coursePopupContainer.classList.remove("hidden");
    }
  }

  function closeCoursePopup(event) { // Declared twice, keeping the first one
    if (event) event.preventDefault(); // Prevent default button behavior
    const coursePopupContainer = document.getElementById("coursePopupContainer");
    if (coursePopupContainer) {
      coursePopupContainer.classList.add("hidden");
    }
  }

  function submitCourseForm(event) { // Declared twice, keeping the first one
    if (event) event.preventDefault(); // Prevent default form submission
    const courseForm = document.getElementById("courseForm");
    if (courseForm) {
      courseForm.submit(); // Submit the form programmatically
    }
  }

  // Close program popup if clicked outside the box
  const popupContainer = document.getElementById("popupContainer");
  if (popupContainer) {
    popupContainer.addEventListener("click", function(event) {
      if (event.target === this) closePopup(event);
    });
  }

  // Close department popup if clicked outside the box
  const departmentPopupContainer = document.getElementById("departmentPopupContainer");
  if (departmentPopupContainer) {
    departmentPopupContainer.addEventListener("click", function(event) {
      if (event.target === this) closeDepartmentPopup(event);
    });
  }

  // Close course popup if clicked outside the box
  const coursePopupContainer = document.getElementById("coursePopupContainer");
  if (coursePopupContainer) {
    coursePopupContainer.addEventListener("click", function(event) {
      if (event.target === this) closeCoursePopup(event);
    });
  }
</script>

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