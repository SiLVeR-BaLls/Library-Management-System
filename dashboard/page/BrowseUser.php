<?php
include '../config.php';
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
        <footer class="bg-blue-600 text-white mt-auto">
            <?php include 'include/footer.php'; ?>
        </footer>
    </div>
</div>
