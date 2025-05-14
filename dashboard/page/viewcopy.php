<?php
include '../config.php';

// Initialize message variables
$message = "";
$message_type = "";

// Get the copy ID from the URL
$ID = isset($_GET['book_copy_ID']) ? $_GET['book_copy_ID'] : '';

// Use prepared statements to prevent SQL injection
$sql = "SELECT * FROM book_copies WHERE book_copy_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ID);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    $message = "Error retrieving copy details: " . $conn->error;
    $message_type = "danger"; // Bootstrap class for error
} elseif ($result->num_rows == 0) {
    $message = "No copy found with the specified ID.";
    $message_type = "warning"; // Bootstrap class for warning
    $copy_data = [];
} else {
    $copy_data = $result->fetch_assoc();
}

$stmt->close();

// Handle deletion logic here if confirmed
if (isset($_GET['confirm_delete']) && $_GET['confirm_delete'] === 'true' && $ID) {
    $delete_sql = "DELETE FROM book_copies WHERE book_copy_ID = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("s", $ID);

    if ($delete_stmt->execute()) {
        $message = "Copy deleted successfully.";
        $message_type = "success";
        // After successful deletion, redirect to BookList.php
        header("Location: BookList.php?title=" . urlencode($copy_data['book_id']) . "&book_copy_ID=" . urlencode($copy_data['book_copy_ID']));
        exit;
    } else {
        $message = "Error deleting copy: " . $conn->error;
        $message_type = "danger";
    }

    $delete_stmt->close();
}
?>
<div class="flex ">
    <?php if (!empty($userType) && isset($sidebars[$userType]) && file_exists($sidebars[$userType])) {
        include $sidebars[$userType];
    } ?><div class="flex-grow ">
        <?php include 'include/header.php'; ?>

        <div class="flex-grow">
            <div class="p-3">
                <a href="BookList.php?title=<?php echo urlencode($copy_data['book_id']); ?>&book_copy_ID=<?php echo urlencode($copy_data['book_copy_ID']); ?>"
                    class="bg-gray-500 text-white py-2 px-6 rounded-md hover:bg-gray-600 inline-block">
                    Return to List
                </a>
                <h2 class="text-3xl font-bold text-center">Book Copy Details</h2>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $message_type; ?> p-4 rounded-md mb-6">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($copy_data)): ?>
                    <div class="book-page p-6 bg-yellow-50 border-4 border-gray-200 rounded-xl ">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div class="flex">
                                    <div class="font-semibold text-lg w-32">ID:</div>
                                    <div class="text-gray-700 flex-grow">
                                        <?php echo htmlspecialchars($copy_data['book_copy']); ?>
                                    </div>
                                </div>

                                <div class="flex">
                                    <div class="font-semibold text-lg w-32">Title:</div>
                                    <div class="text-gray-700 flex-grow">
                                        <?php echo htmlspecialchars($copy_data['B_title']); ?>
                                    </div>
                                </div>

                                <div class="flex">
                                    <div class="font-semibold text-lg w-32">Copy ID:</div>
                                    <div class="text-gray-700 flex-grow">
                                        <?php echo htmlspecialchars($copy_data['copy_ID']); ?>
                                    </div>
                                </div>

                                <div class="flex">
                                    <div class="font-semibold text-lg w-32">Call Number:</div>
                                    <div class="text-gray-700 flex-grow">
                                        <?php echo htmlspecialchars($copy_data['callNumber']); ?>
                                    </div>
                                </div>

                                <div class="flex">
                                    <div class="font-semibold text-lg w-32">Status:</div>
                                    <div class="text-gray-700 flex-grow">
                                        <?php echo htmlspecialchars($copy_data['status']); ?>
                                    </div>
                                </div>

                                <div class="flex">
                                    <div class="font-semibold text-lg w-32">Note:</div>
                                    <div class="text-gray-700 flex-grow">
                                        <?php echo htmlspecialchars($copy_data['note']); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div class="flex">
                                    <div class="font-semibold text-lg w-32">Vendor:</div>
                                    <div class="text-gray-700 flex-grow">
                                        <?php echo htmlspecialchars($copy_data['vendor']); ?>
                                    </div>
                                </div>

                                <div class="flex">
                                    <div class="font-semibold text-lg w-32">Funding Source:</div>
                                    <div class="text-gray-700 flex-grow">
                                        <?php echo htmlspecialchars($copy_data['fundingSource']); ?>
                                    </div>
                                </div>
                                <div class="flex justify-center mt-6 hidden">
                                    <div id="qrcode" class="p-4 bg-white border rounded-md shadow-md"></div>
                                </div>

                                <div class="flex">
                                    <div class="font-semibold text-lg w-32">sublocation:</div>
                                    <div class="text-gray-700 flex-grow">
                                        <?php echo htmlspecialchars($copy_data['sublocation']); ?>
                                    </div>
                                </div>

                                <div class="flex">
                                    <div class="font-semibold text-lg w-32">Rating:</div>
                                    <div class="text-gray-700 flex-grow">
                                        <?php
                                        $rating = (int) $copy_data['rating'];
                                        $maxStars = 5;
                                        for ($i = 1; $i <= $maxStars; $i++) {
                                            if ($i <= $rating) {
                                                $starColor = match ($rating) {
                                                    5 => 'text-green-500',
                                                    4 => 'text-blue-500',
                                                    3 => 'text-yellow-400',
                                                    2 => 'text-orange-500',
                                                    1 => 'text-red-500',
                                                    default => 'text-gray-400',
                                                };
                                                echo "<span class='{$starColor} text-3xl'>&#9733;</span>";
                                            } else {
                                                echo '<span class="text-gray-400 text-3xl">&#9734;</span>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>

                                <?php
                        // Check if the user is an admin or librarian
                        if ($userType === 'admin' || $userType === 'librarian') {
                        ?>
                            <div class="mt-8 flex justify-end gap-4">
                                <a href="include/edit_copy.php?book_copy_ID=<?php echo urlencode($copy_data['book_copy_ID']); ?>"
                                    class="bg-yellow-500 text-white py-2 px-4 rounded-md hover:bg-yellow-600">
                                    <i class="fa fa-pencil"></i> <span class="hidden md:inline"></span>
                                </a>
                                <button onclick="confirmDelete()"
                                    class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600">
                                    <i class="fa fa-trash-o"></i> <span class="hidden md:inline"></span>
                                </button>
                            </div>
                            <div class="flex justify-center gap-4 mb-6">
                            <?php
                        }
                    ?>
                            </div>
                        </div>
                    </div>
                   
                        </div>

            </div>

        <?php endif; ?>

        <footer>
            <?php include 'include/footer.php'; ?>
        </footer>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        if (window.confirm("Are you sure you want to delete this book copy?")) {
            window.location.href = "?book_copy_ID=<?php echo urlencode($copy_data['book_copy_ID']); ?>&confirm_delete=true";
        }
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script>
    $(document).ready(function() {
        var bookCopyID = "<?php echo htmlspecialchars($copy_data['book_copy']); ?>";

        if (bookCopyID) {
            $("#qrcode").qrcode({
                text: bookCopyID,
                width: 250,
                height: 250
            });
        }
    });
</script>