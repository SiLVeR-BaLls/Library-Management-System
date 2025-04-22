<?php
include '../config.php'; // Include the configuration file for database connection
?>
<!-- script for the instascan -->
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

<!-- div Content Area with Sidebar and BrowseBook Section -->
<div class="flex h-full overflow-y-auto">
  <!-- Sidebar Section -->
  <?php include $sidebars[$userType] ?? ''; ?>
  <!-- BrowseBook Content Section -->
  <div class="flex-grow ">
    <!-- Header at the Top -->
    <?php include 'include/header.php'; ?>

    <!-- BorrowBook Content -->
    <div class="container mx-auto px-4 py-6 ">
    <h2 class="text-3xl font-semibold mb-6">Borrow a Book</h2>

      <form action="include/BorrowConnect.php" method="POST">
        <div class="flex flex-row gap-4 mt-2 max-h-[65vh]">

          <!-- User Section (Non-scrollable) -->
          <div class="user-container items-center flex flex-col space-y-4 w-1/2">
            <label for="IDno" class="font-semibold">User ID:</label>
            <div class="flex flex-col space-y-2">
              <div class="flex">
                <input autocomplete="off" type="text" id="IDno" name="IDno"
                  class="form-control shadow border rounded px-3 py-2 text-gray-700 bg-white focus:outline-none focus:shadow-outline"
                  required oninput="searchUser()">
                <button type="button" class="ml-2 bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-700"
                  onclick="startScanner('IDno')">Scan QR</button>
              </div>
              <div id="userSearchResult" class="bookSearchResults mt-2 max-h-64 overflow-y-auto"></div>
            </div>
          </div>

          <!-- Book Section (Scrollable) -->
          <div class="books-container sticky flex flex-col space-y-4 w-1/2 overflow-y-auto" id="bookContainer">
            <div class="form-group flex flex-col space-y-2" id="bookGroup_0">
              <label for="bookID_0" class="font-semibold">Book ID:</label>
              <div class="flex flex-col space-y-2">
                <div class="flex">
                  <input autocomplete="off" type="text" id="bookID_0" name="bookID[]"
                    class="form-control shadow border rounded px-3 py-2 text-gray-700 bg-white focus:outline-none focus:shadow-outline"
                    required oninput="searchBook(0)">
                  <button type="button" class="ml-2 bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-700"
                    onclick="startScanner('bookID_0')">Scan QR</button>
                </div>
                <div id="bookSearchResult_0" class="bookSearchResults mt-2 max-h-40 overflow-y-auto"></div>
              </div>
            </div>
          </div>


          <!-- Video Element for QR Scanning -->
          <div id="scannerModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
            <div class="bg-white p-4 rounded-lg shadow-lg relative">
              <video id="scannerVideo" class="w-full h-auto"></video>
              <button type="button" onclick="stopScanner()" class="absolute top-2 right-2 bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-700">Close</button>
            </div>
          </div>

        </div>

        <!-- JavaScript for QR Scanner -->
        <script>
          let scanner = null;

          function startScanner(inputFieldId) {
            document.getElementById('scannerModal').classList.remove('hidden');

            scanner = new Instascan.Scanner({
              video: document.getElementById('scannerVideo')
            });
            scanner.addListener('scan', function(content) {
              document.getElementById(inputFieldId).value = content;
              stopScanner();
            });

            Instascan.Camera.getCameras().then(function(cameras) {
              if (cameras.length > 0) {
                scanner.start(cameras[0]);
              } else {
                alert('No camera found');
              }
            }).catch(function(e) {
              console.error(e);
            });
          }

          function stopScanner() {
            if (scanner) {
              scanner.stop();
            }
            document.getElementById('scannerModal').classList.add('hidden');
          }

          function addBook() {
            const bookContainer = document.getElementById('bookContainer');
            const bookCount = bookContainer.querySelectorAll('.form-group').length;
            const newBookGroup = document.createElement('div');
            newBookGroup.classList.add('form-group', 'flex', 'flex-col', 'space-y-2');
            newBookGroup.innerHTML = `
        <label for="bookID_${bookCount}" class="font-semibold">Book ID:</label>
        <div class="flex flex-col space-y-2">
          <div class="flex">
            <input autocomplete="off" type="text" id="bookID_${bookCount}" name="bookID[]"
              class="form-control shadow border rounded   px-3 text-gray-700 relative h-96 overflow-y-auto top-0 bg-white focus:outline-none focus:shadow-outline"
              required oninput="searchBook(${bookCount})">
            <button type="button" class="ml-2 bg-green-500 text-white px-4  rounded-lg hover:bg-green-700"
              onclick="startScanner('bookID_${bookCount}')">Scan QR</button>
          </div>
          <div id="bookSearchResult_${bookCount}" class="bookSearchResults mt-2 max-h-40 overflow-y-auto"></div>
        </div>
      `;
            bookContainer.appendChild(newBookGroup);
          }
        </script>

        <!-- Buttons Section -->
        <div class="flex sticky bottom-0 items-center justify-center space-x-4 py-4 shadow-md">
          <!-- Add Another Book Button -->
          <button type="button"
            class="bg-gray-500 text-white font-bold text-base px-6 py-3 rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 transition"
            onclick="addBook()">
            Add Another Book
          </button>

          <!-- Approve Borrowing Button -->
          <button type="submit"
            class="bg-blue-500 text-white font-bold text-base px-6 py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
            Approve Borrowing
          </button>
        </div>

      </form>


    </div>
    <!-- Footer at the Bottom -->
    <footer>
      <?php include 'include/footer.php'; ?>
    </footer>
  </div>
</div>


<!-- Custom CSS for Scrollable Search Results -->
<script>
  // Declare and initialize bookCount to keep track of the number of books
  let bookCount = 1;

  // Search for user based on user ID
  function searchUser() {
    const IDno = document.getElementById('IDno').value;
    if (IDno.length > 2) {
      fetch(`SearchUser.php?IDno=${IDno}`)
        .then(response => response.text())
        .then(data => {
          document.getElementById('userSearchResult').innerHTML = data;
          const results = document.getElementById('userSearchResult').getElementsByClassName('search-item');
          Array.from(results).forEach(item => {
            item.addEventListener('click', () => {
              const selectedID = item.getAttribute('data-id');
              const selectedName = item.textContent;
              selectUser(selectedID, selectedName);
            });
          });
        })
        .catch(error => console.error('Error:', error));
    } else {
      document.getElementById('userSearchResult').innerHTML = "";
    }
  }

  // Search for books by ID
  function searchBook(index) {
    const bookID = document.getElementById(`bookID_${index}`).value;
    if (bookID.length > 2) {
      fetch(`include/SearchBook.php?bookID=${bookID}`)
        .then(response => response.text())
        .then(data => {
          document.getElementById(`bookSearchResult_${index}`).innerHTML = data;
        })
        .catch(error => console.error('Error:', error));
    } else {
      document.getElementById(`bookSearchResult_${index}`).innerHTML = "";
    }
  }

  // Add a new book input field
  function addBook() {
    const container = document.getElementById('bookContainer');
    const newBookIndex = bookCount++;

    const newBookInput = document.createElement('div');
    newBookInput.classList.add('form-group', 'flex', 'flex-col', 'space-y-2');
    newBookInput.setAttribute('id', `bookGroup_${newBookIndex}`);

    newBookInput.innerHTML = `  
                <label for="bookID_${newBookIndex}" class="font-semibold">Book ID:</label>
                <div class="flex flex-col space-y-2">
                    <input type="text" id="bookID_${newBookIndex}" name="bookID[]"         
                    class="form-control shadow border rounded w-48 py-2 px-3 text-gray-700 sticky top-0 bg-white z-10 focus:outline-none focus:shadow-outline"
                        required oninput="searchBook(${newBookIndex})">
                   <div class="flex space-x-2">
                    <button type="button" class="bg-red-500 text-white font-bold py-2 px-4 rounded mt-2 hover:bg-red-700 focus:outline-none" onclick="removeBook(${newBookIndex})">
                    Remove Book</button>
                    <button type="button" class="bg-green-500 text-white  font-bold  px-4 rounded mt-2 hover:bg-green-700 " onclick="startScanner('IDno')">
                    Scan QR</button>
                    </div>
                    
                    <div id="bookSearchResult_${newBookIndex}" class="bookSearchResults mt-2"></div>
                    </div>

    `;

    container.appendChild(newBookInput);
  }


  // Remove a specific book input field
  function removeBook(index) {
    const bookGroup = document.getElementById(`bookGroup_${index}`);
    if (bookGroup) bookGroup.remove();
  }


  // Set user ID in the input field after selecting a result
  function selectUser(IDno, name) {
    document.getElementById('IDno').value = IDno;

    // Hide all results except the selected one
    const userSearchResult = document.getElementById('userSearchResult');
    Array.from(userSearchResult.children).forEach(item => {
      if (item.getAttribute('data-id') === IDno) {
        item.style.display = 'block'; // Show the selected user
      } else {
        item.style.display = 'none'; // Hide others
      }
    });
  }

  // Set book ID in the input field after selecting a result
  function selectBook(bookID, title, author, callNumber, publisher, publicationYear, status) {
    const inputField = document.getElementById(`bookID_${index}`);
    inputField.value = bookID; // Use the relevant parameter

    // Hide all results except the selected one
    const bookSearchResult = document.getElementById(`bookSearchResult_${index}`);
    Array.from(bookSearchResult.children).forEach(item => {
      if (item.getAttribute('data-id') === bookID) {
        item.style.display = 'block'; // Show the selected book
      } else {
        item.style.display = 'none'; // Hide others
      }
    });
  }
</script>

<!-- // Search for user based on user ID -->
<script>
  function searchUser() {
    const IDno = document.getElementById('IDno').value;
    if (IDno.length > 0) {
      fetch(`include/SearchUser.php?IDno=${IDno}`)
        .then(response => response.text())
        .then(data => {
          document.getElementById('userSearchResult').innerHTML = data;
          const results = document.getElementById('userSearchResult').getElementsByClassName('search-item');
          Array.from(results).forEach(item => {
            item.addEventListener('click', () => {
              const selectedID = item.getAttribute('data-id');
              const selectedName = item.textContent;
              selectUser(selectedID, selectedName);
            });
          });
        })
        .catch(error => console.error('Error:', error));
    } else {
      document.getElementById('userSearchResult').innerHTML = "";
    }
  }

  // Search for books based on book ID
  function searchBook(index) {
    const bookID = document.getElementById(`bookID_${index}`).value;
    if (bookID.length > 0) {
      fetch(`include/SearchBook.php?bookID=${bookID}`)
        .then(response => response.text())
        .then(data => {
          document.getElementById(`bookSearchResult_${index}`).innerHTML = data;
          const results = document.getElementById(`bookSearchResult_${index}`).getElementsByClassName('search-item');
          document.getElementById(`bookSearchResult_${index}`).addEventListener('click', (event) => {
            const target = event.target.closest('.search-item');
            if (target) {
              const selectedBookID = target.getAttribute('data-id');
              const selectedTitle = target.textContent;
              selectBook(selectedBookID, selectedTitle, index);
            }
          });

        })
        .catch(error => console.error('Error:', error));
    } else {
      document.getElementById(`bookSearchResult_${index}`).innerHTML = "";
    }
  }



  // Remove a specific book input field
  function removeBook(index) {
    const bookGroup = document.getElementById(`bookGroup_${index}`);
    if (bookGroup) {
      bookGroup.remove();
    }
  }

  // Set user ID in the input field after selecting a result
  function selectUser(IDno, name) {
    document.getElementById('IDno').value = IDno;

    // Hide all results except the selected one
    const userSearchResult = document.getElementById('userSearchResult');
    Array.from(userSearchResult.children).forEach(item => {
      if (item.getAttribute('data-id') === IDno) {
        item.style.display = 'block'; // Show the selected user
      } else {
        item.style.display = 'none'; // Hide others
      }
    });
  }

  // Set book ID in the input field after selecting a result
  function selectBook(bookID, title, index) {
    const inputField = document.getElementById(`bookID_${index}`);
    inputField.value = bookID;
    // We no longer clear the results when selecting a book

    // Hide all results except the selected one
    const bookSearchResult = document.getElementById(`bookSearchResult_${index}`);
    Array.from(bookSearchResult.children).forEach(item => {
      if (item.getAttribute('data-id') === bookID) {
        item.style.display = 'block'; // Show the selected book
      } else {
        item.style.display = 'none'; // Hide others
      }
    });
  }
</script>