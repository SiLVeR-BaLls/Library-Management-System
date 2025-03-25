<?php 
session_start();

// Redirect to dashboard if already logged in
if (isset($_SESSION['admin']) || isset($_SESSION['student']) || isset($_SESSION['librarian']) || isset($_SESSION['faculty'])) {
    header("Location: ../dashboard/page/index.php");
    exit();
}


// Database configuration and connection
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'lms';

$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize status message
$status_message = isset($_SESSION['status_message']) ? $_SESSION['status_message'] : '';
unset($_SESSION['status_message']);

// Initialize failed login attempts and cooldown if not set
if (!isset($_SESSION['failed_attempts'])) {
    $_SESSION['failed_attempts'] = 0;
}

if (!isset($_SESSION['last_failed_attempt'])) {
    $_SESSION['last_failed_attempt'] = 0;
}

// Initialize the cooldown multiplier (starting at 1x = 30 seconds)
if (!isset($_SESSION['cooldown_multiplier'])) {
    $_SESSION['cooldown_multiplier'] = 1;
}

// Disable input fields if 3 failed attempts
$disable_input = ($_SESSION['failed_attempts'] >= 3) ? 'disabled' : '';
$cooldown_message = '';

if ($_SESSION['failed_attempts'] >= 3) {
    $time_since_last_failed = time() - $_SESSION['last_failed_attempt'];
    $cooldown_time = 30 * $_SESSION['cooldown_multiplier']; // Cooldown time increases by multiplier
    if ($time_since_last_failed < $cooldown_time) {
        $remaining_time = $cooldown_time - $time_since_last_failed;
        $cooldown_message = "<span style='color:red;'>Please wait $remaining_time seconds before trying again.</span>";
    } else {
        // Reset failed attempts after cooldown period
        $_SESSION['failed_attempts'] = 0;
        $_SESSION['last_failed_attempt'] = 0;
        $_SESSION['cooldown_multiplier']++; // Increase multiplier after each cooldown
        $disable_input = '';  // Enable input after cooldown
    }
}

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['uname']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (!empty($username) && !empty($password)) {
        $query = "SELECT * FROM `users_info` WHERE username = '$username'";
        $result = mysqli_query($conn, $query);

        if ($row = mysqli_fetch_assoc($result)) {
            if ($row['password'] === $password) {
                // Check the account status
                if ($row['status_log'] == 'pending') {
                    $_SESSION['status_message'] = "Your account is still pending.";
                } elseif ($row['status_log'] == 'approved') {
                    // Reset failed attempts on successful login
                    $_SESSION['failed_attempts'] = 0;
                    $_SESSION['last_failed_attempt'] = 0;
                    $_SESSION['cooldown_multiplier'] = 1; // Reset cooldown multiplier on successful login

                    $_SESSION[$row['U_Type']] = $row;
                    // Redirect to the dashboard/page/index.php after successful login
                    header("Location: ../dashboard/page/index.php");
                    exit();
                } else {
                    $_SESSION['status_message'] = "Your account has been rejected.";
                }
            } else {
                // Increment failed attempt counter and log the time of the failed attempt
                $_SESSION['failed_attempts']++;
                $_SESSION['last_failed_attempt'] = time();
                $_SESSION['status_message'] = "The password does not match.";
            }
        } else {
            // Increment failed attempt counter and log the time of the failed attempt
            $_SESSION['failed_attempts']++;
            $_SESSION['last_failed_attempt'] = time();
            $_SESSION['status_message'] = "The username does not match.";
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/log_in.css">
    <title>Log In</title>
</head>
<body>
    <center>
        <form class="form" method="POST">
            <div class="card">
                <div class="top">
                    <a href="../index.php"><img src="pic/logo.png" alt="Logo"></a>
                    <strong>Library Management System</strong>
                </div>
                
                <a class="login">Log in</a>
                <?php if (!empty($status_message)): ?>
                    <div class="alert" id="statusMessage">
                        <?php echo $status_message; ?>
                    </div>
                <?php endif; ?>
                <!-- Show the cooldown message -->
                <?php if (!empty($cooldown_message)): ?>
                    <div class="cooldown-message">
                        <?php echo $cooldown_message; ?>
                    </div>
                <?php endif; ?>
                <div class="inputBox">
                    <input name="uname" type="text" required="required" <?php echo $disable_input; ?>>
                    <span class="user">Username</span>
                </div>

                <div class="inputBox">
                    <input name="password" type="password" required="required" <?php echo $disable_input; ?>>
                    <span>Password</span>
                </div>

                <div class="inputBox">          
                    <button type="submit" name="submit" class="enter" <?php echo $disable_input; ?>>Enter</button>
                    <p>Already have an account? <a href="sign_up.php">Sign up</a></p>
                </div>
            </div>
        </form>
    </center>

    <script>
       <?php if ($_SESSION['failed_attempts'] >= 3 && $time_since_last_failed < 30 * $_SESSION['cooldown_multiplier']): ?>
    var remainingTime = <?php echo $remaining_time; ?>;
    var countdownInterval = setInterval(function() {
        remainingTime--;
        if (remainingTime <= 0) {
            clearInterval(countdownInterval);
            location.reload(); // Reload to re-enable inputs after cooldown
        }

        // Convert remaining time into hours, minutes, seconds
        var hours = Math.floor(remainingTime / 3600);
        var minutes = Math.floor((remainingTime % 3600) / 60);
        var seconds = remainingTime % 60;

        var timeString = '';
        if (hours > 0) {
            timeString += hours + ' hours ';
        }
        if (minutes > 0) {
            timeString += minutes + ' minutes ';
        }
        if (seconds > 0 || hours == 0 && minutes == 0) {
            timeString += seconds + ' seconds';
        }

        document.querySelector('.cooldown-message').textContent = 'Please wait ' + timeString + ' before trying again.';
    }, 1000);
<?php endif; ?>

    </script>
</body>
</html>
