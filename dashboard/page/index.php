<?php
include '../config.php';

?>


<!-- Main Content Area with Sidebar and BrowseBook Section -->
<div class="flex ">
    <!-- Sidebar PHP Logic -->
    <div class="sidebar">

        <?php include $sidebars[$userType] ?? ''; ?>

    </div>
    <!-- BrowseBook Content Section -->
    <div class="flex flex-col w-screen">
        <!-- Header at the Top -->
        <?php include 'include/header.php'; ?>

        <!-- BrowseBook php and script -->
        <?php include 'include/BrowseBook.php'; ?>

        <!-- Footer at the Bottom -->
        <footer class="mt-auto">
            <?php include 'include/footer.php'; ?>
        </footer>
    </div>

</div>





<style>
    @media (max-width: 768px) {

        /* Hide Co-authors and Extent columns */
        .coauthor,
        .extent,
        th.coauthor,
        th.extent {
            display: none;
        }
    }
</style>