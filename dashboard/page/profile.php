<?php
include '../config.php';
?>


<!-- Main Content Area with Sidebar and BrowseBook Section -->
<iv class="flex ">
    <!-- Sidebar Section -->
<!-- Sidebar PHP Logic -->
<div class="sidebar">
    <?php
    // Include the appropriate sidebar based on the user type
    if ($userType == 'admin') {
        include 'include/side_ad.php'; // Admin Sidebar
    } elseif ($userType == 'student') {
        include 'include/side_stu.php'; // Student Sidebar
    } elseif ($userType == 'librarian') {
        include 'include/side_lib.php'; // Librarian Sidebar
    } elseif ($userType == 'faculty') {
        include 'include/side_fac.php'; // faculty Sidebar
    } else {
        // Optional: You can handle an unexpected user type here
        echo "<p>Error: Invalid user type.</p>";
    }
    ?>
</div>    <!-- BrowseBook Content Section -->
    <div class="flex-grow ">
        <!-- Header at the Top -->
        <?php include 'include/header.php'; ?>

        <!-- BrowseBook Content -->
        <?php include 'include/profile_extend.php'; ?>
        <!-- Footer at the Bottom -->
        <footer>
            <?php include 'include/footer.php'; ?>
        </footer>
    </div>
</iv>