<?php
  include '../config.php';

  // Initialize message variables
  $message = "";
  $message_type = "";

  // Get the book title from the query string
  $title = $_GET['title'] ?? '';

  if ($title) {
    // Fetch the book details
    $sql = "SELECT * FROM Book WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $title);

    if ($stmt->execute()) {
      $result = $stmt->get_result();

      if ($result->num_rows === 0) {
        $message = "No book found with that title.";
        $message_type = "error";
      } else {
        $book = $result->fetch_assoc();

        // Helper function to fetch related data
        function fetch_related_data($conn, $query, $title)
        {
          $stmt = $conn->prepare($query);
          $stmt->bind_param("s", $title);
          $stmt->execute();
          return $stmt->get_result();
        }

        // Fetch related data
        $coAuthorsResult = fetch_related_data($conn, "SELECT * FROM CoAuthor WHERE book_id = ?", $title);
        $subjectsResult = fetch_related_data($conn, "SELECT * FROM Subject WHERE book_id = ?", $title);
      }
    } else {
      $message = "Error executing query: " . $stmt->error;
      $message_type = "error";
    }
    $stmt->close();
  } else {
    $message = "No book title provided.";
    $message_type = "error";
  }
?>


<style>
  .hidden {
    display: none;
  }

  /* Style for the input fields */
  input.form-control,
  select.form-select,
  textarea.form-control {
    border-left: 2px solid #333333;
    /* Darker border color */
    border-bottom: 2px solid #333333;
    /* Darker bottom border */
    border-top: none;
    /* No border on top */
    border-right: none;
    /* No border on right */
    padding-left: 10px;
    padding-right: 10px;
    padding-top: 8px;
    padding-bottom: 8px;
    background-color: #f2f2f2;
    /* Light gray background */
    color: #333333;
    /* Dark text color for better contrast */
    transition: border-color 0.3s ease, background-color 0.3s ease;
    /* Smooth transition for border and background color */
  }

  /* Style for focus effect */
  input.form-control:focus,
  select.form-select:focus,
  textarea.form-control:focus {
    border-color: #1d1d1d;
    /* Even darker border color on focus */
    background-color: #e6e6e6;
    /* Slightly darker background when focused */
    outline: none;
    /* Remove default focus outline */
  }

  /* Optional: Style the focus effect with a box-shadow for more emphasis */
  input.form-control:focus,
  select.form-select:focus,
  textarea.form-control:focus {
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    /* Dark shadow for focus */
  }
</style>

<!-- Main Content Area with Sidebar and BrowseBook Section -->
<div class="flex">
  <!-- Sidebar Section -->
  <?php include $sidebars[$userType] ?? ''; ?>
  <!-- BrowseBook Content Section -->
  <div class="flex flex-col w-screen">
    <!-- Header at the Top -->
    <?php include 'include/header.php'; ?>

    <div class="container mx-auto px-4 py-6 ">
      <!-- Breadcrumb Section -->
      <div class="text-sm text-gray-600 mb-4">
        <a href="index.php" class="hover:text-blue-800 hover:underline">Home</a> &rarr;
        <a href="ViewBook.php?title=<?php echo urlencode($book['book_id']); ?>" class="hover:text-blue-800 hover:underline">
          <?php echo htmlspecialchars($book['B_title']); ?>
        </a> &rarr;
        <a href="AddBookCopy.php?title=<?php echo urlencode($book['book_id']); ?>" class="hover:text-blue-800 hover:underline">Add Copy</a>
      </div>
        <!-- Return Button -->
        <a href="ViewBook.php?title=<?php echo urlencode($book['book_id']); ?>" class="inline-block bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300 mb-2">
                &larr; Return
            </a> <!-- Updated to look like a button --> 

      <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
          <?php echo htmlspecialchars($message); ?>
        </div>
      <?php endif; ?>
      <form action="include/CopyConnection.php" method="post" class="book-card">

        <!-- Display the title -->
        <div class="text-center mb-4">
          <h2 class="text-2xl font-bold"><?php echo htmlspecialchars($book['B_title']); ?></h2>
          <input type="hidden" name="B_title" id="B_title" value="<?php echo htmlspecialchars($book['B_title']); ?>">
        </div>

        <input type="hidden" name="book_id" id="book_id" value="<?php echo htmlspecialchars($book['book_id']); ?>">

        <!-- Status and Rating (Grouped in one row) -->
        <div class="flex flex-wrap gap-4 justify-center mb-4">
          <!-- Status -->
          <div class="w-full sm:w-1/2 lg:w-1/3 mb-4">
            <label for="status" class="form-label">Status</label>
            <select id="status" name="status" class="form-select w-full">
              <option selected>Available</option>
              <option>Checked Out</option>
              <option>Lost</option>
            </select>
          </div>

          <!-- Rating -->
          <div class="w-full sm:w-1/2 lg:w-1/3 mb-4">
            <label for="rating" class="form-label">Rating</label>
            <select id="rating" name="rating" class="form-select w-full" required>
              <option value="5">5 Stars</option>
              <option value="4">4 Stars</option>
              <option value="3">3 Stars</option>
              <option value="2">2 Stars</option>
              <option value="1">1 Star</option>
            </select>
          </div>

          <!-- Circulation Type -->
          <div class="w-full sm:w-1/2 lg:w-1/3 mb-4">
            <label for="circulationType" class="form-label">Circulation Type</label>
            <select id="circulationType" name="circulationType" class="form-select w-full">
              <option selected>General Circulation</option>
              <option>Reference</option>
              <option>Reserve</option>
            </select>
          </div>

          <!-- Date Acquired -->
          <div class="w-full sm:w-1/2 lg:w-1/3 mb-4">
            <label for="dateAcquired" class="form-label">Date Acquired</label>
            <input type="date" id="dateAcquired" name="dateAcquired" class="form-control w-full" required>
          </div>
        </div>

        <!-- Copy Information Section (Grouped in one row) -->
        <h5 class="mt-4 mb-4 text-center">Copy Information</h5>
        <div class="flex justify-center flex-wrap gap-4 mb-4">
          <!-- QR Code -->
          <div class="flex justify-center flex-wrap gap-4 mb-4">

            <!-- Call Number -->
            <div class="w-full sm:w-1/2 lg:w-1/3 mb-4">
              <label for="callNumber" class="form-label">Call Number</label>
              <input type="text" id="callNumber" name="callNumber" class="form-control w-full" required>
            </div>

            <!-- Purchase Price -->
            <div class="w-full sm:w-1/2 lg:w-1/3 mb-4">
              <label for="purchasePrice" class="form-label">Purchase Price</label>
              <input type="number" step="0.1" id="purchasePrice" name="purchasePrice" class="form-control w-full">
            </div>
            <!-- Copy Number -->
            <div class="w-full sm:w-1/2 lg:w-1/3 mb-4">
              <label for="copyNumber" class="form-label">Number of Copy</label>
              <input type="number" id="copyNumber" name="copyNumber" class="form-control w-full">
            </div>
          </div>
          <!-- Notes -->
          <div class="w-full sm:w-1/2 lg:w-1/3 mb-4">
            <label for="notes" class="form-label">Notes</label>
            <textarea class="form-control w-full" id="notes" name="note"
              placeholder="Any additional information about the book"></textarea>
          </div>
        </div>

        <!-- Accession and Number Fields Grouped Together Using Flex -->
        <h5 class="mt-4 mb-4 text-center">Accession and Number</h5>
        <div class="flex justify-center flex-wrap gap-4">
          <!-- Accession Fields Group -->
          <div class=" flex-col sm:flex-row gap-4 w-full sm:w-1/2 lg:w-1/3 mb-4">
            <!-- Accession 1 -->
            <label for="description1" class="form-label w-full">Accession</label>
            <div class="w-full mb-4">
              <input type="text" id="description1" name="description1" class="form-control w-full">
            </div>

            <!-- Accession 2 -->
            <div class="w-full mb-4">
              <input type="text" id="description2" name="description2" class="form-control w-full">
            </div>

            <!-- Accession 3 -->
            <div class="w-full mb-4">
              <input type="text" id="description3" name="description3" class="form-control w-full">
            </div>
          </div>

          <!-- Number Fields Group -->
          <div class=" flex-col sm:flex-row gap-4 w-full sm:w-1/2 lg:w-1/3 mb-4">
            <!-- Number 1 -->
            <label for="number1" class="form-label w-full">Number</label>
            <div class="w-full mb-4">
              <input type="number" id="number1" name="number1" class="form-control w-full">
            </div>

            <!-- Number 2 -->
            <div class="w-full mb-4">
              <input type="number" id="number2" name="number2" class="form-control w-full">
            </div>

            <!-- Number 3 -->
            <div class="w-full mb-4">
              <input type="number" id="number3" name="number3" class="form-control w-full">
            </div>
          </div>
        </div>


        <div class="text-center mt-4 mb-4">
  <h5>Additional Information</h5>


</div>

        <div class="flex justify-center gap-4 mb-4">
          <!-- Sublocation -->
          <div class="w-full sm:w-1/4 lg:w-1/4 mb-4">
            <label for="sublocation" class="form-label">Sublocation</label>
            <select id="sublocation" name="sublocation" class="form-select w-full"></select>
            <button type="button" class="btn btn-link text-gray-500 hover:text-gray-700 active:text-gray-900"
              onclick="showSublocationInput()">Add New Sublocation</button>
            <div class="flex">
              <input type="text"
                class="border-b border-gray-400 bg-gray-200 text-gray-700 p-2 px-3 w-40 mt-2 hidden focus:border-black"
                id="newSublocation" placeholder="Enter new sublocation">
              <button type="button" class="btn btn-link text-gray-500 hover:text-gray-700 active:text-gray-900 hidden"
                id="addSublocationBtn" onclick="addSublocation()">Add</button>
            </div>
          </div>

          <!-- Vendor -->
          <div class="w-full sm:w-1/4 lg:w-1/4 mb-4">
            <label for="vendor" class="form-label">Vendor</label>
            <select id="vendor" name="vendor" class="form-select w-full"></select>
            <button type="button" class="btn btn-link text-gray-500 hover:text-gray-700 active:text-gray-900"
              onclick="showVendorInput()">Add New Vendor</button>
            <div class="flex">
              <input type="text"
                class="border-b border-gray-400 bg-gray-200 text-gray-700 p-2 px-3 w-40 mt-2 hidden focus:border-black"
                id="newVendor" placeholder="Enter new vendor">
              <button type="button" class="btn btn-link text-gray-500 hover:text-gray-700 active:text-gray-900 hidden"
                id="addVendorBtn" onclick="addVendor()">Add</button>
            </div>
          </div>

          <!-- Funding Source -->
          <div class="w-full sm:w-1/4 lg:w-1/4 mb-4">
            <label for="fundingSource" class="form-label justify-between">Funding Source  
               <!-- Icon "!" Link at the end of Funding Source -->
  <a href="javascript:void(0);" onclick="showDataPopup()" class="text-lg font-bold">
      !
    </a></label>
            <select id="fundingSource" name="fundingSource" class="form-select w-full"></select>
            <button type="button" class="btn btn-link text-gray-500 hover:text-gray-700 active:text-gray-900"
              onclick="showFundingSourceInput()">Add New Funding Source</button>
            <div class="flex">

              <input type="text"
              class="border-b border-gray-400 bg-gray-200 text-gray-700 p-2 px-3 w-40 mt-2 hidden focus:border-black"
              id="newFundingSource" placeholder="Enter new funding source">
              <button type="button" class="btn btn-link text-gray-500 hover:text-gray-700 active:text-gray-900 hidden"
                id="addFundingSourceBtn" onclick="addFundingSource()">Add</button>
            </div>


          </div>

        </div>

        <!-- Popup Modal -->
        <div id="dataPopup" class="hidden fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-50">
          <div class="bg-white p-4 rounded-lg shadow-lg w-3/4 max-w-4xl">
            <h2 class="text-xl mb-4">Content of data.json</h2>

            <!-- Grid to display data in 3 columns -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">

              <!-- Sublocations -->
              <div>
                <h3 class="font-bold">Sublocations</h3>
                <ul id="sublocationsList" class="text-sm"></ul>
              </div>

              <!-- Vendors -->
              <div>
                <h3 class="font-bold">Vendors</h3>
                <ul id="vendorsList" class="text-sm"></ul>
              </div>

              <!-- Funding Sources -->
              <div>
                <h3 class="font-bold">Funding Sources</h3>
                <ul id="fundingSourcesList" class="text-sm"></ul>
              </div>
            </div>

            <!-- Icon "!" Link at the end of Funding Source -->
            <div class=" rounded px-4 py-2">
              <a href="javascript:void(0);" onclick="closeDataPopup()" class="mt-4 p-3">
                <span class="text-lg font-bold">Close</span>
              </a>
            </div>


          </div>
        </div>
        
<script>
  // Initialize the page by fetching data from data.json
  async function fetchData() {
    let data;

    // Fetch data from data.json on the server
    try {
      const response = await fetch('data.json');
      data = await response.json();
    } catch (error) {
      console.error('Error fetching from data.json:', error);
      // In case fetching from data.json fails, use default data structure
      data = {
        sublocations: ['sample1', 'sample2', 'sample3'],
        vendors: ['sample1', 'sample2', 'sample3'],
        fundingSources: ['sample1', 'sample2', 'sample3']
      };
    }

    // Populate select options from the data
    populateSelectOptions('sublocation', data.sublocations);
    populateSelectOptions('vendor', data.vendors);
    populateSelectOptions('fundingSource', data.fundingSources);
  }

  // Populate the <select> dropdown with options
  function populateSelectOptions(type, data) {
    const selectElement = document.getElementById(type);
    selectElement.innerHTML = ''; // Clear the current options

    data.forEach(item => {
      const option = document.createElement('option');
      option.value = item;
      option.textContent = item;
      selectElement.appendChild(option);
    });
  }

  

  // Add new item to the data and update the dropdown
  async function addSublocation() {
    const newSublocation = document.getElementById('newSublocation').value;
    if (newSublocation) {
      await addItem('sublocations', newSublocation);
      document.getElementById('newSublocation').value = ''; // Clear input
      hideInputFields('newSublocation', 'addSublocationBtn'); // Hide input field after adding
    }
  }

  async function addVendor() {
    const newVendor = document.getElementById('newVendor').value;
    if (newVendor) {
      await addItem('vendors', newVendor);
      document.getElementById('newVendor').value = ''; // Clear input
      hideInputFields('newVendor', 'addVendorBtn'); // Hide input field after adding
    }
  }

  async function addFundingSource() {
    const newFundingSource = document.getElementById('newFundingSource').value;
    if (newFundingSource) {
      await addItem('fundingSources', newFundingSource);
      document.getElementById('newFundingSource').value = ''; // Clear input
      hideInputFields('newFundingSource', 'addFundingSourceBtn'); // Hide input field after adding
    }
  }

  // Add new item to the data and update data.json
  async function addItem(type, newItem) {
    try {
      const data = await fetchDataFromServer(); // Fetch the latest data from server

      // Add the new item to the respective array
      data[type].push(newItem);

      // Save updated data back to data.json on the server
      await saveDataToServer(data);

      // Re-populate the dropdown with updated options
      populateSelectOptions(type, data[type]);
    } catch (error) {
      console.error('Error adding item:', error);
    }
  }

  // Fetch current data from the server (data.json)
  async function fetchDataFromServer() {
    const response = await fetch('data.json');
    if (response.ok) {
      return await response.json();
    }
    throw new Error('Error fetching data from server');
  }

  // Save updated data back to data.json (server)
  async function saveDataToServer(data) {
    try {
      // Send updated data to the server to save to data.json
      const response = await fetch('saveData.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      });

      if (!response.ok) {
        throw new Error('Error saving data to server');
      }
    } catch (error) {
      console.error('Error saving data to server:', error);
    }
  }

  // Hide input fields after adding a new item
  function hideInputFields(inputId, buttonId) {
    document.getElementById(inputId).classList.add('hidden');
    document.getElementById(buttonId).classList.add('hidden');
  }

  // Show the data popup modal with the content from data.json
  function showDataPopup() {
    fetchDataFromServer().then(data => {
      // Populate Sublocations list
      const sublocationsList = document.getElementById('sublocationsList');
      sublocationsList.innerHTML = data.sublocations.map((item, index) => `
        <li class="flex justify-between items-center">
          ${item} 
          <a href="javascript:void(0)" onclick="removeItem('sublocations', ${index})" class="text-red-500 ml-2">❌</a>
        </li>
      `).join('');

      // Populate Vendors list
      const vendorsList = document.getElementById('vendorsList');
      vendorsList.innerHTML = data.vendors.map((item, index) => `
        <li class="flex justify-between items-center">
          ${item} 
          <a href="javascript:void(0)" onclick="removeItem('vendors', ${index})" class="text-red-500 ml-2">❌</a>
        </li>
      `).join('');

      // Populate Funding Sources list
      const fundingSourcesList = document.getElementById('fundingSourcesList');
      fundingSourcesList.innerHTML = data.fundingSources.map((item, index) => `
        <li class="flex justify-between items-center">
          ${item} 
          <a href="javascript:void(0)" onclick="removeItem('fundingSources', ${index})" class="text-red-500 ml-2">❌</a>
        </li>
      `).join('');

      // Show the modal
      document.getElementById('dataPopup').classList.remove('hidden');
    });
  }

  // Remove item function
  async function removeItem(type, index) {
    try {
      const data = await fetchDataFromServer();

      // Remove the item from the respective array
      data[type].splice(index, 1);

      // Save the updated data back to data.json on the server
      await saveDataToServer(data);

      // Reopen the modal with updated data
      showDataPopup();
    } catch (error) {
      console.error('Error removing item:', error);
    }
  }

  // Close the data popup modal
  function closeDataPopup() {
    document.getElementById('dataPopup').classList.add('hidden');
  }

  

  // Initialize the page by fetching and displaying the data
  fetchData();
</script>



        <button type="submit" class="btn btn-primary mt-4 block mx-auto">Submit</button>
      </form>




      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    </div>
    <!-- Footer at the Bottom -->
    <footer>
      <?php include 'include/footer.php'; ?>
    </footer>
    </di>