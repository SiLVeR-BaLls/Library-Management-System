<div class="m-6">
<form action="include/AddConnect.php" class="p-3 rounded-lg bg-white" method="post">
  
<!-- Title Section -->

<!-- Book Info Section -->
<div class="mb-8" id="title-section">
  <div class="bg-[#f2f2f2] p-6 border rounded-md shadow-sm">
    <h1 class="text-3xl font-bold text-blue-600 mb-6">Book Info</h1>
    <div class="mx-auto">
      <div class="flex items-center space-x-2 mb-4">
        <h1 class="text-3xl font-bold text-blue-600">Title</h1>
        <input type="text" id="B_title" name="B_title" required placeholder="Enter the book title"
          class="flex-grow px-4 py-2 border rounded-md">
      </div>
    </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="subtitle" class="block text-sm font-medium text-gray-700">Subtitle</label>
              <input type="text" id="subtitle" name="subtitle" placeholder="Enter subtitle if any"
                class="w-full px-4 py-2 border rounded-md">
            </div>
            <div>
              <label for="author" class="block text-sm font-medium text-gray-700">Author</label>
              <input type="text" id="author" name="author" placeholder="Enter author name"
                class="w-full px-4 py-2 border rounded-md">
            </div>
            <div>
              <label for="edition" class="block text-sm font-medium text-gray-700">Edition</label>
              <input type="text" id="edition" name="edition" placeholder="Edition of the book"
                class="w-full px-4 py-2 border rounded-md">
            </div>
          </div>
          <h2 class="text-xl font-semibold mt-6">Standard Numbers</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <div>
              <label for="LCCN" class="block text-sm font-medium text-gray-700">LCCN</label>
              <input type="text" id="LCCN" name="LCCN" placeholder="Enter LCCN"
                class="w-full px-4 py-2 border rounded-md">
            </div>
            <div>
              <label for="ISBN" class="block text-sm font-medium text-gray-700">ISBN</label>
              <input type="text" id="ISBN" name="ISBN" placeholder="Enter ISBN number"
                class="w-full px-4 py-2 border rounded-md">
            </div>
            <div>
              <label for="ISSN" class="block text-sm font-medium text-gray-700">ISSN</label>
              <input type="text" id="ISSN" name="ISSN" placeholder="Enter ISSN number"
                class="w-full px-4 py-2 border rounded-md">
            </div>
          </div>


          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="mt-6">
            <label for="MT" class="block text-sm font-medium text-gray-700">Material Type</label>
            <select name="MT" id="MT" class="w-full px-4 py-2 border rounded-md">
              <option selected value="Book">Book</option>
              <option value="Computer File">Computer File</option>
              <option value="Electronic Book">Electronic Book (E-Book)</option>
              <option value="Map">Map</option>
              <option value="Picture">Picture</option>
              <option value="Serial">Serial</option>
              <option value="Video">Video</option>
              <option value="Journal">Journal</option>
            </select>
          </div>

          <div class="mt-6">
            <label for="ST" class="block text-sm font-medium text-gray-700">SubType</label>
            <select name="ST" id="ST" class="w-full px-4 py-2 border rounded-md">
              <option value="Not Assigned" selected>No SubType Assigned</option>
              <option value="Braille">Braille</option>
              <option value="Hardcover">Hardcover</option>
              <option value="LargePrint">Large Print</option>
              <option value="Paperback">Paperback</option>
              <option value="Picture Book">Picture Book</option>
              <option value="Dictionary">Dictionary</option>
              <option value="Other">Other</option>
            </select>
          </div>
          </div>
          
          <h2 class="text-xl font-semibold mt-6">Publication Information</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div>
              <label for="place" class="block text-sm font-medium text-gray-700">Place</label>
              <input type="text" id="place" name="place" placeholder="Place of publication"
                class="w-full px-4 py-2 border rounded-md">
            </div>
            <div>
              <label for="publisher" class="block text-sm font-medium text-gray-700">Publisher</label>
              <input type="text" id="publisher" name="publisher" placeholder="Publisher name"
                class="w-full px-4 py-2 border rounded-md">
            </div>
            <div>
              <label for="copyright" class="block text-sm font-medium text-gray-700">Copyright</label>
              <input type="text" id="copyright" name="copyright" placeholder="Enter copyright details"
                class="w-full px-4 py-2 border rounded-md">
            </div>
          </div>
          <h2 class="text-xl font-semibold mt-6">Physical Description</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div>
              <label for="extent" class="block text-sm font-medium text-gray-700">Extent</label>
              <input type="text" id="extent" name="extent" placeholder="Number of pages or length"
                class="w-full px-4 py-2 border rounded-md">
            </div>
            <div>
              <label for="size" class="block text-sm font-medium text-gray-700">Size</label>
              <input type="text" id="size" name="size" placeholder="Dimensions of the book"
                class="w-full px-4 py-2 border rounded-md">
            </div>
            <div>
              <label for="Odetail" class="block text-sm font-medium text-gray-700">Other Details</label>
              <input type="text" id="Odetail" name="Odetail" placeholder="Any additional details"
                class="w-full px-4 py-2 border rounded-md">
            </div>
          </div>
        </div>
      </div>

      <!-- Series Info Section -->
      <div class="mb-8" id="series/note">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-[#f2f2f2] p-6 border rounded-md shadow-sm">
            <h1 class="text-3xl font-bold text-blue-600 mb-6">Series Info</h1>
            <div class="mb-4">
              <label for="volume" class="block text-sm font-medium text-gray-700">Volume</label>
              <input type="text" id="volume" name="volume" placeholder="Enter volume number"
                class="w-full px-4 py-2 border rounded-md">
            </div>
            <div class="mt-6">
              <h1 class="text-2xl font-semibold mb-4">Add a note</h1>
              <input id="note" class="w-full px-4 py-2 border rounded-md" name="note"
                placeholder="Write your note...">
            </div>
          </div>
          <div class="bg-[#f2f2f2] p-6 border rounded-md shadow-sm">
            <h1 class="text-3xl font-bold text-blue-600 mb-6">Subject</h1>
            <div class="subject-item mb-4">
              <div class="flex flex-col space-y-4">
                <div>
                  <label for="Sub_Head" class="block text-sm text-gray-700">Subject Heading</label>
                  <select id="Sub_Head" name="Sub_Head[]"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    <option value="Topic Heading" selected>Topic Heading</option>
                    <option value="Personal Heading">Personal Heading</option>
                    <option value="Geographic Heading">Geographic Heading</option>
                    <option value="Local Heading">Local Heading</option>
                  </select>
                </div>
                <div>
                  <label for="Sub_Head_input" class="block text-sm text-gray-700">Subject Heading Details</label>
                  <input type="text" id="Sub_Head_input" name="Sub_Head_input[]"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md mt-2 focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter subject heading details">
                </div>
              </div>
              <div class="mt-6">
                <button type="button" id="addSubject" class="bg-blue-500 text-white py-2 px-4 rounded-md">
                  Add Subject
                </button>
              </div>
              <div id="subjectsContainer" class="mt-4"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Resources Section -->
      <div class="mb-8" id="resources">
        <div class="bg-[#f2f2f2] p-6 border rounded-md shadow-sm">
          <h1 class="text-3xl font-bold text-blue-600 mb-6">Resources</h1>
        
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="url" class="block text-sm font-medium text-gray-700">URL</label>
              <input type="url" id="url" name="url" placeholder="Enter the resource URL"
                class="w-full px-4 py-2 border rounded-md">
            </div>
            
            <div>
              <label for="Description" class="block text-sm font-medium text-gray-700">Description</label>
              <input type="text" id="Description" name="Description" placeholder="Provide a description"
                class="w-full px-4 py-2 border rounded-md">
            </div>
          </div>
        </div>
      </div>

      <!-- Added Entries Section -->
      <div class="mb-8" id="added-entrie">
        <div class="bg-[#f2f2f2] p-6 border rounded-md shadow-sm">
          <div class="flex gap-6">
            <div class="w-full md:w-1/2">
              <h1 class="text-3xl font-bold text-blue-600 mb-6">Alternate Title</h1>
              <div class="flex flex-col gap-6">
                <div class="w-full">
                  <label for="UTitle" class="block text-sm font-medium text-gray-700">Uniform Title</label>
                  <input type="text" id="UTitle" name="UTitle" placeholder="Enter uniform title"
                    class="w-full px-4 py-2 border rounded-md">
                </div>
                <div class="w-full">
                  <label for="VForm" class="block text-sm font-medium text-gray-700">Varying Form</label>
                  <input type="text" id="VForm" name="VForm" placeholder="Enter varying form"
                    class="w-full px-4 py-2 border rounded-md">
                </div>
                <div class="w-full">
                  <label for="SUTitle" class="block text-sm font-medium text-gray-700">Series Uniform Title</label>
                  <input type="text" id="SUTitle" name="SUTitle" placeholder="Enter series uniform title"
                    class="w-full px-4 py-2 border rounded-md">
                </div>
              </div>
            </div>
            <div class="w-full md:w-1/2">
              <h1 class="text-3xl font-bold text-blue-600 mb-6">Co-Authors, Illustrator, Editor, etc.</h1>
              <div id="coAuthorsContainer">
                <div class="flex flex-grow gap-6">
                  <div class="flex gap-6 w-full">
                    <div class="w-full">
                      <label for="Co_Name" class="block text-sm font-medium text-gray-700">Co-Author's Name</label>
                      <input type="text" id="Co_Name" class="mt-1 p-2 border border-gray-300 rounded-md w-full"
                        placeholder="Enter co-author's name">
                    </div>
                    <div class="w-full">
                      <label for="Co_Date" class="block text-sm font-medium text-gray-700">Co-Author's Date</label>
                      <input type="date" id="Co_Date" class="mt-1 p-2 border border-gray-300 rounded-md w-full">
                    </div>
                    <div class="w-full">
                      <label for="Co_Role" class="block text-sm font-medium text-gray-700">Co-Author's Role</label>
                      <input type="text" id="Co_Role" class="mt-1 p-2 border border-gray-300 rounded-md w-full"
                        placeholder="Enter co-author's role">
                    </div>
                  </div>
                </div>
                <button type="button" id="addCoAuthor"
                  class="mt-4 bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Add
                  Another Co-Author</button>
              </div>
              <div id="coAuthorsList" class="mt-4"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="flex justify-between mt-8">
        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-md ml-auto">Add</button>
      </div>
    </form>
  </div>

<!-- Script for add subject -->
<script>
  // Handle the adding of a subject entry
  document.getElementById('addSubject').addEventListener('click', function() {
    const subjectHeading = document.querySelector('#Sub_Head').value;
    const subjectDetails = document.querySelector('#Sub_Head_input').value;

    if (subjectHeading && subjectDetails) {
      const subjectsContainer = document.getElementById('subjectsContainer');
      const newSubjectEntry = document.createElement('div');
      newSubjectEntry.classList.add('subject-entry', 'flex', 'items-center', 'space-x-4');
      newSubjectEntry.innerHTML = `
      <div class="flex-grow subject-info">
        <span>${subjectHeading} - ${subjectDetails}</span>
      </div>
      <button type="button" class="removeSubject text-red-500">
        <i class="fas fa-trash-alt"></i>
      </button>
    `;
      subjectsContainer.appendChild(newSubjectEntry);

      // Clear input fields after adding
      document.querySelector('#Sub_Head').value = 'Topic Heading'; // Reset to default
      document.querySelector('#Sub_Head_input').value = ''; // Clear input field
    } else {
      alert('Please fill out both fields.');
    }
  });

  // Handle the removal of a subject entry
  document.getElementById('subjectsContainer').addEventListener('click', function(e) {
    if (e.target.classList.contains('removeSubject')) {
      const subjectEntry = e.target.closest('.subject-entry');
      if (subjectEntry) {
        subjectEntry.remove();
      }
    }
  });

  // Handle form submission to save only displayed subjects
  document.querySelector('form').addEventListener('submit', function(event) {
    // Prevent default form submission
    event.preventDefault();

    // Clear existing hidden inputs related to subjects
    const existingHiddenInputs = document.querySelectorAll('input.subject-hidden');
    existingHiddenInputs.forEach(input => input.remove());

    // Get all the dynamically added subjects
    const displayedSubjects = document.querySelectorAll('#subjectsContainer .subject-entry');

    // Loop through each displayed subject to collect the data
    displayedSubjects.forEach(subject => {
      const subjectInfo = subject.querySelector('.subject-info span').textContent;
      const [heading, details] = subjectInfo.split(" - ");

      // Create and append new hidden inputs
      const inputHead = document.createElement('input');
      inputHead.type = 'hidden';
      inputHead.name = 'Sub_Head[]';
      inputHead.value = heading.trim();
      inputHead.classList.add('subject-hidden');
      event.target.appendChild(inputHead);

      const inputDetails = document.createElement('input');
      inputDetails.type = 'hidden';
      inputDetails.name = 'Sub_Head_input[]';
      inputDetails.value = details.trim();
      inputDetails.classList.add('subject-hidden');
      event.target.appendChild(inputDetails);
    });

    // Clear static input fields before submission
    document.querySelector('#Sub_Head').name = '';
    document.querySelector('#Sub_Head_input').name = '';

    // Submit the form after processing
    event.target.submit();
  });
</script>

<!-- script for coauthor -->
<script>
  document.getElementById('addCoAuthor').addEventListener('click', function() {
    // Get the form fields
    const coName = document.querySelector('#Co_Name').value;
    const coDate = document.querySelector('#Co_Date').value;
    const coRole = document.querySelector('#Co_Role').value;

    // Validate input before adding
    if (coName && coDate && coRole) {
      // Create a new co-author entry display below the form
      const coAuthorsContainer = document.getElementById('coAuthorsList');
      const newCoAuthorDisplay = document.createElement('div');
      newCoAuthorDisplay.classList.add('co-author-entry', 'flex', 'justify-between', 'items-center', 'space-x-4', );
      newCoAuthorDisplay.innerHTML = `
        <div class="flex-grow">
          <span class="font-medium">${coName}</span>
          <span class="text-gray-500">(${coDate})</span>
          <span>- ${coRole}</span>
        </div>
        <button type="button" class="removeCoAuthor text-red-500 hover:text-red-700">
          <i class="fas fa-trash-alt"></i>
        </button>
      `;
      coAuthorsContainer.appendChild(newCoAuthorDisplay);

      // Add hidden input fields for submission only for the added co-author
      const form = document.querySelector('form');
      const coNameInput = document.createElement('input');
      coNameInput.type = 'hidden';
      coNameInput.name = 'Co_Name[]';
      coNameInput.value = coName;
      form.appendChild(coNameInput);

      const coDateInput = document.createElement('input');
      coDateInput.type = 'hidden';
      coDateInput.name = 'Co_Date[]';
      coDateInput.value = coDate;
      form.appendChild(coDateInput);

      const coRoleInput = document.createElement('input');
      coRoleInput.type = 'hidden';
      coRoleInput.name = 'Co_Role[]';
      coRoleInput.value = coRole;
      form.appendChild(coRoleInput);

      // Clear input fields for the next entry
      document.querySelector('#Co_Name').value = '';
      document.querySelector('#Co_Date').value = '';
      document.querySelector('#Co_Role').value = '';
    } else {
      alert("Please fill out all fields.");
    }
  });

  // Event listener for removing co-authors from display
  document.getElementById('coAuthorsList').addEventListener('click', function(e) {
    if (e.target.classList.contains('removeCoAuthor')) {
      // Find the parent of the button, which is the entire co-author entry
      const coAuthorEntry = e.target.closest('.co-author-entry');

      // Remove the co-author entry from the display
      if (coAuthorEntry) {
        coAuthorEntry.remove();
      }

      // Also remove the corresponding hidden inputs from the form
      const coName = coAuthorEntry.querySelector('.font-medium').textContent.trim();
      const coInputs = document.querySelectorAll(`input[name='Co_Name[]']`);

      // Find and remove the corresponding hidden input for the co-author
      for (const input of coInputs) {
        if (input.value === coName) {
          input.remove();
          break;
        }
      }
    }
  });
</script>