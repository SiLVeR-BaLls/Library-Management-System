<?php
include '../config.php';
// Start output buffering to prevent premature output
ob_start();
?>
 
 <title>Browse User</title>

<div class="flex flex-grow">
    <!-- Sidebar Section -->
            <?php include $sidebars[$userType] ?? ''; ?>

    <div class="flex-grow">
        <!-- Fixed Header -->
        <?php include 'include/header.php'; ?>

        <!-- Content Pushed Below the Header -->
            <?php include 'include/TableOfUser.php'; ?>

        <!-- Footer -->
        <footer>
            <?php include 'include/footer.php'; ?>
        </footer>
    </div>
</div>
