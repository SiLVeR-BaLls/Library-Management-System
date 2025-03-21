<?php

session_start();

// Database Connection
$conn = new mysqli('localhost', 'root', '', 'lms');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);



// Function to verify if ID exists in the database
function getUserData($conn, $idno)
{
    $query = "SELECT * FROM users_info WHERE IDno = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $idno);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Initialize user data to prevent undefined variable issues
$userData = null;

// Determine User Type
$userTypes = ['admin', 'student', 'librarian', 'faculty'];
$idno = null;
foreach ($userTypes as $type) {
    if (!empty($_SESSION[$type]['IDno'])) {
        $idno = $_SESSION[$type]['IDno'];
        break;
    }
}
// Fetch and verify user data
if (!empty($idno)) {
    $userData = getUserData($conn, $idno);

    if ($userData) {
        $_SESSION['user_data'] = $userData;

        // Redirect the user based on U_Type
        if (in_array($userData['U_Type'], ['admin', 'student', 'librarian', 'faculty'])) {
            header("Location: dashboard/page/index.php");
            exit();
        }
    }
}





// for fetching the latest theme settings from the database

// Fetch the latest theme settings from the database
$result = $conn->query("SELECT * FROM settings ORDER BY id DESC LIMIT 1");
$settings = $result->fetch_assoc();

// Retrieve color settings
$background = $settings['background_color'] ?? '';
$text1 = $settings['text_color1'] ?? '';
$text2 = $settings['text_color2'] ?? '';
$button = $settings['button_color'] ?? '';
$button_hover = $settings['button_hover_color'] ?? '';
$button_active = $settings['button_active_color'] ?? ''; 
$sidebar_hover = $settings['sidebar_hover_color'] ?? ''; 
$sidebar_active = $settings['sidebar_active_color'] ?? ''; 
$header = $settings['header_color'] ?? '';
$footer = $settings['footer_color'] ?? '';
$sidebar = $settings['sidebar_color'] ?? '';
$logo = !empty($settings['logo']) ? '../../../pic/scr/' . $settings['logo'] : 'default-logo.png';
?>

<style>
        /* Apply custom button colors */
        .btn {
            background-color: <?= $button ?>;
            color: white;
        }

        /* Button hover state based on DB value */
        .btn:hover {
            background-color: <?= $button_hover ?>;
        }

        /* Button active state based on DB value */
        .btn:active {
            background-color: <?= $button_active ?>;
        }

        /* Sidebar item hover color from DB */
        .sidebar-item:hover {
            background-color: <?= $sidebar_hover ?>;
        }

        /* Sidebar item active color from DB */
        .sidebar-item.active {
            background-color: <?= $sidebar_active ?>;
        }
    </style>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex flex-col min-h-screen bg-gray-100 text-gray-900">

    <!-- Main Content Area with Sidebar and BrowseBook Section -->
    <div class="flex ">
    <!-- Sidebar Section -->
    <!-- BrowseBook Content Section -->
    <div class="flex flex-col w-screen">
        <!-- Header at the Top -->
        <div class="flex flex-col w-screen">
        <!-- Header at the Top -->
        <?php include 'dashboard/page/include/header.php'; ?>

        <!-- BrowseBook php and script -->
        <?php include 'dashboard/page/include/BrowseBook.php'; ?>

  <div id="materialTypeModal" class="modal hidden">
    <!-- Modal Content -->
    <div class="modal-content flex-grow bg-white">
      <!-- Modal Header -->
      <h4 class="text-center text-xl font-bold mb-4">Select Material Type</h4>
      <div id="materialTypeForm" class="flex flex-col">

        <!-- Modal Overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">

          <!-- Form Container -->
          <form class="bg-white p-6 rounded-lg shadow-lg w-full max-w-4xl" id="materialTypeForm" style="background: <?= $sidebar ?>; color: <?= $text1 ?>;">

            <!-- Title -->
            <h2 class="text-2xl font-bold mb-6 text-center">Material Type Filters</h2>

            <!-- Material Type Options -->
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 mb-6">
              <h3 class="col-span-4 text-xl font-semibold">Select Material Types</h3>

              <!-- Material Type 1 -->
              <div class="flex items-center">
                <label class="flex items-center space-x-2">
                  <input type="checkbox" name="materialType" value="Book" class="h-4 w-4" />
                  <span>Book</span>
                </label>
              </div>
              <div class="flex items-center">
                <label class="flex items-center space-x-2">
                  <input type="checkbox" name="materialType" value="Computer File" class="h-4 w-4" />
                  <span>Computer File</span>
                </label>
              </div>
              <div class="flex items-center">
                <label class="flex items-center space-x-2">
                  <input type="checkbox" name="materialType" value="Electronic Book" class="h-4 w-4" />
                  <span>Electronic Book (E-Book)</span>
                </label>
              </div>
              <div class="flex items-center">
                <label class="flex items-center space-x-2">
                  <input type="checkbox" name="materialType" value="Equipment" class="h-4 w-4" />
                  <span>Equipment</span>
                </label>
              </div>

              <!-- Material Type 2 -->
              <div class="flex items-center">
                <label class="flex items-center space-x-2">
                  <input type="checkbox" name="materialType" value="Kit" class="h-4 w-4" />
                  <span>Kit</span>
                </label>
              </div>
              <div class="flex items-center">
                <label class="flex items-center space-x-2">
                  <input type="checkbox" name="materialType" value="Manuscript Language Material" class="h-4 w-4" />
                  <span>Manuscript Language Material</span>
                </label>
              </div>
              <div class="flex items-center">
                <label class="flex items-center space-x-2">
                  <input type="checkbox" name="materialType" value="Map" class="h-4 w-4" />
                  <span>Map</span>
                </label>
              </div>
              <div class="flex items-center">
                <label class="flex items-center space-x-2">
                  <input type="checkbox" name="materialType" value="Mixed Material" class="h-4 w-4" />
                  <span>Mixed Material</span>
                </label>
              </div>

              <!-- Material Type 3 -->
              <div class="flex items-center">
                <label class="flex items-center space-x-2">
                  <input type="checkbox" name="materialType" value="Music" class="h-4 w-4" />
                  <span>Music (Printed)</span>
                </label>
              </div>
              <div class="flex items-center">
                <label class="flex items-center space-x-2">
                  <input type="checkbox" name="materialType" value="Picture" class="h-4 w-4" />
                  <span>Picture</span>
                </label>
              </div>
              <div class="flex items-center">
                <label class="flex items-center space-x-2">
                  <input type="checkbox" name="materialType" value="Serial" class="h-4 w-4" />
                  <span>Serial</span>
                </label>
              </div>
              <div class="flex items-center">
                <label class="flex items-center space-x-2">
                  <input type="checkbox" name="materialType" value="Musical Sound Recording" class="h-4 w-4" />
                  <span>Musical Sound Recording</span>
                </label>
              </div>

              <!-- Material Type 4 -->
              <div class="flex items-center">
                <label class="flex items-center space-x-2">
                  <input type="checkbox" name="materialType" value="NonMusical Sound Recording" class="h-4 w-4" />
                  <span>Non-Musical Sound Recording</span>
                </label>
              </div>
              <div class="flex items-center">
                <label class="flex items-center space-x-2">
                  <input type="checkbox" name="materialType" value="Video" class="h-4 w-4" />
                  <span>Video</span>
                </label>
              </div>

              <!-- Journal Option -->
              <div class="flex items-center">
                <label class="flex items-center space-x-2">
                  <input type="checkbox" id="filterJournal" class="h-4 w-4" />
                  <span>Journal</span>
                </label>
              </div>

              <!-- All Option -->
              <div class="flex items-center">
                <label class="flex items-center space-x-2">
                  <input type="checkbox" id="filterAll" class="h-4 w-4" />
                  <span>Show All</span>
                </label>
              </div>
            </div>

            <!-- SubType Section -->
            <div class="mb-6">
              <h3 class="text-xl font-semibold">Select SubType</h3>
              <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 mt-4">
                <!-- SubType Checkboxes -->
                <div class="flex items-center">
                  <label class="flex items-center space-x-2">
                    <input type="checkbox" name="subType" value="not_assigned" class="h-4 w-4" />
                    <span>No SubType Assigned</span>
                  </label>
                </div>
                <div class="flex items-center">
                  <label class="flex items-center space-x-2">
                    <input type="checkbox" name="subType" value="Braille" class="h-4 w-4" />
                    <span>Braille</span>
                  </label>
                </div>
                <div class="flex items-center">
                  <label class="flex items-center space-x-2">
                    <input type="checkbox" name="subType" value="Hardcover" class="h-4 w-4" />
                    <span>Hardcover</span>
                  </label>
                </div>
                <div class="flex items-center">
                  <label class="flex items-center space-x-2">
                    <input type="checkbox" name="subType" value="LargePrint" class="h-4 w-4" />
                    <span>Large Print</span>
                  </label>
                </div>
                <div class="flex items-center">
                  <label class="flex items-center space-x-2">
                    <input type="checkbox" name="subType" value="Paperback" class="h-4 w-4" />
                    <span>Paperback</span>
                  </label>
                </div>
                <div class="flex items-center">
                  <label class="flex items-center space-x-2">
                    <input type="checkbox" name="subType" value="Picture Book" class="h-4 w-4" />
                    <span>Picture Book</span>
                  </label>
                </div>
                <div class="flex items-center">
                  <label class="flex items-center space-x-2">
                    <input type="checkbox" name="subType" value="Dictionary" class="h-4 w-4" />
                    <span>Dictionary</span>
                  </label>
                </div>
                <div class="flex items-center">
                  <label class="flex items-center space-x-2">
                    <input type="checkbox" name="subType" value="All" class="h-4 w-4" />
                    <span>All</span>
                  </label>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 mt-6 justify-center">
              <button type="button" id="filterApply" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Apply Filter</button>
              <button type="button" id="filterClear" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Clear Filters</button>
              <button type="button" id="filterCancel" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</button>
            </div>

          </form>
        </div>
      </div>
    </div>

  </div>


  <!-- for filter -->
  <script>
    $(document).ready(function() {
      // Hide the modal initially on page load
      $('#materialTypeModal').hide();

      // Open the modal when the filter button is clicked
      $('#filterButton').on('click', function() {
        $('#materialTypeModal').show(); // Show the modal
      });

      // Close the modal if the cancel button is clicked
      $('#filterCancel').on('click', function() {
        $('#materialTypeModal').hide(); // Hide the modal
      });

      // Apply the filter when the "Apply Filter" button is clicked
      $('#filterApply').on('click', function() {
        var selectedTypes = [];
        $('input[name="materialType"]:checked').each(function() {
          selectedTypes.push($(this).val().toLowerCase());
        });

        var selectedSubTypes = [];
        $('input[name="subType"]:checked').each(function() {
          selectedSubTypes.push($(this).val().toLowerCase());
        });

        // Filter the rows based on selected material types and subtypes
        $('#bookTableBody tr').each(function() {
          var rowMaterialType = $(this).find('.MT').text().toLowerCase();
          var rowSubType = $(this).find('.subtype').text().toLowerCase(); // Assuming the subtypes are stored in a column with class 'subtype'

          if (
            (selectedTypes.length === 0 || selectedTypes.includes(rowMaterialType)) &&
            (selectedSubTypes.length === 0 || selectedSubTypes.includes(rowSubType))
          ) {
            $(this).show(); // Show row if it matches the criteria
          } else {
            $(this).hide(); // Hide row if it doesn't match the criteria
          }
        });

        $('#materialTypeModal').hide(); // Close the modal after filtering
      });

      // Clear all filters when the "Clear Filters" button is clicked
      $('#filterClear').on('click', function() {
        $('input[name="materialType"]').prop('checked', false);
        $('input[name="subType"]').prop('checked', false);
        $('#bookTableBody tr').show(); // Show all rows (clear all filters)

        $('#materialTypeModal').hide();
      });

      // Close the modal if clicked outside the modal
      $(window).on('click', function(event) {
        if ($(event.target).is('#materialTypeModal')) {
          $('#materialTypeModal').hide(); // Hide the modal
        }
      });
    });
  </script>



        </div>
        <!-- Footer at the Bottom -->
        <footer class="mt-auto">
            <?php include 'dashboard/page/include/footer.php'; ?>
        </footer>
    </div>

</div>
</body>

</html>