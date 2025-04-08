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
        $U_Type = 'librarian';

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
                        <input id="Fname" name="Fname" type="text" placeholder="Firstname" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div>
                        <label for="Sname" class="text-sm font-medium">Surname</label>
                        <input id="Sname" name="Sname" type="text" placeholder="Surname" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label for="Mname" class="text-sm font-medium">Middle Name</label>
                        <input id="Mname" name="Mname" type="text" placeholder="Middle Name" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div>
                        <label for="Ename" class="text-sm font-medium">Extension</label>
                        <input id="Ename" name="Ename" type="text" placeholder="Enter Extension" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
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
                        <option value="o">Other</option>
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
                    <input id="contact" name="contact" type="text" placeholder="09*********" pattern="^\d{11}$" title="Please enter a valid 11-digit number" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
                </div>
                <div>
                    <label for="email" class="text-sm font-medium">Email 1</label>
                    <input id="email" name="email" type="email" placeholder="sample@gmail.com" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
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
                        <input id="IDno" name="IDno" type="text" placeholder="Enter ID (if Manual)" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
                    </div>
                </div>
            </fieldset>

            <!-- Site Information -->
            <fieldset class="space-y-5 p-4 border border-gray-300 rounded-md">
                <legend class="text-lg font-semibold">Site Information</legend>
                <div id="user-info" class="space-y-4">

                    <!-- Department -->
                    <div id="department-group">
                        <label for="college" class="text-sm font-medium">Department</label>
                        <select id="college" name="college" class="w-full mt-1 border-gray-300 rounded-md shadow-sm">
              <option value="" disabled selected>Select Department</option>
              <?php foreach ($departments as $department): ?>
                <option value="<?php echo $department['id']; ?>">
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
                    <input id="username" name="username" type="text" placeholder="Enter Username" class="w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
                </div>
                <div class="relative">
                    <label for="password" class="text-sm font-medium">Password</label>
                    <input id="password" name="password" type="password" placeholder="Enter Password" class="w-full mt-1 border-gray-300 rounded-md shadow-sm pr-10" required>
                    <!-- Password toggle button (on the right side of the input) -->
                    <span id="password-toggle" class="show-password absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer" onclick="togglePasswordVisibility('password', 'password-toggle')">📚</span>
                </div>
            </div>
        </fieldset>

        <!-- Submit Button -->
        <div class="text-center">
            <button type="submit" id="submitBtn" class="px-6 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:ring-4 focus:ring-blue-300">Submit</button>
        </div>
    </form>
</div>