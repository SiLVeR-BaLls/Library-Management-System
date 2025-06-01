<?php
    include 'config.php';

    // Initialize message variables
    $message = "";
    $message_type = "";

    // Get the book title from the query string
    $title = $_GET['title'] ?? '';

    if ($title) {
        // Fetch book details
        $sql = "SELECT * FROM book WHERE book_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $title);
        $stmt->execute();
        $result = $stmt->get_result();
        $book = $result->num_rows > 0 ? $result->fetch_assoc() : null;
        $stmt->close();

        if (!$book) {
            $message = "No book found with that ID.";
            $message_type = "error";
        }

        // Fetch co-authors
        $stmt = $conn->prepare("SELECT * FROM coauthor WHERE book_id = ?");
        $stmt->bind_param("s", $title);
        $stmt->execute();
        $coAuthorsResult = $stmt->get_result();
        $stmt->close();

        // Fetch subjects
        $stmt = $conn->prepare("SELECT * FROM subject WHERE book_id = ?");
        $stmt->bind_param("s", $title);
        $stmt->execute();
        $subjectResult = $stmt->get_result();
        $stmt->close();
    } else {
        $message = "No book title provided.";
        $message_type = "error";
    }

    // Handle form submission for updating book details, co-authors, and subjects
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        // Update co-authors
        if (!empty($_POST['Co_Name']) && !empty($_POST['Co_Date']) && !empty($_POST['Co_Role'])) {
            $coNames = $_POST['Co_Name'];
            $coDates = $_POST['Co_Date'];
            $coRoles = $_POST['Co_Role'];
            $coAuthorIds = $_POST['co_author_ids'] ?? [];

            for ($i = 0; $i < count($coNames); $i++) {
                $name = $conn->real_escape_string($coNames[$i]);
                $date = $conn->real_escape_string($coDates[$i]);
                $role = $conn->real_escape_string($coRoles[$i]);

                if (isset($coAuthorIds[$i]) && !empty($coAuthorIds[$i])) {
                    // Update existing co-author
                    $coAuthorId = (int)$coAuthorIds[$i];
                    $updateQuery = "UPDATE coauthor SET Co_Name = ?, Co_Date = ?, Co_Role = ? WHERE co_author_id = ? AND book_id = ?";
                    $stmt = $conn->prepare($updateQuery);
                    $stmt->bind_param("sssis", $name, $date, $role, $coAuthorId, $title);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    // Insert new co-author
                    $insertQuery = "INSERT INTO coauthor (book_id, Co_Name, Co_Date, Co_Role) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($insertQuery);
                    $stmt->bind_param("ssss", $title, $name, $date, $role);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }

        // Update subjects
        if (!empty($_POST['subject_heads']) && !empty($_POST['subject_inputs'])) {
            $subjectHeads = $_POST['subject_heads'];
            $subjectInputs = $_POST['subject_inputs'];
            $subjectIds = $_POST['subject_ids'] ?? [];

            for ($i = 0; $i < count($subjectHeads); $i++) {
                $head = $conn->real_escape_string($subjectHeads[$i]);
                $input = $conn->real_escape_string($subjectInputs[$i]);

                if (isset($subjectIds[$i]) && !empty($subjectIds[$i])) {
                    // Update existing subject
                    $subjectId = (int)$subjectIds[$i];
                    $updateQuery = "UPDATE subject SET Sub_Head = ?, Sub_Head_input = ? WHERE subject_id = ? AND book_id = ?";
                    $stmt = $conn->prepare($updateQuery);
                    $stmt->bind_param("ssis", $head, $input, $subjectId, $title);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    // Insert new subject
                    $insertQuery = "INSERT INTO subject (book_id, Sub_Head, Sub_Head_input) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($insertQuery);
                    $stmt->bind_param("sss", $title, $head, $input);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }

        // ** Handle Book Details Update and File Upload **
        if (empty($message)) {
            // Process file upload for book photo
            $photoPath = $book['photo']; // Default to existing photo
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; // Allowed MIME types

            if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
                $fileType = mime_content_type($_FILES['photo']['tmp_name']);
                $fileSize = $_FILES['photo']['size'];

                if (!in_array($fileType, $allowedTypes) || $fileSize > 2 * 1024 * 1024) { // 2 MB limit
                    $message = "Invalid image format or file too large.";
                    $message_type = "error";
                } else {
                    $uploadDir = '../pic/Book/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    // Delete existing photo if present
                    if (!empty($book['photo'])) {
                        unlink($uploadDir . $book['photo']);
                    }

                    // Handle new file upload
                    $fileName = uniqid() . '_' . basename($_FILES['photo']['name']);
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $fileName)) {
                        $photoPath = $fileName;
                    } else {
                        $message = "Failed to upload photo.";
                        $message_type = "error";
                    }
                }
            }

            // ** Update Book Information **
            if (empty($message)) {
                $sql = "UPDATE book SET subtitle=?, author=?, edition=?, LCCN=?, ISBN=?, ISSN=?, MT=?, ST=?, place=?, publisher=?, Pdate=?, copyright=?, extent=?, Odetail=?, size=?, Description=?, url=?, UTitle=?, VForm=?, SUTitle=?, photo=? WHERE book_id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssssssssssssssssss", $_POST['subtitle'], $_POST['author'], $_POST['edition'], $_POST['LCCN'], $_POST['ISBN'], $_POST['ISSN'], $_POST['MT'], $_POST['ST'], $_POST['place'], $_POST['publisher'], $_POST['Pdate'], $_POST['copyright'], $_POST['extent'], $_POST['Odetail'], $_POST['size'], $_POST['Description'], $_POST['url'], $_POST['UTitle'], $_POST['VForm'], $_POST['SUTitle'], $photoPath, $title);

                if (!$stmt->execute()) {
                    $message = "Error updating book: " . $stmt->error;
                    $message_type = "error";
                }
                $stmt->close();
            }
        }

        // ** Redirect After Update or Error **
        if (empty($message)) {
            // Redirect to the book view page on success
            header("Location: ViewBook.php?message=success&title=" . urlencode($title));
            exit();
        }
    }

    // Handle book deletion
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        // Get the photo filename before deleting the book record
        $photoToDelete = $book['photo'] ?? '';

        $deleteSql = "DELETE FROM Book WHERE book_id = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param("s", $title);

        if ($deleteStmt->execute()) {
            // Check if the photo exists and delete it from the directory
            if ($photoToDelete && file_exists("../pic/Book/" . $photoToDelete)) {
                unlink("../pic/Book/" . $photoToDelete);
            }

            // Redirect to index page after deletion
            header("Location: index.php?message=deleted&title=" . urlencode($title));
            exit();
        } else {
            $message = "Error deleting book: " . $deleteStmt->error;
            $message_type = "error";
        }
        $deleteStmt->close();
    }
?>

<title>Edit Book</title>

<div class="flex">
    <?php include $sidebars[$userType] ?? ''; ?>

    <div class="flex-grow">
        <?php include 'include/header.php'; ?>

        <div class="container mx-auto px-2 py-4"> <!-- Reduced padding -->
            <div class="text-sm text-gray-600 mb-2"> <!-- Reduced margin -->
                <a href="index.php" class="hover:text-blue-800 hover:underline">Home</a> &rarr;
                <?php if ($book): ?>
                    <a href="ViewBook.php?title=<?php echo urlencode($book['book_id']); ?>" class="hover:text-blue-800 hover:underline">
                        <?php echo htmlspecialchars($book['B_title']); ?>
                    </a> &rarr;
                    <span>Edit Copy</span>
                <?php else: ?>
                    <span>Book not found</span>
                <?php endif; ?>
            </div>

            <!-- Return Button -->
            <a href="ViewBook.php?title=<?php echo urlencode($book['book_id']); ?>" class="inline-block bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300 mb-2">
                &larr; Return
            </a> <!-- Updated to look like a button -->

            <?php if ($book): ?>
                <h2 class="text-2xl font-semibold mt-2 mb-4 text-gray-800">Editing: <?php echo htmlspecialchars($book['B_title']); ?></h2> <!-- Reduced font size and margins -->
                <form method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-4 space-y-4"> <!-- Reduced padding and spacing -->
                    <?php if ($message): ?>
                        <div class="p-3 text-sm text-<?php echo $message_type === 'error' ? 'red' : 'green'; ?>-700 bg-<?php echo $message_type === 'error' ? 'red' : 'green'; ?>-100 rounded-lg">
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Photo Upload -->
                    <div>
                        <label for="photo" class="block text-gray-700 font-medium mb-1">Upload Photo:</label> <!-- Reduced margin -->
                        <?php if (!empty($book['photo'])): ?>
                            <img src="../pic/Book/<?php echo htmlspecialchars($book['photo']); ?>" alt="Book Photo"
                                 class="w-36 h-36 mx-auto rounded-lg shadow-md"> <!-- Reduced size -->
                        <?php else: ?>
                            <img src="../pic/default/book.jpg" alt="Default Book Photo"
                                 class="w-36 h-36 mx-auto rounded-lg shadow-md"> <!-- Reduced size -->
                        <?php endif; ?>
                        <input type="file" id="photo" name="photo" class="mt-2"> <!-- Reduced margin -->
                    </div>

                    <!-- Book Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4"> <!-- Reduced gap -->
                        <div>
                            <label for="subtitle" class="block text-gray-700 font-medium mb-1">Subtitle:</label> <!-- Reduced margin -->
                            <input type="text" id="subtitle" name="subtitle" value="<?php echo htmlspecialchars($book['subtitle']); ?>" class="shadow-sm border rounded-lg w-full py-1 px-2 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300"> <!-- Reduced padding -->
                        </div>
                        <div>
                            <label for="author" class="block text-gray-700 font-medium mb-1">Author:</label> <!-- Reduced margin -->
                            <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" class="shadow-sm border rounded-lg w-full py-1 px-2 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300"> <!-- Reduced padding -->
                        </div>
                        <div>
                            <label for="edition" class="block text-gray-700 font-medium mb-1">Edition:</label> <!-- Reduced margin -->
                            <input type="text" id="edition" name="edition" value="<?php echo htmlspecialchars($book['edition']); ?>" class="shadow-sm border rounded-lg w-full py-1 px-2 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300"> <!-- Reduced padding -->
                        </div>
                        <div>
                            <label for="LCCN" class="block text-gray-700 font-medium mb-1">LCCN:</label> <!-- Reduced margin -->
                            <input type="text" id="LCCN" name="LCCN" value="<?php echo htmlspecialchars($book['LCCN']); ?>" class="shadow-sm border rounded-lg w-full py-1 px-2 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300"> <!-- Reduced padding -->
                        </div>
                        <div>
                            <label for="ISBN" class="block text-gray-700 font-medium mb-1">ISBN:</label> <!-- Reduced margin -->
                            <input type="text" id="ISBN" name="ISBN" value="<?php echo htmlspecialchars($book['ISBN']); ?>" class="shadow-sm border rounded-lg w-full py-1 px-2 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300"> <!-- Reduced padding -->
                        </div>
                        <div>
                            <label for="ISSN" class="block text-gray-700 font-medium mb-1">ISSN:</label> <!-- Reduced margin -->
                            <input type="text" id="ISSN" name="ISSN" value="<?php echo htmlspecialchars($book['ISSN']); ?>" class="shadow-sm border rounded-lg w-full py-1 px-2 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300"> <!-- Reduced padding -->
                        </div>
                        <div class="mt-6">
                            <label for="MT" class="block text-sm font-medium text-gray-700">Material Type</label>
                            <select name="MT" id="MT" class="w-full px-4 py-2 border rounded-md">
                                <option value="" <?php echo empty($book['MT']) ? 'selected' : ''; ?>>Select Material Type</option>
                                <option value="Book" <?php echo $book['MT'] === 'Book' ? 'selected' : ''; ?>>Book</option>
                                <option value="Computer File" <?php echo $book['MT'] === 'Computer File' ? 'selected' : ''; ?>>Computer File</option>
                                <option value="Electronic Book" <?php echo $book['MT'] === 'Electronic Book' ? 'selected' : ''; ?>>Electronic Book (E-Book)</option>
                                <option value="Equipment" <?php echo $book['MT'] === 'Equipment' ? 'selected' : ''; ?>>Equipment</option>
                                <option value="Kit" <?php echo $book['MT'] === 'Kit' ? 'selected' : ''; ?>>Kit</option>
                                <option value="Manuscript Language Material" <?php echo $book['MT'] === 'Manuscript Language Material' ? 'selected' : ''; ?>>Manuscript Language Material</option>
                                <option value="Map" <?php echo $book['MT'] === 'Map' ? 'selected' : ''; ?>>Map</option>
                                <option value="Mixed Material" <?php echo $book['MT'] === 'Mixed Material' ? 'selected' : ''; ?>>Mixed Material</option>
                                <option value="Music" <?php echo $book['MT'] === 'Music' ? 'selected' : ''; ?>>Music (Printed)</option>
                                <option value="Picture" <?php echo $book['MT'] === 'Picture' ? 'selected' : ''; ?>>Picture</option>
                                <option value="Serial" <?php echo $book['MT'] === 'Serial' ? 'selected' : ''; ?>>Serial</option>
                                <option value="Musical Sound Recording" <?php echo $book['MT'] === 'Musical Sound Recording' ? 'selected' : ''; ?>>Musical Sound Recording</option>
                                <option value="NonMusical Sound Recording" <?php echo $book['MT'] === 'NonMusical Sound Recording' ? 'selected' : ''; ?>>NonMusical Sound Recording</option>
                                <option value="Video" <?php echo $book['MT'] === 'Video' ? 'selected' : ''; ?>>Video</option>
                                <option value="Journal" <?php echo $book['MT'] === 'Journal' ? 'selected' : ''; ?>>Journal</option>
                            </select>
                        </div>
                        <div class="mt-6">
                            <label for="ST" class="block text-sm font-medium text-gray-700">SubType</label>
                            <select name="ST" id="ST" class="w-full px-4 py-2 border rounded-md">
                                <option value="Not Assigned" <?php echo $book['ST'] === 'Not Assigned' ? 'selected' : ''; ?>>No SubType Assigned</option>
                                <option value="Braille" <?php echo $book['ST'] === 'Braille' ? 'selected' : ''; ?>>Braille</option>
                                <option value="Hardcover" <?php echo $book['ST'] === 'Hardcover' ? 'selected' : ''; ?>>Hardcover</option>
                                <option value="LargePrint" <?php echo $book['ST'] === 'LargePrint' ? 'selected' : ''; ?>>Large Print</option>
                                <option value="Paperback" <?php echo $book['ST'] === 'Paperback' ? 'selected' : ''; ?>>Paperback</option>
                                <option value="Picture Book" <?php echo $book['ST'] === 'Picture Book' ? 'selected' : ''; ?>>Picture Book</option>
                                <option value="Dictionary" <?php echo $book['ST'] === 'Dictionary' ? 'selected' : ''; ?>>Dictionary</option>
                                <option value="Other" <?php echo $book['ST'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        <div>
                            <label for="place" class="block text-gray-700 font-medium mb-1">Place:</label> <!-- Reduced margin -->
                            <input type="text" id="place" name="place" value="<?php echo htmlspecialchars($book['place']); ?>" class="shadow-sm border rounded-lg w-full py-1 px-2 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300"> <!-- Reduced padding -->
                        </div>
                        <div>
                            <label for="publisher" class="block text-gray-700 font-medium mb-1">Publisher:</label> <!-- Reduced margin -->
                            <input type="text" id="publisher" name="publisher" value="<?php echo htmlspecialchars($book['publisher']); ?>" class="shadow-sm border rounded-lg w-full py-1 px-2 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300"> <!-- Reduced padding -->
                        </div>
                        <div>
                            <label for="Pdate" class="block text-gray-700 font-medium mb-1">Publication Date:</label> <!-- Reduced margin -->
                            <input type="date" id="Pdate" name="Pdate" value="<?php echo htmlspecialchars($book['Pdate']); ?>" class="shadow-sm border rounded-lg w-full py-1 px-2 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300"> <!-- Reduced padding -->
                        </div>
                        <div>
                            <label for="copyright" class="block text-gray-700 font-medium mb-1">Copyright:</label> <!-- Reduced margin -->
                            <input type="text" id="copyright" name="copyright" value="<?php echo htmlspecialchars($book['copyright']); ?>" class="shadow-sm border rounded-lg w-full py-1 px-2 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300"> <!-- Reduced padding -->
                        </div>
                        <div>
                            <label for="extent" class="block text-gray-700 font-medium mb-1">Extent:</label> <!-- Reduced margin -->
                            <input type="text" id="extent" name="extent" value="<?php echo htmlspecialchars($book['extent']); ?>" class="shadow-sm border rounded-lg w-full py-1 px-2 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300"> <!-- Reduced padding -->
                        </div>
                        <div>
                            <label for="Odetail" class="block text-gray-700 font-medium mb-1">Other Details:</label> <!-- Reduced margin -->
                            <textarea id="Odetail" name="Odetail" class="shadow-sm border rounded-lg w-full py-1 px-2 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300"><?php echo htmlspecialchars($book['Odetail']); ?></textarea> <!-- Reduced padding -->
                        </div>
                        <div>
                            <label for="size" class="block text-gray-700 font-medium mb-1">Size:</label> <!-- Reduced margin -->
                            <input type="text" id="size" name="size" value="<?php echo htmlspecialchars($book['size']); ?>" class="shadow-sm border rounded-lg w-full py-1 px-2 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300"> <!-- Reduced padding -->
                        </div>
                    </div>

                    <!-- Co-Authors -->
                    <div>
                        <h3 class="text-md font-semibold text-gray-700 mb-2">Co-Authors</h3> <!-- Reduced font size and margin -->
                        <div id="coAuthorsContainer" class="space-y-2"> <!-- Reduced spacing -->
                            <?php if ($coAuthorsResult->num_rows > 0): ?>
                                <?php while ($row = $coAuthorsResult->fetch_assoc()): ?>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 relative">
                                        <input type="hidden" name="co_author_ids[]" value="<?php echo $row['co_author_id']; ?>">
                                        <input type="text" name="Co_Name[]" value="<?php echo htmlspecialchars($row['Co_Name']); ?>" placeholder="Name" class="shadow-sm border rounded-lg w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300">
                                        <input type="date" name="Co_Date[]" value="<?php echo htmlspecialchars($row['Co_Date']); ?>" class="shadow-sm border rounded-lg w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300">
                                        <input type="text" name="Co_Role[]" value="<?php echo htmlspecialchars($row['Co_Role']); ?>" placeholder="Role" class="shadow-sm border rounded-lg w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300">
                                        <button type="button" class="absolute top-0 right-0 text-red-500 hover:text-red-700 remove-entry">
                                            &times;
                                        </button>
                                    </div>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </div>
                        <button type="button" id="addCoAuthor" class="bg-blue-500 text-white py-1 px-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300 mt-2">Add Co-Author</button> <!-- Reduced padding and margin -->
                    </div>

                    <!-- Subjects -->
                    <div>
                        <h3 class="text-md font-semibold text-gray-700 mb-2">Subjects</h3> <!-- Reduced font size and margin -->
                        <div id="subjectsContainer" class="space-y-2"> <!-- Reduced spacing -->
                            <?php if ($subjectResult->num_rows > 0): ?>
                                <?php while ($row = $subjectResult->fetch_assoc()): ?>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 relative">
                                        <input type="hidden" name="subject_ids[]" value="<?php echo $row['subject_id']; ?>">
                                        <input type="text" name="subject_heads[]" value="<?php echo htmlspecialchars($row['Sub_Head']); ?>" placeholder="Subject Head" class="shadow-sm border rounded-lg w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300">
                                        <input type="text" name="subject_inputs[]" value="<?php echo htmlspecialchars($row['Sub_Head_input']); ?>" placeholder="Subject Input" class="shadow-sm border rounded-lg w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300">
                                        <button type="button" class="absolute top-0 right-0 text-red-500 hover:text-red-700 remove-entry">
                                            &times;
                                        </button>
                                    </div>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </div>
                        <button type="button" id="addSubject" class="bg-blue-500 text-white py-1 px-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300 mt-2">Add Subject</button> <!-- Reduced padding and margin -->
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-2"> <!-- Reduced spacing -->
                        <button type="submit" name="update" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded-lg focus:outline-none focus:ring focus:ring-green-300" onclick="return confirm('Are you sure you want to update this book?');">Update Book</button> <!-- Added confirmation -->
                        <button type="submit" name="delete" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded-lg focus:outline-none focus:ring focus:ring-red-300" onclick="return confirm('Are you sure you want to delete this book? This action cannot be undone.');">Delete Book</button> <!-- Added confirmation -->
                    </div>
                </form>
            <?php endif; ?>
        </div>

        <?php include 'include/footer.php'; ?>
    </div>
</div>

<script>
    // Add Co-Author
    document.getElementById('addCoAuthor').addEventListener('click', function () {
        const container = document.getElementById('coAuthorsContainer');
        const div = document.createElement('div');
        div.className = 'grid grid-cols-1 md:grid-cols-3 gap-4 relative';
        div.innerHTML = `
            <input type="text" name="Co_Name[]" placeholder="Name" class="shadow-sm border rounded-lg w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300">
            <input type="date" name="Co_Date[]" class="shadow-sm border rounded-lg w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300">
            <input type="text" name="Co_Role[]" placeholder="Role" class="shadow-sm border rounded-lg w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300">
            <button type="button" class="absolute top-0 right-0 text-red-500 hover:text-red-700 remove-entry">
                &times;
            </button>
        `;
        container.appendChild(div);
    });

    // Add Subject
    document.getElementById('addSubject').addEventListener('click', function () {
        const container = document.getElementById('subjectsContainer');
        const div = document.createElement('div');
        div.className = 'grid grid-cols-1 md:grid-cols-2 gap-4 relative';
        div.innerHTML = `
            <input type="text" name="subject_heads[]" placeholder="Subject Head" class="shadow-sm border rounded-lg w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300">
            <input type="text" name="subject_inputs[]" placeholder="Subject Input" class="shadow-sm border rounded-lg w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring focus:ring-blue-300">
            <button type="button" class="absolute top-0 right-0 text-red-500 hover:text-red-700 remove-entry">
                &times;
            </button>
        `;
        container.appendChild(div);
    });

    // Remove Entry
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-entry')) {
            e.target.parentElement.remove();
        }
    });
</script>