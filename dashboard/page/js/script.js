function toggleMobileMenu() {
    const navLinks = document.getElementById('nav-links');
    navLinks.classList.toggle('active');
}

// Close dropdown when clicking outside
window.onclick = function(event) {
    const dropdownMenu = document.getElementById('dropdown-menu');
    if (!event.target.matches('#profile')) {
        dropdownMenu.style.display = 'none';
    }
}

function showSection(sectionId) {
    var sections = document.querySelectorAll('.main-content');
    sections.forEach(function (section) {
        section.classList.remove('active');
    });

    var section = document.getElementById(sectionId);
    section.classList.add('active');
}

function resetCommonForm() {
    var form = document.querySelectorAll('.main-content.active form');
    form.forEach(function (f) {
        f.reset();
    });
}

window.onload = function() {
    showSection('title-section'); // Ensure the 'Brief Title' section is shown by default after page load
};

function openCommentModal() {
    var modal = document.getElementById('commentModal');
    modal.style.display = 'block';
}

// Close Modal
function closeCommentModal() {
    var modal = document.getElementById('commentModal');
    modal.style.display = 'none';
}

// Submit Comment
function submitComment() {
    var comment = document.getElementById('commentInput').value;
    if (comment.trim() === "") {
        alert("Please enter a comment before submitting.");
        return;
    }

    var commentsDisplay = document.getElementById("commentsDisplay");

    // Create comment container
    var commentDiv = document.createElement("div");
    commentDiv.className = "comment";

    // Create comment text
    var newComment = document.createElement("span");
    newComment.className = "comment-text";
    newComment.textContent = comment;

    // Create remove button
    var removeBtn = document.createElement("span");
    removeBtn.className = "remove-btn";
    removeBtn.textContent = "Delete";
    removeBtn.onclick = function() {
        commentsDisplay.removeChild(commentDiv);
    };

    // Append the comment text and remove button to the comment container
    commentDiv.appendChild(newComment);
    commentDiv.appendChild(removeBtn);

    // Append the comment container to the comments display area
    commentsDisplay.appendChild(commentDiv);

    // Clear the textarea and close the modal
    document.getElementById('commentInput').value = "";
    closeCommentModal();
}

// Close modal if clicked outside of content
window.onclick = function(event) {
    var modal = document.getElementById('commentModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
};

// for co_authotr
document.getElementById('addCoAuthor').addEventListener('click', function() {
    const coAuthorsContainer = document.getElementById('coAuthorsContainer');
    const newCoAuthor = document.createElement('div');
    newCoAuthor.classList.add('form-co-author');
    newCoAuthor.innerHTML = `
        <div class="form-book">
            <label for="Co_Name[]">Name</label>
            <input type="text" id="Co_Name" name="Co_Name[]" placeholder="Enter co-author's name" required>
        </div>
        <div class="form-book">
            <label for="Co_Date[]">Date</label>
            <input type="date" id="Co_Date" name="Co_Date[]" >
        </div>
        <div class="form-book">
            <label for="Co_Role[]">Role</label>
            <input type="text" id="Co_Role" name="Co_Role[]" placeholder="Enter co-author's role" >
        </div>
        <button type="button" class="removeCoAuthor">Remove</button>
    `;
    coAuthorsContainer.appendChild(newCoAuthor);
});

document.getElementById('coAuthorsContainer').addEventListener('click', function(e) {
    if (e.target.classList.contains('removeCoAuthor')) {
        e.target.parentElement.remove();
    }
});
