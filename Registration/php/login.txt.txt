<?php
   session_start(); // Start the session to manage user data

   $Username = $_POST['Username'];
   $Password = $_POST['Password'];

   // Database connection
   $con = new mysqli("localhost", "root", "", "system");
   if ($con->connect_error) {
       die("Connection failed: " . $con->connect_error);
   } else {
       $stmt = $con->prepare("SELECT * FROM registration WHERE Username = ?");
       $stmt->bind_param("s", $Username);
       $stmt->execute();
       $stmt_result = $stmt->get_result();
       if ($stmt_result->num_rows > 0) {
           $data = $stmt_result->fetch_assoc();
           // Verify the password using password_verify
           if (password_verify($Password, $data['Password'])) {
               // Set session variables
               $_SESSION['username'] = $Username;
               header('Location: profile.php?username=' . urlencode($Username)); // Redirect to profile page
               exit();
           } else {
               echo "<h2>Usernamer does not matchy</h2>";
           }
       } else {
           echo "<h2>Username or Password does not match</h2>";
       }
   }

   $stmt->close();
   $con->close();
?> 
