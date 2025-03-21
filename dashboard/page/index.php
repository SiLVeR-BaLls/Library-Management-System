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


<!-- Modal Structure -->
<div id="materialTypeModal" class="modal hidden">
 <!-- Modal Overlay (will close when clicked outside) -->
<div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50" id="modalOverlay">
  <!-- Modal Content -->
  <div  style="color: <?= $text ?>; background: <?= $background ?>;" class="modal-content relative p-6 rounded-lg w-full max-w-4xl opacity-100">
    <!-- Close Button in the top-right corner -->
    <div class="absolute top-2 right-2 p-2">
      <button id="modalCloseBtn" class="text-gray-500 text-2xl">Close</button>
    </div>
      
      <!-- Form Container -->
      <form id="materialTypeForm">
        <h2 class="text-2xl font-bold mb-6 text-center">Material Type Filters</h2>

        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 mb-6">
            <h3 class="col-span-4 text-xl font-semibold">Select Material Types</h3>
            <!-- Material Type Options -->
            <div class="flex items-center">
              <label class="flex items-center space-x-2">
                <input type="radio" name="materialType" value="Book" class="h-4 w-4" />
                <span>Book</span>
              </label>
            </div>
            <div class="flex items-center">
              <label class="flex items-center space-x-2">
                <input type="radio" name="materialType" value="Computer File" class="h-4 w-4" />
                <span>Computer File</span>
              </label>
            </div>
            <div class="flex items-center">
              <label class="flex items-center space-x-2">
                <input type="radio" name="materialType" value="Electronic Book" class="h-4 w-4" />
                <span>Electronic Book (E-Book)</span>
              </label>
            </div>
            <div class="flex items-center">
              <label class="flex items-center space-x-2">
                <input type="radio" name="materialType" value="Equipment" class="h-4 w-4" />
                <span>Equipment</span>
              </label>
            </div>
            <div class="flex items-center">
              <label class="flex items-center space-x-2">
                <input type="radio" name="materialType" value="Kit" class="h-4 w-4" />
                <span>Kit</span>
              </label>
            </div>
            <div class="flex items-center">
              <label class="flex items-center space-x-2">
                <input type="radio" name="materialType" value="Manuscript Language Material" class="h-4 w-4" />
                <span>Manuscript Language Material</span>
              </label>
            </div>
            <div class="flex items-center">
              <label class="flex items-center space-x-2">
                <input type="radio" name="materialType" value="Map" class="h-4 w-4" />
                <span>Map</span>
              </label>
            </div>
            <div class="flex items-center">
              <label class="flex items-center space-x-2">
                <input type="radio" name="materialType" value="Mixed Material" class="h-4 w-4" />
                <span>Mixed Material</span>
              </label>
            </div>
            <div class="flex items-center">
              <label class="flex items-center space-x-2">
                <input type="radio" name="materialType" value="Music" class="h-4 w-4" />
                <span>Music (Printed)</span>
              </label>
            </div>
            <div class="flex items-center">
              <label class="flex items-center space-x-2">
                <input type="radio" name="materialType" value="Picture" class="h-4 w-4" />
                <span>Picture</span>
              </label>
            </div>
            <div class="flex items-center">
              <label class="flex items-center space-x-2">
                <input type="radio" name="materialType" value="Serial" class="h-4 w-4" />
                <span>Serial</span>
              </label>
            </div>
            <div class="flex items-center">
              <label class="flex items-center space-x-2">
                <input type="radio" name="materialType" value="Musical Sound Recording" class="h-4 w-4" />
                <span>Musical Sound Recording</span>
              </label>
            </div>
            <div class="flex items-center">
              <label class="flex items-center space-x-2">
                <input type="radio" name="materialType" value="NonMusical Sound Recording" class="h-4 w-4" />
                <span>Non-Musical Sound Recording</span>
              </label>
            </div>
            <div class="flex items-center">
              <label class="flex items-center space-x-2">
                <input type="radio" name="materialType" value="Video" class="h-4 w-4" />
                <span>Video</span>
              </label>
            </div>
          </div>

          <!-- SubType Section -->
          <div class="mb-6">
            <h3 class="text-xl font-semibold">Select SubType</h3>
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 mt-4">
              <!-- SubType Radio Buttons -->
              <div class="flex items-center">
                <label class="flex items-center space-x-2">
                  <input type="radio" name="subType" value="Not Assigned" class="h-4 w-4" />
                  <span>Not Assigned</span>
                </label>
              </div>
              <div class="flex items-center">
                <label class="flex items-center space-x-2">
                  <input type="radio" name="subType" value="Braille" class="h-4 w-4" />
                  <span>Braille</span>
                </label>
              </div>
              <div class="flex items-center">
                <label class="flex items-center space-x-2">
                  <input type="radio" name="subType" value="Hardcover" class="h-4 w-4" />
                  <span>Hardcover</span>
                </label>
              </div>
              <div class="flex items-center">
                <label class="flex items-center space-x-2">
                  <input type="radio" name="subType" value="LargePrint" class="h-4 w-4" />
                  <span>Large Print</span>
                </label>
              </div>
              <div class="flex items-center">
                <label class="flex items-center space-x-2">
                  <input type="radio" name="subType" value="Paperback" class="h-4 w-4" />
                  <span>Paperback</span>
                </label>
              </div>
              <div class="flex items-center">
                <label class="flex items-center space-x-2">
                  <input type="radio" name="subType" value="Picture" class="h-4 w-4" />
                  <span>Picture</span>
                </label>
              </div>
              <div class="flex items-center">
                <label class="flex items-center space-x-2">
                  <input type="radio" name="subType" value="Dictionary" class="h-4 w-4" />
                  <span>Dictionary</span>
                </label>
              </div>
            </div>
          </div>

        <!-- Action Buttons -->
        <div class="flex gap-4 mt-6 justify-center">
          <button type="button" id="filterApply" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Apply Filter</button>
          <button type="button" id="filterClear" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Clear Filters</button>
        </div>
      </form>
    </div>
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