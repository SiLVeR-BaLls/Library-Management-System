<?php
include '../config.php';
?>
<?php
// Fetch existing departments only (no insert)
$departments = [];

$query = "SELECT * FROM Department ORDER BY name ASC";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
}
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