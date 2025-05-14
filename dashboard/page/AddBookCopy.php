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

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';
    $name = $_POST['name'] ?? '';

    if ($type && $name) {
      $table = '';
      if ($type === 'sublocation') $table = 'sublocation';
      elseif ($type === 'vendor') $table = 'Vendor';
      elseif ($type === 'fundingSource') $table = 'FundingSource';

      if ($table) {
        $stmt = $conn->prepare("INSERT INTO $table (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
          echo json_encode(['success' => true, 'id' => $conn->insert_id, 'name' => $name]);
        } else {
          echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
      }
    }
    exit;
  }


  $vendors_result = $conn->query("SELECT id, name FROM vendor ");
  $fund_result = $conn->query("SELECT id, name FROM fundingsource ");
  $sub_result = $conn->query("SELECT id, name FROM sublocation ");


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
              <input type="date" id="dateAcquired" name="dateAcquired" class="form-control w-full" value="<?php echo date('Y-m-d'); ?>" required>
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
                <input type="number" step="0.01" min="0" id="purchasePrice" name="purchasePrice" class="form-control w-full" placeholder="0.00">
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
            <!-- sublocation -->
            <div class="w-full sm:w-1/4 lg:w-1/4 mb-4">
              <label for="sublocation" class="form-label">sublocation</label>
              <select name="sublocation" id="sublocation" class="form-select w-full">
                             <?php
                while ($sub_row = $sub_result->fetch_assoc()) {
                  $selected = ($sub_row['name'] == $copy_data['sublocation']) ? 'selected' : '';
                  echo "<option value='{$sub_row['name']}' $selected>{$sub_row['name']}</option>";
                }
                // Reset the result set pointer to the beginning for potential future use if needed
                if (isset($sub_result)) $sub_result->data_seek(0);
                ?>
                </select>
              <a href="#" onclick="openPopup('sublocation', event)" class="text-blue-600 underline">Add</a>
            </div>

            <!-- Vendor -->
            <div class="w-full sm:w-1/4 lg:w-1/4 mb-4">
              <label for="vendor" class="form-label">Vendor</label>
              <select name="vendor" id="vendor" class="form-select w-full">
                <?php
                while ($vendor_row = $vendors_result->fetch_assoc()) {
                  $selected = ($vendor_row['name'] == $copy_data['vendor']) ? 'selected' : '';
                  echo "<option value='{$vendor_row['name']}' $selected>{$vendor_row['name']}</option>";
                }
                // Reset the result set pointer to the beginning for potential future use if needed
                if (isset($vendors_result)) $vendors_result->data_seek(0);
                ?>
              </select>
              <a href="#" onclick="openPopup('vendor', event)" class="text-blue-600 underline">Add</a>

            </div>

            <!-- Funding Source -->
            <div class="w-full sm:w-1/4 lg:w-1/4 mb-4">
              <label for="fundingSource" class="form-label">Funding Source</label>
              <select name="fundingSource" id="fundingSource" class="form-select w-full">
                              <?php
                                  while ($fund_row = $fund_result->fetch_assoc()) {
                                      $selected = ($fund_row['name'] == $copy_data['funding_source']) ? 'selected' : '';
                                      echo "<option value='{$fund_row['name']}' $selected>{$fund_row['name']}</option>";
                                  }
                                  // Reset the result set pointer
                                  if (isset($fund_result)) $fund_result->data_seek(0);
                              ?>
                              </select>
              <a href="#" onclick="openPopup('fundingSource', event)" class="text-blue-600 underline">Add</a>
            </div>
          </div>

          <button type="submit" class="btn btn-primary mt-4 block mx-auto">Submit</button>
        </form>


        <!-- Popup (Hidden by Default) -->
        <div id="popupContainer" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
          <div id="popupBox" class="bg-white p-6 rounded-lg shadow-lg w-80">
            <h2 class="text-xl font-bold mb-2">Add New Entry</h2>
            <input type="hidden" id="popupType">
            <input type="text" id="popupInput" class="w-full border rounded px-3 py-2 mb-4" placeholder="Type something...">
            <div class="flex justify-end gap-2">
              <a href="#" onclick="closePopup(event)" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 transition">Cancel</a>
              <a href="#" onclick="confirmInput(event)" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Confirm</a>
            </div>
          </div>
        </div>

        <script>
          function openPopup(type, event) {
            event.preventDefault(); // Prevent the default behavior of the <a> tag
            document.getElementById("popupType").value = type;
            document.getElementById("popupInput").value = '';
            document.getElementById("popupContainer").classList.remove("hidden");
          }

          function closePopup(event) {
            event.preventDefault(); // Prevent the default behavior of the <a> tag
            document.getElementById("popupContainer").classList.add("hidden");
          }

          function confirmInput(event) {
            event.preventDefault(); // Prevent the default behavior of the <a> tag
            const type = document.getElementById("popupType").value;
            const input = document.getElementById("popupInput").value.trim();

            if (!input) {
              alert("Please enter a value.");
              return;
            }

            fetch('AddBookCopy.php', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                  type,
                  name: input
                })
              })
              .then(response => response.json())
              .then(data => {
                if (data.success) {
                  const select = document.getElementById(type);
                  const option = document.createElement('option');
                  option.value = data.id;
                  option.textContent = data.name;
                  select.appendChild(option);
                  select.value = data.id;
                  closePopup(event);
                  alert(`${type} added successfully.`);
                } else {
                  alert(`Error: ${data.error}`);
                }
              });
          }

          // Close popup if clicked outside the box
          document.getElementById("popupContainer").addEventListener("click", function(event) {
            if (event.target === this) closePopup(event);
          });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

      </div>
      <!-- Footer at the Bottom -->
      <footer>
        <?php include 'include/footer.php'; ?>
      </footer>
    </div>