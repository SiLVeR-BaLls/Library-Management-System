<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<?php
    // Initialize variables for messages
    $message = ""; // Variable to store messages
    $message_type = ""; // Variable to store message type (e.g. success, error)

    // Check if connection is still open before executing query
    if ($conn && !$conn->connect_error) {
      $sql = "SELECT 
            book.book_id, 
            book.B_title, 
            book.subtitle, 
            book.author, 
            book.LCCN, 
            book.ISBN, 
            book.ISSN, 
            book.copyright, 
            book.MT, 
            book.ST, 
            book.extent,
            book.journal,  /* Add journal here */
            GROUP_CONCAT(DISTINCT coauthor.Co_Name SEPARATOR ', ') AS coauthor,  
            COUNT(CASE WHEN book_copies.status = 'Available' THEN 1 END) AS available_count,
            COUNT(Book_copies.book_copy_ID) AS total_count
        FROM 
            book 
        LEFT JOIN 
            coauthor ON book.book_id = coauthor.book_id  
        LEFT JOIN 
            book_copies ON book.B_title = book_copies.B_title
        GROUP BY 
            book.book_id, 
            book.B_title, 
            book.subtitle, 
            book.author, 
            book.LCCN, 
            book.ISBN, 
            book.ISSN, 
            book.copyright, 
            book.MT, 
            book.ST, 
            book.extent, 
            book.journal /* Group by journal */
        ORDER BY 
            book.B_title;
        ";


      // Execute the query and get the result
      $result = $conn->query($sql);

      if ($result === false) {
        // Query failed, handle error
        echo "Error executing query: " . $conn->error;
      }
    } else {
      // Connection is not open, handle error
      echo "Database connection is closed or failed.";
    }
?>

<div class="my-6 px-4 flex  w-full justify-between items-center">
  <!-- Centered Search Controls -->
  <div class="flex flex-row gap-4  items-center">
    <!-- Search Input -->
    <input type="text" id="searchInput"
      class="form-input block w-40 sm:w-60 px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-gray-700 text-sm"
      placeholder="Enter search term...">

    <!-- Search Type Selection -->
    <select id="searchType"
      class="form-select block px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-gray-700 text-sm">
      <option value="all">All</option>
      <option value="title">Title</option>
      <option value="author">Author</option>
      <option value="coauthor">Co-authors</option>
      <option value="LCCN">LCCN</option>
      <option value="ISBN">ISBN</option>
      <option value="ISSN">ISSN</option>
      <option value="MT">Material Type</option>
      <option value="ST">sub Type</option>
      <option value="extent">Extent</option>
      <option value="journal">Journal</option>
    </select>
  </div>
  <!-- Button to Open the Modal -->
  <button id="filterButton" class="btn btn-primary">Filter by Material Type</button>

</div>

<div class="p-4 overflow-x-auto w-full table-container rounded-lg shadow-md flex flex-col justify-center items-center">
  <table class="max-w-full table-screen rounded-lg">
    <thead class="w-screen" style="color: <?= $text ?>; background: <?= $sidebar ?>;">
      <tr>
        <th class="px-4 py-2 rounded-tl-lg">Title</th>
        <th class="px-4 py-2">Author</th>
        <th class="px-4 py-2 w-36 coauthor">Co-authors</th>
        <th class="px-4 py-2 w-28">Material Type</th>
        <th class="px-4 py-2 w-28">Sub Type</th>
        <th class="px-4 py-2 extent">Extent</th>
        <th class="px-4 py-2 rounded-tr-lg">Copies</th>
      </tr>
    </thead>
    <tbody id="bookTableBody" class="bg-white">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr class="border-y border-solid cursor-pointer hover:bg-gray-200"
            data-title="<?php echo htmlspecialchars($row['B_title']); ?>"
            data-author="<?php echo htmlspecialchars($row['author']); ?>"
            data-coauthor="<?php echo htmlspecialchars($row['coauthor']); ?>"
            data-lccn="<?php echo htmlspecialchars($row['LCCN']); ?>"
            data-isbn="<?php echo htmlspecialchars($row['ISBN']); ?>"
            data-issn="<?php echo htmlspecialchars($row['ISSN']); ?>"
            data-material-type="<?php echo htmlspecialchars($row['MT']); ?>"
            data-material-type="<?php echo htmlspecialchars($row['ST']); ?>"
            data-extent="<?php echo htmlspecialchars($row['extent']); ?>"
            data-available-count="<?php echo $row['available_count']; ?>"
            data-total-count="<?php echo $row['total_count']; ?>"
            data-copyright="<?php echo htmlspecialchars($row['copyright']); ?>"
            onclick="window.location.href='ViewBook.php?title=<?php echo urlencode($row['book_id']); ?>';"
            onmouseenter="showPopup(event, this)" onmouseleave="hidePopup()">

            <td class="px-4 py-2 title"><?php echo htmlspecialchars($row['B_title']); ?></td>
            <td class="px-4 py-2 author"><?php echo htmlspecialchars($row['author']); ?></td>
            <td class="px-4 py-2 coauthor"><?php echo htmlspecialchars($row['coauthor']); ?></td>
            <td class="px-4 py-2 MT"><?php echo htmlspecialchars($row['MT']); ?></td>
            <td class="px-4 py-2 ST"><?php echo htmlspecialchars($row['ST']); ?></td>
            <td class="px-4 py-2 extent"><?php echo htmlspecialchars($row['extent']); ?></td>
            <td class="px-4 py-2 flex justify-center gap-2">
              <?php if ($row['available_count'] > 0): ?>
                <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center">✔</div>
              <?php else: ?>
                <div class="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center">✖</div>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="9" class="text-center py-4">No books found.</td>
        </tr>
      <?php endif; ?>
    </tbody> 
  </table>
          
  <!-- Pagination Controls -->
  <div class="flex justify-center items-center space-x-4 my-6 flex-col md:flex-row md:space-x-6">
      <button id="prevBtn" onclick="prevPage()" class="btn-pagination px-6 py-2 bg-gray-800 text-white rounded-lg cursor-pointer disabled:bg-gray-400 disabled:cursor-not-allowed transition duration-300 hover:bg-gray-600" disabled>Previous</button>
      <span id="pageInfo" class="text-lg text-gray-600 font-medium">Page 1 of X</span> <!-- Placeholder for page info -->
      <button id="nextBtn" onclick="nextPage()" class="btn-pagination px-6 py-2 bg-gray-800 text-white rounded-lg cursor-pointer transition duration-300 hover:bg-gray-600">Next</button>
  </div>
</div>
<script>
  $(document).ready(function() {
    let currentPage = 1;
    const rowsPerPage = 10; // Set number of rows per page
    let filteredRows = []; // Store filtered rows
    let allRows = []; // Store all rows before filtering

    // Capture all rows initially
    function captureAllRows() {
      allRows = [];
      $('#bookTableBody tr').each(function() {
        allRows.push($(this)); // Store each row in the allRows array
      });
    }

    // Initialize all rows and handle search/filter
    captureAllRows();
    displayTablePage(currentPage);

    // Event listener for input changes
    $('#searchInput').on('keyup', filterTable);
    $('#searchType').on('change', filterTable);

    // Filter function to show/hide rows
    function filterTable() {
      const searchType = $('#searchType').val();
      const searchText = $('#searchInput').val().trim().toLowerCase(); // Trim and convert to lowercase

      if (searchText === '' && searchType === 'all') {
        // If no filter is applied, show all rows
        filteredRows = allRows;
      } else {
        // Apply filtering logic
        filteredRows = allRows.filter(function(row) {
          const rowTitle = row.find('.title').text().trim().toLowerCase();
          const rowAuthor = row.find('.author').text().trim().toLowerCase();
          const rowCoauthor = row.find('.coauthor').text().trim().toLowerCase();
          const rowLCCN = row.find('.lccn').text().trim().toLowerCase();
          const rowISBN = row.find('.isbn').text().trim().toLowerCase();
          const rowISSN = row.find('.issn').text().trim().toLowerCase();
          const rowMT = row.find('.MT').text().trim().toLowerCase();
          const rowST = row.find('.ST').text().trim().toLowerCase();
          const rowExtent = row.find('.extent').text().trim().toLowerCase();
          const rowJournal = row.find('.journal').text().trim().toLowerCase();
          
          let match = false;
          switch (searchType) {
            case 'all':
              match = rowTitle.indexOf(searchText) > -1 ||
                      rowAuthor.indexOf(searchText) > -1 ||
                      rowCoauthor.indexOf(searchText) > -1 ||
                      rowLCCN.indexOf(searchText) > -1 ||
                      rowISBN.indexOf(searchText) > -1 ||
                      rowISSN.indexOf(searchText) > -1 ||
                      rowMT.indexOf(searchText) > -1 ||
                      rowST.indexOf(searchText) > -1 ||
                      rowExtent.indexOf(searchText) > -1 ||
                      rowJournal.indexOf(searchText) > -1;
              break;
            case 'title': match = rowTitle.indexOf(searchText) > -1; break;
            case 'author': match = rowAuthor.indexOf(searchText) > -1; break;
            case 'coauthor': match = rowCoauthor.indexOf(searchText) > -1; break;
            case 'lccn': match = rowLCCN.indexOf(searchText) > -1; break;
            case 'isbn': match = rowISBN.indexOf(searchText) > -1; break;
            case 'issn': match = rowISSN.indexOf(searchText) > -1; break;
            case 'MT': match = rowMT.indexOf(searchText) > -1; break;
            case 'ST': match = rowST.indexOf(searchText) > -1; break;
            case 'extent': match = rowExtent.indexOf(searchText) > -1; break;
            case 'journal': match = rowJournal.indexOf(searchText) > -1; break;
          }
          return match;
        });
      }
      displayTablePage(currentPage); // Update the displayed rows after filtering
    }

    // Display current page of rows
    function displayTablePage(pageNumber) {
      const startIndex = (pageNumber - 1) * rowsPerPage;
      const endIndex = startIndex + rowsPerPage;
      const rowsToDisplay = filteredRows.slice(startIndex, endIndex);
      
      // Clear current table body and append filtered rows
      $('#bookTableBody').empty().append(rowsToDisplay);
      
      // Update pagination
      const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
      $('#pageInfo').text(`Page ${pageNumber} of ${totalPages}`);
      
      // Disable 'Previous' button if on the first page
      $('#prevBtn').prop('disabled', pageNumber <= 1);
      
      // Disable 'Next' button if on the last page
      $('#nextBtn').prop('disabled', pageNumber >= totalPages);
    }

    // Pagination functions
    function nextPage() {
      const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
      if (currentPage < totalPages) {
        currentPage++;
        displayTablePage(currentPage);
      }
    }

    function prevPage() {
      if (currentPage > 1) {
        currentPage--;
        displayTablePage(currentPage);
      }
    }

    // Initialize with all rows visible
    filterTable(); // Ensure the filter shows all rows initially

    // Bind the pagination buttons to their functions
    $('#nextBtn').on('click', nextPage);
    $('#prevBtn').on('click', prevPage);

    // Modal filter functionality
    // Hide the modal initially on page load
    $('#materialTypeModal').hide();

    // Open the modal when the filter button is clicked
    $('#filterButton').on('click', function() {
      $('#materialTypeModal').show(); // Show the modal
    });

    // Close the modal when the "X" button is clicked
    $('#modalCloseBtn').on('click', function() {
      $('#materialTypeModal').hide(); // Hide the modal
    });

    // Apply the filter when the "Apply Filter" button is clicked
    $('#filterApply').on('click', function() {
      var selectedType = $('input[name="materialType"]:checked').val();
      var selectedSubType = $('input[name="subType"]:checked').val();

      // Filter the rows based on selected material type and subtypes
      $('#bookTableBody tr').each(function() {
        var rowMaterialType = $(this).find('.MT').text().toLowerCase();
        var rowSubType = $(this).find('.ST').text().toLowerCase(); // Extract SubType from ST class

        // Show row if it matches the filter criteria
        if (
          (!selectedType || selectedType.toLowerCase() === rowMaterialType) &&
          (!selectedSubType || selectedSubType.toLowerCase() === rowSubType || selectedSubType === "All")
        ) {
          $(this).show(); // Show row
        } else {
          $(this).hide(); // Hide row
        }
      });

      // Reapply table filter to ensure pagination is correct
      filterTable();
    });

    // Clear all filters when the "Clear Filters" button is clicked
    $('#filterClear').on('click', function() {
      $('input[name="materialType"]').prop('checked', false);
      $('input[name="subType"]').prop('checked', false);
      $('#bookTableBody tr').show(); // Show all rows (clear all filters)

      // Reapply table filter to ensure pagination is correct
      filterTable();
    });

    // Close the modal if clicked outside the modal
    $(window).on('click', function(event) {
      if ($(event.target).is('#materialTypeModal')) {
        $('#materialTypeModal').hide(); // Hide the modal
      }
    });
  });
</script>

