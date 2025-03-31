<?php
    include '../../config.php';

    // Initialize message variables
    $message = "";
    $message_type = "";

    // Get the copy ID from the URL
    $ID = isset($_GET['book_copy_ID']) ? $_GET['book_copy_ID'] : '';

    // Fetch the current copy details from the database
    $sql = "SELECT * FROM book_copies WHERE book_copy_ID = '" . $conn->real_escape_string($ID) . "'";
    $result = $conn->query($sql);

    if (!$result) {
        $message = "Error retrieving copy details: " . $conn->error;
        $message_type = "error";
        $copy_data = [];
    } elseif ($result->num_rows == 0) {
        $message = "No copy found with the specified ID.";
        $message_type = "warning";
        $copy_data = [];
    } else {
        $copy_data = $result->fetch_assoc();
    }

    // Handle form submission for updating
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
        $B_title = isset($_POST['B_title']) ? $_POST['B_title'] : '';
        $copy_ID = isset($_POST['copy_ID']) ? $_POST['copy_ID'] : '';
        $note = isset($_POST['note']) ? $_POST['note'] : '';  // Added note here
        $callNumber = isset($_POST['callNumber']) ? $_POST['callNumber'] : '';
        $status = isset($_POST['status']) ? $_POST['status'] : '';
        $vendor = isset($_POST['vendor']) ? $_POST['vendor'] : '';
        $fundingSource = isset($_POST['fundingSource']) ? $_POST['fundingSource'] : '';
        $Sublocation = isset($_POST['Sublocation']) ? $_POST['Sublocation'] : '';
        $rating = isset($_POST['rating']) ? $_POST['rating'] : '';

        // Update the book copy in the database
        $update_sql = "UPDATE book_copies SET 
            copy_ID = '" . $conn->real_escape_string($copy_ID) . "',
            note = '" . $conn->real_escape_string($note) . "', 
            callNumber = '" . $conn->real_escape_string($callNumber) . "',
            status = '" . $conn->real_escape_string($status) . "',
            vendor = '" . $conn->real_escape_string($vendor) . "',
            fundingSource = '" . $conn->real_escape_string($fundingSource) . "',
            Sublocation = '" . $conn->real_escape_string($Sublocation) . "',
            rating = '" . $conn->real_escape_string($rating) . "' 
            WHERE book_copy_ID = '" . $conn->real_escape_string($ID) . "'";

        if ($conn->query($update_sql) === TRUE) {
            // Redirect to refresh the page after the update
            header("Location: " . $_SERVER['PHP_SELF'] . "?book_copy_ID=" . urlencode($ID));
            exit(); // Always call exit after header redirection
        } else {
            // Handle update failure
            $message = "Error updating copy: " . $conn->error;
            $message_type = "error";
        }
    }
?>

    <script src="https://cdn.tailwindcss.com"></script>

    <div class="container mx-auto mt-8 px-4">
        <a href="../viewcopy.php?book_copy_ID=<?php echo urlencode($copy_data['book_copy_ID']); ?>"
            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Return to Copy Details</a>

        <h2 class="text-2xl font-semibold mt-4 mb-4">Edit Copy</h2>

        <?php if (!empty($copy_data)): ?>
        <form method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8">
            <input type="hidden" name="action" value="update">
            <!-- Book Title Displayed at the Top -->
            <div class="mb-4">
                <h3 class="text-lg font-bold">Book Title: <?php echo htmlspecialchars($copy_data['B_title']); ?></h3>
            </div>
            <table class="table-auto w-full border-collapse border border-gray-300">
                <tbody>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">Copy ID</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" id="copy_ID" name="copy_ID" value="<?php echo htmlspecialchars($copy_data['copy_ID']); ?>" required>
                        </td>
                    </tr>
                    <!-- Added Note Field -->
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">Note</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" id="note" name="note" value="<?php echo htmlspecialchars($copy_data['note']); ?>" >
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">Call Number</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" id="callNumber" name="callNumber" value="<?php echo htmlspecialchars($copy_data['callNumber']); ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">Status</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" id="status" name="status" required>
                                <option value="Available" <?php echo $copy_data['status'] == 'Available' ? 'selected' : ''; ?>>Available</option>
                                <option value="Borrowed" <?php echo $copy_data['status'] == 'Borrowed' ? 'selected' : ''; ?>>Borrowed</option>
                                <option value="Reserved" <?php echo $copy_data['status'] == 'Reserved' ? 'selected' : ''; ?>>Reserved</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">Vendor</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" id="vendor" name="vendor" value="<?php echo htmlspecialchars($copy_data['vendor']); ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">Funding Source</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" id="fundingSource" name="fundingSource" value="<?php echo htmlspecialchars($copy_data['fundingSource']); ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">Sublocation</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" id="Sublocation" name="Sublocation" value="<?php echo htmlspecialchars($copy_data['Sublocation']); ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">Rating</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" id="rating" name="rating" required>
                                <option value="0" <?php echo $copy_data['rating'] == '0' ? 'selected' : ''; ?>>0</option>
                                <option value="1" <?php echo $copy_data['rating'] == '1' ? 'selected' : ''; ?>>1</option>
                                <option value="2" <?php echo $copy_data['rating'] == '2' ? 'selected' : ''; ?>>2</option>
                                <option value="3" <?php echo $copy_data['rating'] == '3' ? 'selected' : ''; ?>>3</option>
                                <option value="4" <?php echo $copy_data['rating'] == '4' ? 'selected' : ''; ?>>4</option>
                                <option value="5" <?php echo $copy_data['rating'] == '5' ? 'selected' : ''; ?>>5</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="flex items-center justify-between mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Save Changes</button>
            </div>
        </form>

        <?php endif; ?>
    </div>
