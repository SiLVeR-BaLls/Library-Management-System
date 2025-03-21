<?php
// Configuration
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

    // Insert user data into tables
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get POST data
        $Fname = $_POST['Fname'];
        $Sname = $_POST['Sname'];
        $Mname = $_POST['Mname'];
        $Ename = $_POST['Ename'] ?? '';
        $gender = $_POST['gender']??'';
        $DOB = $_POST['DOB'];
        $municipality = $_POST['municipality'];
        $barangay = $_POST['barangay'];
        $province = $_POST['province'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $username = $_POST['username'];
        $password = $_POST['password']; // No hashing
        $IDno = $_POST['IDno'];
       $U_Type = $_POST['U_Type'];
        // $status = $_POST['status'];
        $college = $_POST['college']??'';
        $course = $_POST['course']??'';
        $yrLVL = $_POST['yrLVL']??'';
        $personnel_type = $_POST['personnel_type']??'';
      $status_details ='active';
            $A_LVL = '3';
            $status_log = 'pending';

        // Check if IDno already exists
        $query = "SELECT * FROM users_info WHERE IDno = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $IDno);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "Error: IDno already exists";
            $message_type = "error";
        } else {
            // Insert into users table
            $Ename = 'N/A';
            $photo = 'default.jpg';
            $sql = "INSERT INTO users_info (IDno, Fname, Sname, Mname, Ename, gender, photo, municipality, barangay, province, DOB, college, course, yrLVL, A_LVL, status_details, personnel_type, username, password, U_Type, status_log, email, contact ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssssssssssssssss", $IDno, $Fname, $Sname, $Mname, $Ename, $gender, $photo, $municipality, $barangay, $province, $DOB, $college, $course,  $yrLVL, $A_LVL, $status_details, $personnel_type, $username, $password, $U_Type, $status_log, $email, $contact );
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Processing</title>
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showAlert(message, type) {
            Swal.fire({
                icon: type === 'success' ? 'success' : 'error',
                title: type === 'success' ? 'Please wait for your approval' : 'Error',
                text: message,
                didClose: () => {
                    if (type === 'success') {
                        window.location.href = '../log_in.php'; // Redirect to the index page
                    } else if (type === 'error') {
                // Redirect back to the registration page using history.back()
                // This will take the user to the previous page in their history stack
                window.history.back();
              }
                }
            });
        }

        function formatGender(gender) {
            switch (gender.toLowerCase()) {
                case 'f':
                    return 'Mrs';
                case 'm':
                    return 'Mr';
                case 'o':

                    return 'Msr';
            }
        }
    </script>
</head>
<body>
    <script>
        // Check if there's a message and type
        <?php if ($message): ?>
            document.addEventListener('DOMContentLoaded', function() {
                var formattedGender = formatGender("<?php echo addslashes($gender); ?>");
                var fullMessage = "<?php echo addslashes($message); ?>";

                if ("<?php echo $message_type; ?>" === 'success') {
                    showAlert(`It might take a while for our team to review your account while pending`, "success");
                } else {
                    showAlert(fullMessage, "error");
                }
            });
        <?php endif; ?>
    </script>
</body>
</html>
