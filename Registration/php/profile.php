<?php
// Configuration
include '../../dashboard/config.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: log_in.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$query = "SELECT * FROM users_info
          JOIN address ON users_info.IDno = address.IDno
          JOIN user_details ON users_info.IDno = user_details.IDno
          JOIN user_log ON users_info.IDno = user_log.IDno
          JOIN contact ON users_info.IDno = contact.IDno
          WHERE users_info.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$user = $result->fetch_assoc();

if (!$user) {
    die("User data not found.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../css/profile.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .edit-profile-section {
            display: none;
        }
        .edit-profile-section input,
        .edit-profile-section select {
            display: block;
            margin-bottom: 10px;
            width: 100%;
            padding: 8px;
        }
        .buttons {
            text-align: center;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .edit-button {
            background-color: #007bff;
        }
        .logout-button {
            background-color: #dc3545;
        }
        .save-button {
            background-color: #28a745;
        }
        .cancel-button {
            background-color: #6c757d;
        }
        .edit-button:hover {
            background-color: #0056b3;
        }
        .logout-button:hover {
            background-color: #c82333;
        }
        .save-button:hover {
            background-color: #218838;
        }
        .cancel-button:hover {
            background-color: #5a6268;
        }
    </style>
</head>

<body>
    <header>
        <h1>User Profile</h1>
    </header>
    <main>
        <div class="profile-container">
            <div class="profile-header">
                <div class="profile-photo">
                    <img src="<?php echo htmlspecialchars($user['profile_photo']) ? htmlspecialchars($user['profile_photo']) : 'default-profile.png'; ?>" alt="Profile Photo">
                </div>
                <h1><?php echo htmlspecialchars($user['Fname']) . ' ' . htmlspecialchars($user['Sname']); ?></h1>
            </div>
            
            <!-- Other profile sections -->
            <div class="profile-section">
                <h2>Personal Information</h2>
                <div class="profile-box">
                    <p><strong>First Name:</strong> <?php echo htmlspecialchars($user['Fname']); ?></p>
                    <p><strong>Last Name:</strong> <?php echo htmlspecialchars($user['Sname']); ?></p>
                    <p><strong>Middle Name:</strong> <?php echo htmlspecialchars($user['Mname']); ?></p>
                    <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
                    <p><strong>Extension:</strong> <?php echo htmlspecialchars($user['Ename']); ?></p>
                    <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($user['DOB']); ?></p>
                </div>
            </div>

            <div class="profile-section">
                <h2>Education Information</h2>
                <div class="profile-box">
                    <p><strong>Grade Level:</strong> <?php echo htmlspecialchars($user['GRAD_LVL']); ?></p>
                    <p><strong>Graduation Year:</strong> <?php echo htmlspecialchars($user['GRAD_YR']); ?></p>
                    <p><strong>College:</strong> <?php echo htmlspecialchars($user['college']); ?></p>
                    <p><strong>Course:</strong> <?php echo htmlspecialchars($user['course']); ?></p>
                    <p><strong>Year Level:</strong> <?php echo htmlspecialchars($user['yrLVL']); ?></p>
                    <p><strong>Section:</strong> <?php echo htmlspecialchars($user['section']); ?></p>
                </div>
            </div>

            <div class="profile-section">
                <h2>Address Information</h2>
                <div class="profile-box">
                    <p><strong>Municipality:</strong> <?php echo htmlspecialchars($user['municipality']); ?></p>
                    <p><strong>City:</strong> <?php echo htmlspecialchars($user['city']); ?></p>
                    <p><strong>Barangay:</strong> <?php echo htmlspecialchars($user['barangay']); ?></p>
                    <p><strong>Province:</strong> <?php echo htmlspecialchars($user['province']); ?></p>
                </div>
            </div>

            <div class="profile-section">
                <h2>Contact Information</h2>
                <div class="profile-box">
                    <p><strong>Email 1:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Email 2:</strong> <?php echo htmlspecialchars($user['email2']); ?></p>
                    <p><strong>Contact No. 1:</strong> <?php echo htmlspecialchars($user['contact']); ?></p>
                    <p><strong>Contact No. 2:</strong> <?php echo htmlspecialchars($user['con2']); ?></p>
                </div>
            </div>

            <div class="profile-section">
                <h2>Account Information</h2>
                <div class="profile-box">
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                    <p><strong>User Type:</strong> <?php echo htmlspecialchars($user['U_Type']); ?></p>
                    <p><strong>Access Level:</strong> <?php echo htmlspecialchars($user['A_LVL']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($user['status']); ?></p>
                </div>
            </div>

            <div class="buttons">
                <button id="edit-mode-button" class="button edit-button">Edit Profile</button>
                <a href="logout.php" class="button logout-button">Logout</a>
            </div>

            <!-- Edit Profile Section -->
            <div id="edit-profile-section" class="edit-profile-section">
                <h2>Edit Profile</h2>
                <form id="edit-profile-form" action="update_profile.php" method="post" enctype="multipart/form-data">
                    <!-- Personal Information -->
                    <div class="profile-box">
                        <label for="first-name">First Name:</label>
                        <input type="text" id="first-name" name="first_name" value="<?php echo htmlspecialchars($user['Fname']); ?>" required>
                        <label for="last-name">Last Name:</label>
                        <input type="text" id="last-name" name="last_name" value="<?php echo htmlspecialchars($user['Sname']); ?>" required>
                        <label for="middle-name">Middle Name:</label>
                        <input type="text" id="middle-name" name="middle_name" value="<?php echo htmlspecialchars($user['Mname']); ?>" required>
                        <!-- Add other fields as needed -->
                    </div>
                    <div class="profile-box">
                        <label for="profile-photo">Profile Photo:</label>
                        <input type="file" id="profile-photo" name="profile_photo">
                    </div>
                    <div class="buttons">
                        <button type="submit" class="button save-button">Save</button>
                        <button type="button" id="cancel-edit" class="button cancel-button">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('edit-mode-button').addEventListener('click', function() {
            document.getElementById('edit-profile-section').style.display = 'block';
            document.querySelector('.profile-container').style.display = 'none';
        });

        document.getElementById('cancel-edit').addEventListener('click', function() {
            document.getElementById('edit-profile-section').style.display = 'none';
            document.querySelector('.profile-container').style.display = 'block';
        });
    </script>
</body>

</html>
