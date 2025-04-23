<div class="min-h-screen bg-[#f2f2f2] justify-center items-center px-10">
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
          <input id="DOB" name="DOB" type="date" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required
            max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>" 
            value="<?php echo date('Y-m-d', strtotime('-18 years')); ?>"> <!-- Set default date -->
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

          <!-- College -->
          <div id="program-group">
            <label for="college" class="text-sm font-medium">College</label>
            <select id="college" name="college" class="form-select w-full">
              <option value="" disabled selected>Select College</option>
              <?php foreach ($colleges as $college): ?>
                <option value="<?php echo htmlspecialchars($college['name']); ?>">
                  <?php echo htmlspecialchars($college['name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
            <button type="button" onclick="openProgramPopup()" class="text-blue-600 underline">Add</button>
          </div>

          <!-- Department -->
          <div id="department-group">
            <label for="department" class="text-sm font-medium">Department</label>
            <select id="department" name="department" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
              <option value="" disabled selected>Select Department</option>
              <?php foreach ($departments as $department): ?>
                <option value="<?php echo htmlspecialchars($department['name']); ?>">
                  <?php echo htmlspecialchars($department['name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
            <button type="button" onclick="openDepartmentPopup()" class="text-blue-600 underline">Add</button>
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
              <option value="" disabled selected>Select Course</option>
              <?php if (!empty($courses)): ?>
                <?php foreach ($courses as $course): ?>
                  <option value="<?php echo htmlspecialchars($course['name']); ?>"> <!-- Use course name as value -->
                    <?php echo htmlspecialchars($course['name']); ?>
                  </option>
                <?php endforeach; ?>
              <?php else: ?>
                <option value="" disabled>No courses available</option>
              <?php endif; ?>
            </select>
            <button type="button" id="addCourseLink" onclick="openCoursePopup()" class="text-blue-600 underline">Add</button>
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

  <?php if (!empty($_GET['success']) && $_GET['success'] === 'true'): ?>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('registration-form').reset(); // Clear the form
        Swal.fire({
          icon: 'success',
          title: 'Success',
          text: 'Data submitted successfully!',
          confirmButtonText: 'OK'
        });
      });
    </script>
  <?php endif; ?>

  <button type="button" id="displayDataButton" class="px-6 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:ring-4 focus:ring-blue-300">
    Display Data
  </button>
  <div id="displayDataModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden" style="z-index: 10000;">
    <div class="modal-content bg-white p-6 rounded-lg shadow-lg w-full max-w-5xl">
      <h2 class="text-xl font-bold mb-4">Added Data</h2>

      <div class="flex space-x-4">
        <!-- Departments Section -->
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

        <!-- Colleges Section -->
        <div class="w-full">
          <h3 class="text-lg font-semibold mb-2">Colleges</h3>
          <div id="filteredCollegesList" class="overflow-y-auto max-h-96">
            <?php foreach ($colleges as $college): ?>
              <div class="border-b py-2 flex justify-between items-center" data-college-id="<?php echo $college['id']; ?>">
                <?php echo htmlspecialchars($college['name']); ?>
                <button class="bg-red-500 text-white px-2 py-1 rounded" data-type="college" data-id="<?php echo $college['id']; ?>" onclick="confirmRemove(this)">-</button>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Courses Section -->
        <div class="w-full">
          <div class="flex justify-between items-center gap-4 mb-4">
            <h3 class="text-lg font-semibold">Courses</h3>
            <select id="courseFilter" class="px-3 py-2">
              <option value="" selected>All Colleges</option>
              <?php foreach ($colleges as $college): ?>
                <option value="<?php echo $college['id']; ?>"><?php echo htmlspecialchars($college['name']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div id="filteredCoursesList" class="overflow-y-auto max-h-96">
            <?php if (isset($courses) && is_array($courses) && !empty($courses)): ?>
              <?php foreach ($courses as $course): ?>
                <?php 
                  $courseId = isset($course['id']) ? htmlspecialchars($course['id']) : '';
                  $collegeId = isset($course['college_id']) ? htmlspecialchars($course['college_id']) : '';
                  $courseName = isset($course['name']) ? htmlspecialchars($course['name']) : 'Unknown Course';
                ?>
                <div class="py-2 flex justify-between items-center" data-course-id="<?php echo $courseId; ?>" data-college-id="<?php echo $collegeId; ?>">
                  <span><?php echo $courseName; ?></span>
                  <div class="flex items-center space-x-2">
                    <input type="number" min="0" max="1" value="<?php echo htmlspecialchars($course['max_year'] ?? 0); ?>" 
                           class="w-16 px-2 py-1 text-center" 
                           onchange="updateMaxYear(<?php echo $courseId; ?>, this.value)">
                    <button class="bg-red-500 text-white px-2 py-1 rounded" data-type="course" data-id="<?php echo $courseId; ?>" onclick="confirmRemove(this)">-</button>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p>No courses available.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="confirmationModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden" style="z-index: 10001;">
    <div class="modal-content bg-white p-6 rounded-lg shadow-lg w-96">
      <p id="confirmationMessage">Are you sure you want to remove this item?</p>
      <div class="flex justify-end mt-4">
        <button id="confirmRemoveButton" class="bg-red-500 text-white px-4 py-2 rounded mr-2">Remove</button>
        <button id="cancelRemoveButton" class="bg-gray-400 text-white px-4 py-2 rounded" onclick="closeConfirmationModal()">Cancel</button>
      </div>
    </div>
  </div>

  <!-- Duplication Error Popup -->
  <div id="duplicationErrorPopup" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden" style="z-index: 10001;">
    <div class="modal-content bg-white p-6 rounded-lg shadow-lg w-96">
      <h2 class="text-xl font-bold mb-4 text-red-500">Duplication Error</h2>
      <p id="duplicationErrorMessage" class="text-gray-700"></p>
      <div class="flex justify-end mt-4">
        <button type="button" onclick="closeDuplicationErrorPopup()" class="bg-blue-500 text-white px-4 py-2 rounded">OK</button>
      </div>
    </div>
  </div>
</div>

<div id="message" class="text-center mb-4">
  <?php
  if (!empty($_GET['message'])): ?>
    <p class="<?php echo $_GET['type'] === 'success' ? 'text-green-500' : 'text-red-500'; ?>">
      <?php echo htmlspecialchars($_GET['message']); ?>
    </p>
  <?php endif; ?>
</div>

<!-- Program Popup -->
<div id="popupContainer" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
  <div id="popupBox" class="bg-white p-6 rounded-lg shadow-lg w-80">
    <h2 class="text-xl font-bold mb-2">Add New Program</h2>
    <form id="programForm" method="post" action="" class="ajax-form">
      <input type="text" name="newProgram" id="popupInput" class="w-full border rounded px-3 py-2 mb-4" placeholder="Enter Program Name" required>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closePopup(event)" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 transition">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Confirm</button>
      </div>
    </form>
  </div>
</div>

<!-- Department Popup -->
<div id="departmentPopupContainer" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
  <div id="departmentPopupBox" class="bg-white p-6 rounded-lg shadow-lg w-80">
    <h2 class="text-xl font-bold mb-2">Add New Department</h2>
    <form id="departmentForm" method="post" action="" class="ajax-form">
      <input type="text" name="newDepartment" id="departmentPopupInput" class="w-full border rounded px-3 py-2 mb-4" placeholder="Enter Department Name" required>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeDepartmentPopup()" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 transition">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Confirm</button>
      </div>
    </form>
  </div>
</div>

<!-- Course Popup -->
<div id="coursePopupContainer" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
  <div id="coursePopupBox" class="bg-white p-6 rounded-lg shadow-lg w-96">
    <h2 class="text-xl font-bold mb-4 text-center">Add New Course</h2>
    <form id="courseForm" method="post" action="AddUser.php" class="space-y-4" onsubmit="closeCoursePopupAfterSubmit(event)">
      <div>
        <label for="programId" class="block text-sm font-medium text-gray-700">College:</label>
        <select id="programId" name="programId" 
                class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
          <option value="" disabled selected>Select College</option>
          <?php foreach ($colleges as $college): ?>
            <option value="<?php echo $college['id']; ?>">
              <?php echo htmlspecialchars($college['name']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label for="newCourse" class="block text-sm font-medium text-gray-700">Course Name:</label>
        <input type="text" id="newCourse" name="newCourse" 
               class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
               placeholder="Enter Course Name" required>
      </div>
      <div class="flex justify-end space-x-2">
        <button type="button" onclick="closeCoursePopup()" 
                class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 transition">Cancel</button>
        <button type="submit" 
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Add Course</button>
      </div>
    </form>
  </div>
</div>

<div id="feedbackPopup" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
  <div class="modal-content bg-white p-6 rounded-lg shadow-lg w-96">
    <h2 id="feedbackTitle" class="text-xl font-bold mb-4"></h2>
    <p id="feedbackMessage" class="text-gray-700"></p>
    <div class="flex justify-end mt-4">
      <button type="button" onclick="closeFeedbackPopup()" class="bg-blue-500 text-white px-4 py-2 rounded">OK</button>
    </div>
  </div>
</div>

<style>
  /* ...existing styles... */
  #popupContainer,
  #departmentPopupContainer,
  #coursePopupContainer {
    z-index: 10001; /* Ensure the popups are above other elements */
  }
</style>

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
    const selectedCollegeName = document.getElementById("college").value; // Get the selected college name
    const courseDropdown = document.getElementById("course");
    const yrLvlDropdown = document.getElementById("yrLVL");

    // Clear the course dropdown
    courseDropdown.innerHTML = "";

    // Clear the year level dropdown
    yrLvlDropdown.innerHTML = "";

    // Add a default "Select Year and Section" option
    const defaultYrOption = document.createElement("option");
    defaultYrOption.value = "";
    defaultYrOption.textContent = "Select Year and Section";
    defaultYrOption.disabled = true;
    defaultYrOption.selected = true;
    yrLvlDropdown.appendChild(defaultYrOption);

    // If no college is selected, do not populate the course dropdown
    if (!selectedCollegeName) {
      const defaultOption = document.createElement("option");
      defaultOption.value = "";
      defaultOption.textContent = "Select Course";
      defaultOption.disabled = true;
      defaultOption.selected = true;
      courseDropdown.appendChild(defaultOption);
      return;
    }

    // Populate the course dropdown based on the selected college
    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.textContent = "Select Course";
    defaultOption.disabled = true;
    defaultOption.selected = true;
    courseDropdown.appendChild(defaultOption);

    courses.forEach(course => {
      if (course.college_name === selectedCollegeName) { // Match by college name
        const option = document.createElement("option");
        option.value = course.name; // Save course name instead of ID
        option.textContent = course.name;
        option.dataset.maxYear = course.max_year; // Store max_year as a data attribute
        courseDropdown.appendChild(option);
      }
    });
  }

  function updateYearAndSection() {
    const courseDropdown = document.getElementById("course");
    const selectedCourse = courseDropdown.options[courseDropdown.selectedIndex];
    const maxYear = selectedCourse ? parseInt(selectedCourse.dataset.maxYear) : 0;
    const yrLvlDropdown = document.getElementById("yrLVL");

    // Clear the year level dropdown
    yrLvlDropdown.innerHTML = "";

    // Add a default "Select Year and Section" option
    const defaultYrOption = document.createElement("option");
    defaultYrOption.value = "";
    defaultYrOption.textContent = "Select Year and Section";
    defaultYrOption.disabled = true;
    defaultYrOption.selected = true;
    yrLvlDropdown.appendChild(defaultYrOption);

    // Populate year and section options based on max_year
    if (!isNaN(maxYear)) {
      const maxYearLevel = maxYear === 1 ? 5 : 4;
      for (let year = 1; year <= maxYearLevel; year++) {
        ["A", "B", "C", "D"].forEach(section => {
          const option = document.createElement("option");
          option.value = `${year} ${section}`;
          option.textContent = `${year} ${section}`;
          yrLvlDropdown.appendChild(option);
        });
      }
    }
  }

  // Attach event listeners
  document.getElementById("college").addEventListener("change", filterCourses);
  document.getElementById("course").addEventListener("change", updateYearAndSection);

  // Initial setup
  document.addEventListener("DOMContentLoaded", filterCourses);

  function openCoursePopup() {
    const coursePopupContainer = document.getElementById("coursePopupContainer");
    if (coursePopupContainer) {
      coursePopupContainer.classList.remove("hidden");
    }
  }
</script>

<script>
  // Show the modal for displaying added data
  document.getElementById('displayDataButton').addEventListener('click', function() {
    document.getElementById('displayDataModal').classList.remove('hidden');
  });

  // Close the modal when clicking outside the content
  document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(event) {
      if (event.target === this) {
        this.classList.add('hidden');
      }
    });
  });

  // Confirm removal of an item
  function confirmRemove(button) {
    const removeId = button.dataset.id;
    const removeType = button.dataset.type;

    if (confirm(`Are you sure you want to remove this ${removeType}?`)) {
      const formData = new FormData();
      formData.append('remove_id', removeId);
      formData.append('remove_type', removeType);

      fetch(window.location.href, {
        method: 'POST',
        body: formData,
      })
        .then(response => response.text())
        .then(data => {
          alert(data.trim());
          window.location.reload(); // Reload to reflect changes
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred while removing the item.');
        });
    }
  }

  // Open the Program Popup
  function openProgramPopup(event) {
    if (event) event.preventDefault(); // Prevent default link behavior
    const popupContainer = document.getElementById("popupContainer");
    if (popupContainer) {
      popupContainer.classList.remove("hidden");
    }
  }

  // Close the Program Popup
  function closePopup(event) {
    if (event) event.preventDefault(); // Prevent default button behavior
    const popupContainer = document.getElementById("popupContainer");
    if (popupContainer) {
      popupContainer.classList.add("hidden");
    }
  }

  // Open the Department Popup
  function openDepartmentPopup(event) {
    if (event) event.preventDefault(); // Prevent default link behavior
    const departmentPopupContainer = document.getElementById("departmentPopupContainer");
    if (departmentPopupContainer) {
      departmentPopupContainer.classList.remove("hidden");
    }
  }

  // Close the Department Popup
  function closeDepartmentPopup(event) {
    if (event) event.preventDefault(); // Prevent default button behavior
    const departmentPopupContainer = document.getElementById("departmentPopupContainer");
    if (departmentPopupContainer) {
      departmentPopupContainer.classList.add("hidden");
    }
  }

  // Open the Course Popup
  function openCoursePopup(event) {
    const coursePopupContainer = document.getElementById("coursePopupContainer");
    if (coursePopupContainer) {
      coursePopupContainer.classList.remove("hidden");
    }
  }

  // Close the Course Popup
  function closeCoursePopup(event) {
    if (event) event.preventDefault(); // Prevent default button behavior
    const coursePopupContainer = document.getElementById("coursePopupContainer");
    if (coursePopupContainer) {
      coursePopupContainer.classList.add("hidden");
    }
  }

  function closeCoursePopupAfterSubmit(event) {
    event.preventDefault(); // Prevent default form submission
    const coursePopupContainer = document.getElementById("coursePopupContainer");
    if (coursePopupContainer) {
      coursePopupContainer.classList.add("hidden"); // Hide the modal
    }
    document.getElementById("courseForm").submit(); // Submit the form programmatically
  }

  // Close popups when clicking outside the popup box
  document.addEventListener("click", function(event) {
    const popupContainers = [
      document.getElementById("popupContainer"),
      document.getElementById("departmentPopupContainer"),
      document.getElementById("coursePopupContainer"),
    ];
    popupContainers.forEach(container => {
      if (container && !container.classList.contains("hidden") && event.target === container) {
        container.classList.add("hidden");
      }
    });
  });

  function updateMaxYear(courseId, newValue) {
    if (newValue < 0 || newValue > 1) {
      alert("Max year must be either 0 or 1.");
      return;
    }

    fetch(window.location.href, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `save_max_year=1&course_id=${courseId}&new_value=${newValue}`,
    })
      .then(response => response.text())
      .then(data => {
        alert(data.trim());
        window.location.reload();
      })
      .catch(error => {
        console.error("Error updating max year:", error);
        alert("An error occurred while updating the max year.");
      });
  }

  // Filter courses by selected college
  document.getElementById('courseFilter').addEventListener('change', function() {
    const selectedCollegeId = this.value;
    const courses = document.querySelectorAll('#filteredCoursesList > div');

    courses.forEach(courseDiv => {
      const courseCollegeId = courseDiv.dataset.collegeId;
      if (selectedCollegeId === '' || courseCollegeId === selectedCollegeId) {
        courseDiv.style.display = '';
      } else {
        courseDiv.style.display = 'none';
      }
    });
  });

  // Function to show duplication error popup
  function showDuplicationErrorPopup(message) {
    const popup = document.getElementById('duplicationErrorPopup');
    const messageElement = document.getElementById('duplicationErrorMessage');
    messageElement.textContent = message;
    popup.classList.remove('hidden');
  }

  // Function to close duplication error popup
  function closeDuplicationErrorPopup() {
    const popup = document.getElementById('duplicationErrorPopup');
    popup.classList.add('hidden');
  }

  // Example usage of duplication error popup
  <?php if (!empty($_GET['message']) && $_GET['type'] === 'error'): ?>
    showDuplicationErrorPopup("<?php echo htmlspecialchars($_GET['message']); ?>");
  <?php endif; ?>
</script>

<script>
  function showFeedbackPopup(title, message) {
    document.getElementById('feedbackTitle').textContent = title;
    document.getElementById('feedbackMessage').textContent = message;
    document.getElementById('feedbackPopup').classList.remove('hidden');
  }

  function closeFeedbackPopup() {
    document.getElementById('feedbackPopup').classList.add('hidden');
  }

  // Example usage: Show feedback popup on page load if a message is passed via PHP
  <?php if (!empty($_GET['message'])): ?>
    showFeedbackPopup(
      "<?php echo $_GET['type'] === 'success' ? 'Success' : 'Error'; ?>",
      "<?php echo htmlspecialchars($_GET['message']); ?>"
    );
  <?php endif; ?>
</script>

<script>
  function togglePasswordVisibility(passwordFieldId, toggleIconId) {
    const passwordField = document.getElementById(passwordFieldId);
    const toggleIcon = document.getElementById(toggleIconId);

    if (passwordField.type === "password") {
      passwordField.type = "text";
      toggleIcon.textContent = "🙈"; // Change icon to indicate visibility
    } else {
      passwordField.type = "password";
      toggleIcon.textContent = "📚"; // Change icon back to indicate hidden
    }
  }
</script>