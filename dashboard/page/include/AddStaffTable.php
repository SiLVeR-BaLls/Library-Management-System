<div class="min-h-screen bg-[#f2f2f2] justify-center items-center px-10">
  <center class="bg-green-100 p-4 rounded-md shadow-md">
    <h1 class="text-2xl font-bold">Add Staff</h1>
  </center>
  <form id="registration-form" action="" method="post" class="space-y-8">
    <!-- Staff Information Section -->
    <fieldset class="space-y-5 p-4 border border-gray-300 rounded-md">
      <legend class="text-lg font-semibold">Staff Information</legend>
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
            max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>">
        </div>
      </div>
    </fieldset>

    <!-- Contact Information Section -->
    <fieldset class="space-y-5 p-4 border border-gray-300 rounded-md">
      <legend class="text-lg font-semibold">Contact Information</legend>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label for="contact" class="text-sm font-medium">Contact No.</label>
          <input id="contact" name="contact" type="text" placeholder="09*********" pattern="^\d{11}$"
            title="Please enter a valid 11-digit number" class="w-full mt-1 border-gray-300 rounded-md shadow-sm"
            required>
        </div>
        <div>
          <label for="email" class="text-sm font-medium">Email</label>
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
            <input id="IDno" name="IDno" type="text" placeholder="Enter ID"
              class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
          </div>
        </div>
      </fieldset>

      <!-- Site Information -->
      <fieldset class="space-y-5 p-4 border border-gray-300 rounded-md">
        <legend class="text-lg font-semibold">Site Information</legend>
        <div id="user-info" class="space-y-4">

          <!-- Department -->
          <div id="department-group">
            <label for="department" class="text-sm font-medium">Department</label>
            <select id="department" name="department" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
              <option value="" disabled selected>Select Department</option>
              <?php foreach ($departments as $department): ?>
                <option value="<?php echo htmlspecialchars($department['name']); ?>">
                  <?php echo htmlspecialchars($department['name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Personnel Type -->
          <div id="personnel-group">
            <label for="personnel_type" class="text-sm font-medium">Personnel Type</label>
            <select id="personnel_type" name="personnel_type" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
              <option value="" disabled selected>Select Personnel Type</option>
              <option value="Teaching Personnel">Teaching Personnel</option>
              <option value="Non-Teaching Personnel">Non-Teaching Personnel</option>
            </select>
          </div>
        </div>
      </fieldset>
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
</div>

