<?php
  include '../config.php';

  // Determine User Type
  $userTypes = ['admin', 'student', 'librarian', 'faculty'];
  $userType = null;
  $idno = null;

  foreach ($userTypes as $type) {
    if (!empty($_SESSION[$type]['IDno'])) {
      $userType = $type;
      $idno = $_SESSION[$type]['IDno'];
      break;
    }
  }

  // Define the main DDC ranges for the dropdown
  $mainDDCClasses = [
    'all' => 'Display All Books',
    'other' => 'Books Other',
    '000-099' => 'General Works & Computer Science',
    '100-199' => 'Philosophy & Psychology',
    '200-299' => 'Religion',
    '300-399' => 'Social Sciences',
    '400-499' => 'Language',
    '500-599' => 'Pure Sciences',
    '600-699' => 'Technology (Applied Sciences)',
    '700-799' => 'The Arts',
    '800-899' => 'Literature & Rhetoric',
    '900-999' => 'History & Geography'
  ];

  // Initialize filter variables to retain selections if the user returns
  $searchTerm = isset($_GET['searchTerm']) ? $_GET['searchTerm'] : '';
  $searchBy = isset($_GET['searchByOption']) ? $_GET['searchByOption'] : 'all';
  $materialType = isset($_GET['materialType']) ? $_GET['materialType'] : 'all';
  $subType = isset($_GET['subType']) ? $_GET['subType'] : 'all';
  $ddcMainClass = isset($_GET['ddcMainClass']) ? $_GET['ddcMainClass'] : 'all';

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

    <div class="p-6">
      <form action="search_results.php" method="GET" style="background: <?= $background ?> ?>;" class="p-6 rounded-xl shadow-lg space-y-6 w-full">
        <div class="flex w-full items-center">
          <input type="text" placeholder="Enter search term..." class="w-full p-4 rounded-lg" id="searchInput" name="searchTerm" value="<?= htmlspecialchars($searchTerm) ?>" />
          <button type="submit" class="ml-4 px-6 search-btn block  px-4 py-2 rounded-lg text-center cursor-pointer transition duration-300  py-2 rounded-lg font-semibold">Search</button>
        </div>

        <div class="space-y-2 w-full">
          <p class="text-sm font-semibold" style="color: <?= $text1 ?>;">Search By:</p>
          <div id="searchButtons" class="flex w-full gap-3">
            <label class="search-label flex-1 cursor-pointer">
              <input type="radio" name="searchByOption" value="title" class="hidden" id="titleButton" <?= ($searchBy === 'title') ? 'checked' : '' ?>>
              <span class="search-btn block  px-4 py-2 rounded-lg text-center cursor-pointer transition duration-300 <?= ($searchBy === 'title') ? 'active-btn' : '' ?>">Title</span>
            </label>
            <label class="search-label flex-1 cursor-pointer">
              <input type="radio" name="searchByOption" value="all" class="hidden" id="allButton" <?= ($searchBy === 'all') ? 'checked' : '' ?>>
              <span class="search-btn block bg-blue-600 text-white px-4 py-2 rounded-lg text-center cursor-pointer transition duration-300 <?= ($searchBy === 'all') ? 'active-btn' : '' ?>">All</span>
            </label>
            <label class="search-label flex-1 cursor-pointer">
              <input type="radio" name="searchByOption" value="author" class="hidden" id="authorButton" <?= ($searchBy === 'author') ? 'checked' : '' ?>>
              <span class="search-btn block  px-4 py-2 rounded-lg text-center cursor-pointer transition duration-300 <?= ($searchBy === 'author') ? 'active-btn' : '' ?>">Author</span>
            </label>
            <label class="search-label flex-1 cursor-pointer">
              <input type="radio" name="searchByOption" value="coauthor" class="hidden" id="coauthorButton" <?= ($searchBy === 'coauthor') ? 'checked' : '' ?>>
              <span class="search-btn block  px-4 py-2 rounded-lg text-center cursor-pointer transition duration-300 <?= ($searchBy === 'coauthor') ? 'active-btn' : '' ?>">Co-Author</span>
            </label>
          </div>
        </div>

        <p class="text-sm font-semibold mt-6" style="color: <?= $text1 ?>;">Select Category:</p>
        <div class="flex space-x-6">
          <div class="flex-1">
            <h3 class="text-xl font-semibold mb-3" style="color: <?= $text ?>">Select Material Type</h3>
            <select name="materialType" class="w-full p-2 border rounded  ">
              <option value="all" <?= ($materialType === 'all') ? 'selected' : '' ?>>All</option>
              <option value="Book" <?= ($materialType === 'Book') ? 'selected' : '' ?>>Book</option>
              <option value="Kit" <?= ($materialType === 'Kit') ? 'selected' : '' ?>>Kit</option>
              <option value="Music Printed" <?= ($materialType === 'Music Printed') ? 'selected' : '' ?>>Music Printed</option>
              <option value="Non-Musical Sound Recording" <?= ($materialType === 'Non-Musical Sound Recording') ? 'selected' : '' ?>>Non-Musical Sound Recording</option>
              <option value="Computer File" <?= ($materialType === 'Computer File') ? 'selected' : '' ?>>Computer File</option>
              <option value="Manuscript Language Material" <?= ($materialType === 'Manuscript Language Material') ? 'selected' : '' ?>>Manuscript Language Material</option>
              <option value="Map" <?= ($materialType === 'Map') ? 'selected' : '' ?>>Map</option>
              <option value="Series" <?= ($materialType === 'Series') ? 'selected' : '' ?>>Series</option>
              <option value="Journal" <?= ($materialType === 'Journal') ? 'selected' : '' ?>>Journal</option>
              <option value="Equipment" <?= ($materialType === 'Equipment') ? 'selected' : '' ?>>Equipment</option>
              <option value="Mixed Materials" <?= ($materialType === 'Mixed Materials') ? 'selected' : '' ?>>Mixed Materials</option>
              <option value="Musical Sound Recordings" <?= ($materialType === 'Musical Sound Recordings') ? 'selected' : '' ?>>Musical Sound Recordings</option>
              <option value="Article" <?= ($materialType === 'Article') ? 'selected' : '' ?>>Article</option>
              <option value="Magazine" <?= ($materialType === 'Magazine') ? 'selected' : '' ?>>Magazine</option>
              <option value="Newspaper" <?= ($materialType === 'Newspaper') ? 'selected' : '' ?>>Newspaper</option>
              <option value="Thesis" <?= ($materialType === 'Thesis') ? 'selected' : '' ?>>Thesis</option>
            </select>
          </div>

          <div class="flex-1">
            <h3 class="text-xl font-semibold mb-3" style="color: <?= $text ?>">Select Subtype</h3>
            <select name="subType" class="w-full p-2 border rounded  ">
              <option value="all" <?= ($subType === 'all') ? 'selected' : '' ?>>All</option>
              <option value="NotAssigned" <?= ($subType === 'NotAssigned') ? 'selected' : '' ?>>NotAssigned</option>
              <option value="Hardcover" <?= ($subType === 'Hardcover') ? 'selected' : '' ?>>Hardcover</option>
              <option value="Microform" <?= ($subType === 'Microform') ? 'selected' : '' ?>>Microform</option>
              <option value="Online" <?= ($subType === 'Online') ? 'selected' : '' ?>>Online</option>
              <option value="Paperback" <?= ($subType === 'Paperback') ? 'selected' : '' ?>>Paperback</option>
              <option value="Baraille" <?= ($subType === 'Baraille') ? 'selected' : '' ?>>Baraille</option>
              <option value="Dictionary" <?= ($subType === 'Dictionary') ? 'selected' : '' ?>>Dictionary</option>
              <option value="Picture" <?= ($subType === 'Picture') ? 'selected' : '' ?>>Picture</option>
              <option value="Video" <?= ($subType === 'Video') ? 'selected' : '' ?>>Video</option>
              <option value="Ebook" <?= ($subType === 'Ebook') ? 'selected' : '' ?>>Ebook</option>
            </select>
          </div>

          <div class="flex-1">
            <h3 class="text-xl font-semibold mb-3" style="color: <?= $text ?>">Select DDC Main Class</h3>
            <select name="ddcMainClass" id="ddcMainClass" class="w-full p-2 border rounded  ">
              <option value="all" <?= ($ddcMainClass === 'all') ? 'selected' : '' ?>>Display All Books</option>
              <option value="other" <?= ($ddcMainClass === 'other') ? 'selected' : '' ?>>Books Other </option>
              <option value="000-099" <?= ($ddcMainClass === '000-099') ? 'selected' : '' ?>>General Works & Computer Science</option>
              <option value="100-199" <?= ($ddcMainClass === '100-199') ? 'selected' : '' ?>>Philosophy & Psychology</option>
              <option value="200-299" <?= ($ddcMainClass === '200-299') ? 'selected' : '' ?>>Religion</option>
              <option value="300-399" <?= ($ddcMainClass === '300-399') ? 'selected' : '' ?>>Social Sciences</option>
              <option value="400-499" <?= ($ddcMainClass === '400-499') ? 'selected' : '' ?>>Language</option>
              <option value="500-599" <?= ($ddcMainClass === '500-599') ? 'selected' : '' ?>>Pure Sciences</option>
              <option value="600-699" <?= ($ddcMainClass === '600-699') ? 'selected' : '' ?>>Technology (Applied Sciences)</option>
              <option value="700-799" <?= ($ddcMainClass === '700-799') ? 'selected' : '' ?>>The Arts</option>
              <option value="800-899" <?= ($ddcMainClass === '800-899') ? 'selected' : '' ?>>Literature & Rhetoric</option>
              <option value="900-999" <?= ($ddcMainClass === '900-999') ? 'selected' : '' ?>>History & Geography</option>
            </select>
          </div>
        </div>
      </form>
    </div>

    <footer class="mt-auto">
      <?php include 'include/footer.php'; ?>
    </footer>
  </div>
</div>

<script>
  const searchLabels = document.querySelectorAll('#searchButtons .search-label');

  searchLabels.forEach(label => {
    label.addEventListener('click', () => {
      searchLabels.forEach(lbl => lbl.querySelector('span').classList.remove('active-btn'));
      label.querySelector('span').classList.add('active-btn');
      const radio = label.querySelector('input[type="radio"]');
      radio.checked = true;
    });
  });

  document.addEventListener('DOMContentLoaded', () => {
    const selectedOption = document.querySelector('input[name="searchByOption"]:checked');
    if (selectedOption) {
      const label = selectedOption.closest('.search-label');
      if (label && label.querySelector('span')) {
        label.querySelector('span').classList.add('active-btn');
      }
    }
  });
</script>

<style>
  .search-btn {
    background-color: <?= $button ?>;
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
  }

  .search-btn:hover {
    background-color: <?= $button_hover ?>;
    color: white;
  }

  .search-btn.active-btn {
    background-color: <?= $button_active ?>;
    color: white;
  }
</style>