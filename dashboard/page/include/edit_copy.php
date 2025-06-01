<?php
    include '../../config.php';

    // Initialize message variables
    $message = "";
    $message_type = "";

    // Get the copy ID from the URL
    $book_copy_ID = isset($_GET['book_copy_ID']) ? $_GET['book_copy_ID'] : '';

    // Fetch data for dropdowns
    $vendors_result = $conn->query("SELECT name, name FROM vendor");
    $fund_result = $conn->query("SELECT name, name FROM fundingsource");
    $sub_result = $conn->query("SELECT name, name FROM sublocation");

    // Fetch the current copy details along with the names from related tables
    $sql = "SELECT bc.*, b.B_title,
                    v.name AS vendor_name,
                    fs.name AS funding_source_name,
                    sl.name AS sublocation_name
            FROM book_copies bc
            JOIN book b ON bc.book_ID = b.book_ID
            LEFT JOIN vendor v ON bc.vendor = v.name
            LEFT JOIN fundingsource fs ON bc.fundingSource = fs.name
            LEFT JOIN sublocation sl ON bc.sublocation = sl.name
            WHERE bc.book_copy_ID = '" . $conn->real_escape_string($book_copy_ID) . "'";
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
        $copy_ID = isset($_POST['copy_ID']) ? $_POST['copy_ID'] : '';
        $note = isset($_POST['note']) ? $_POST['note'] : '';
        $callNumber = isset($_POST['callNumber']) ? $_POST['callNumber'] : '';
        $status = isset($_POST['status']) ? $_POST['status'] : '';
        $vendor_id = isset($_POST['vendor']) ? $_POST['vendor'] : '';
        $fundingSource_id = isset($_POST['fundingSource']) ? $_POST['fundingSource'] : '';
        $sublocation_id = isset($_POST['sublocation']) ? $_POST['sublocation'] : '';
        $rating = isset($_POST['rating']) ? $_POST['rating'] : '';

        // Prepare the update statement
        $stmt = $conn->prepare("UPDATE book_copies SET
            copy_id = ?,
            note = ?,
            callNumber = ?,
            status = ?,
            vendor = ?,
            fundingSource = ?,
            sublocation = ?,
            rating = ?
            WHERE book_copy_ID = ?"
        );

        $stmt->bind_param("sssssssss", // All are strings now
            $copy_ID,
            $note,
            $callNumber,
            $status,
            $vendor_id,
            $fundingSource_id,
            $sublocation_id,
            $rating,
            $book_copy_ID
        );

        if ($stmt->execute()) {
            header("Location: ../../viewcopy.php?book_copy_ID=" . urlencode($book_copy_ID));
            exit();
        } else {
            $message = "Error updating copy: " . $stmt->error;
            $message_type = "error";
        }

        $stmt->close();
    }

    // Move helper functions to the top so they are only defined once and can be reused
    function renderOptions($rows, $selected_name) {
        $options = '';
        $found = false;
        foreach ($rows as $row) {
            if (trim($row['name']) === trim($selected_name)) {
                $found = true;
                break;
            }
        }
        foreach ($rows as $i => $row) {
            $selected = '';
            if ($found && trim($row['name']) === trim($selected_name)) {
                $selected = 'selected';
            } elseif (!$found && $i === 0) {
                $selected = 'selected';
            }
            $options .= "<option value='" . htmlspecialchars($row['name']) . "' $selected>" . htmlspecialchars($row['name']) . "</option>";
        }
        return $options;
    }

    // Fetch all dropdown data as arrays (not result sets)
    $vendors_result = $conn->query("SELECT id, name FROM vendor");
    $fund_result = $conn->query("SELECT id, name FROM fundingsource");
    $sub_result = $conn->query("SELECT id, name FROM sublocation");

    $vendors = $vendors_result ? $vendors_result->fetch_all(MYSQLI_ASSOC) : [];
    $funds = $fund_result ? $fund_result->fetch_all(MYSQLI_ASSOC) : [];
    $sublocs = $sub_result ? $sub_result->fetch_all(MYSQLI_ASSOC) : [];

    // Update renderOptions to use id for value and match by id
    function renderOptionsById($rows, $selected_id) {
        $options = '';
        $found = false;
        foreach ($rows as $row) {
            if ((string)$row['name'] === (string)$selected_id) {
                $found = true;
                break;
            }
        }
        foreach ($rows as $i => $row) {
            $selected = '';
            if ($found && (string)$row['name'] === (string)$selected_id) {
                $selected = 'selected';
            } elseif (!$found && $i === 0) {
                $selected = 'selected';
            }
            $options .= "<option value='" . htmlspecialchars($row['name']) . "' $selected>" . htmlspecialchars($row['name']) . "</option>";
        }
        return $options;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book Copy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-8 px-4">
        <a href="../../viewcopy.php?book_copy_ID=<?php echo urlencode($book_copy_ID); ?>"
            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 inline-block mb-4">Return to Copy Details</a>

        <h2 class="text-2xl font-semibold mb-4">Edit Book Copy</h2>

        <?php if (!empty($message)): ?>
            <div class="bg-<?php echo $message_type === 'error' ? 'red' : ($message_type === 'warning' ? 'yellow' : 'green'); ?>-100 border border-<?php echo $message_type === 'error' ? 'red' : ($message_type === 'warning' ? 'yellow' : 'green'); ?>-400 text-<?php echo $message_type === 'error' ? 'red' : ($message_type === 'warning' ? 'yellow' : 'green'); ?>-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold"><?php echo ucfirst($message_type); ?>!</strong>
                <span class="block sm:inline"><?php echo $message; ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($copy_data)): ?>
        <form method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8">
            <input type="hidden" name="action" value="update">
            <div class="mb-4">
                <h3 class="text-lg font-bold">Book Title: <?php echo htmlspecialchars($copy_data['B_title']); ?></h3>
            </div>
            <table class="table-auto w-full border-collapse border border-gray-300">
                <tbody>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">Book Copy ID</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <?php echo htmlspecialchars($copy_data['book_copy']); ?>
                            <input type="hidden" name="book_copy" value="<?php echo htmlspecialchars($copy_data['book_copy']); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">Copy ID</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" id="copy_ID" name="copy_ID" value="<?php echo htmlspecialchars($copy_data['copy_ID']); ?>" >
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">Note</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" id="note" name="note" value="<?php echo htmlspecialchars($copy_data['note']); ?>" >
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">Call Number</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" id="callNumber" name="callNumber" value="<?php echo htmlspecialchars($copy_data['callNumber']); ?>" >
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">Status</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" id="status" name="status" >
                                <option value="Available" <?php echo $copy_data['status'] == 'Available' ? 'selected' : ''; ?>>Available</option>
                                <option value="Borrowed" <?php echo $copy_data['status'] == 'Borrowed' ? 'selected' : ''; ?>>Borrowed</option>
                                <option value="Reserved" <?php echo $copy_data['status'] == 'Reserved' ? 'selected' : ''; ?>>Reserved</option>
                                <option value="Weed" <?php echo $copy_data['status'] == 'Weed' ? 'selected' : ''; ?>>Weed</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">Vendor</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <select name="vendor" id="vendor" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                            <?php echo renderOptionsById($vendors, $copy_data['vendor']); ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">Funding Source</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <select name="fundingSource" id="fundingSource" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                            <?php echo renderOptionsById($funds, $copy_data['fundingSource']); ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">sublocation</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <select name="sublocation" id="sublocation" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                            <?php echo renderOptionsById($sublocs, $copy_data['sublocation']); ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">Rating</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" id="rating" name="rating" >
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
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="return confirm('Are you sure you want to save the changes to this book copy?');">Save Changes</button>
            </div>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
    // Close the database connection
    $conn->close();
?>