<?php
include '../config.php';
?>
<div class="flex  ">
    <!-- Sidebar Section -->
             <?php if (!empty($userType) && isset($sidebars[$userType]) && file_exists($sidebars[$userType])) {
    include $sidebars[$userType]; 
}?><!-- BrowseBook Content Section -->
    <div class="flex-grow ">
        <!-- Header at the Top -->
        <?php include 'include/header.php'; ?>

        <!-- BrowseBook Content -->
        <?php include 'include/AddbookTable.php'; ?>

        <!-- Footer at the Bottom -->
        <footer>
            <?php include 'include/footer.php'; ?>
        </footer>
</div>