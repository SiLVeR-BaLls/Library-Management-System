<?php
include '../config.php';
?>


<!-- Main Content Area with Sidebar and BrowseBook Section -->
<div class="flex">
  <!-- Sidebar Section -->
          <?php include $sidebars[$userType] ?? ''; ?>
  <!-- BrowseBook Content Section -->
  <div class="flex-grow ">
    <!-- Header at the Top -->
    <?php include 'include/header.php'; ?>

    <?php include 'include/AddStaffTable.php'; ?>


    <!-- Footer at the Bottom -->
    <footer>
      <?php include 'include/footer.php'; ?>
    </footer>
  </div>
  </div>

  <script src="../../Registration/js/script.js"></script>