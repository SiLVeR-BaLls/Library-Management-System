<?php
include 'config.php';
?>
   
<div class="flex flex-1">
    <div class="sidebar">
        <?php
        // Only include sidebar if a valid user type and the file exists
        if (!empty($userType) && isset($sidebars[$userType]) && file_exists($sidebars[$userType])) {
            include $sidebars[$userType];
        }
        ?>
    </div>
    <div class="flex flex-col flex-1">
        <?php include 'include/header.php'; ?>


            <!-- BrowseBook Content -->
            <?php include 'page/include/AddbookTable.php'; ?>

            <!-- Footer at the Bottom -->
            <footer class="mt-auto">
                <?php include 'include/footer.php'; ?>
            </footer>
        </div>
    </div>
