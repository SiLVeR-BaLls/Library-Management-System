<?php
include('../dashboard/config.php');

// Check if the user is logged in and is either admin, student, or librarian
if (!isset($_SESSION['admin']) && !isset($_SESSION['student']) && !isset($_SESSION['librarian'])) {
    header('Location: ../../Registration/log_in.php'); // Redirect to the login page if not logged in
    exit();
}

// Fetch data for the logged-in user (admin, student, or librarian)
if (isset($_SESSION['admin'])) {
    $userType = 'admin';
    $userID = $_SESSION['admin']['IDno']; // Get IDno from session based on admin
} elseif (isset($_SESSION['student'])) {
    $userType = 'student';
    $userID = $_SESSION['student']['IDno']; // Get IDno from session based on student
} elseif (isset($_SESSION['librarian'])) {
    $userType = 'librarian';
    $userID = $_SESSION['librarian']['IDno']; // Get IDno from session based on librarian
} else 

// Check if user is logged in
if (!$userID) {
    header("Location: ../../Registration/log_in.php"); // Redirect to login page if not logged in
    exit();
}

// Get the selected user to chat with
$selected_user_id = isset($_GET['IDno']) ? $_GET['IDno'] : null;

// Handle sending a message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message']) && $selected_user_id) {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        // Insert message into the messages table
        $query = "INSERT INTO messages (from_user_id, to_user_id, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss', $userID, $selected_user_id, $message);
        $stmt->execute();
        // Redirect to the same page to refresh the messages
        header("Location: index.php?IDno=" . $selected_user_id);
        exit();
    }
}

// Fetch messages between the logged-in user and selected user, order by latest first
$messages = [];
if ($selected_user_id) {
    $query = "SELECT * FROM messages 
              WHERE (from_user_id = ? AND to_user_id = ?) 
                 OR (from_user_id = ? AND to_user_id = ?) 
              ORDER BY timestamp DESC"; // Order by latest message first
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssss', $userID, $selected_user_id, $selected_user_id, $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch selected user details (for displaying in chat)
$selectedUser = [];
if ($selected_user_id) {
    $query = "SELECT Fname, Sname FROM users_info WHERE IDno = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $selected_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $selectedUser = $result->fetch_assoc();
}

// Fetch all users for the sidebar (only those with messages exchanged)
$searchResults = [];
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = $_GET['search'];
    $searchStmt = $conn->prepare("SELECT IDno, Fname, Sname FROM users_info WHERE Fname LIKE ? OR Sname LIKE ?");
    $searchTerm = '%' . $search_query . '%';
    $searchStmt->bind_param('ss', $searchTerm, $searchTerm);
    $searchStmt->execute();
    $searchResult = $searchStmt->get_result();
    $searchResults = $searchResult->fetch_all(MYSQLI_ASSOC);
}

// Fetch the conversation partners (users with actual messages exchanged) and the latest message
$conversationPartners = [];
$conversationStmt = $conn->prepare("
    SELECT DISTINCT u.IDno, u.Fname, u.Sname, m.message, m.timestamp, m.is_read
    FROM users_info u
    JOIN messages m ON (m.from_user_id = u.IDno OR m.to_user_id = u.IDno)
    WHERE (m.from_user_id = ? OR m.to_user_id = ?)
    AND u.IDno != ?
    ORDER BY m.timestamp DESC
");
$conversationStmt->bind_param('sss', $userID, $userID, $userID);
$conversationStmt->execute();
$conversationResult = $conversationStmt->get_result();

// Store the results in an associative array grouped by user ID
$conversationPartners = [];
while ($row = $conversationResult->fetch_assoc()) {
    if (!isset($conversationPartners[$row['IDno']])) {
        $conversationPartners[$row['IDno']] = $row;
    }
}

// Fetch the logged-in user's first and last name
$userDetails = [];
$query = "SELECT Fname, Sname FROM users_info WHERE IDno = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $userID);
$stmt->execute();
$result = $stmt->get_result();
$userDetails = $result->fetch_assoc();

// Mark messages as read when chat is opened
$updateQuery = "UPDATE messages SET is_read = 1 WHERE to_user_id = ? AND from_user_id = ? AND is_read = 0";
$updateStmt = $conn->prepare($updateQuery);
$updateStmt->bind_param('ss', $userID, $selected_user_id);
$updateStmt->execute();

if (isset($_GET['IDno'])) {
    $IDno = $_GET['IDno'];  // Assuming 'IDno' is the unique message or conversation identifier

    // Prepare the query to update 'is_read' status
    $stmt = $conn->prepare("UPDATE messages SET is_read = 1 WHERE message_id = ?");
    $stmt->bind_param("i", $IDno);  // Bind the message ID (or conversation ID)
    $stmt->execute();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen flex">

<div class="flex-1 flex overflow-hidden">

<!-- Sidebar -->
<div class="w-1/4 bg-white p-4 shadow-md h-full overflow-y-auto">
    <a href="../" class="text-red-500">Leave</a>
    <h2 class="text-xl font-semibold">
        Logged in as: 
        <span class="text-blue-500"><?php echo htmlspecialchars($userDetails['Fname']) . ' ' . htmlspecialchars($userDetails['Sname']); ?></span>
    </h2>

    <!-- Search input -->
    <form id="search-form" action="index.php" method="GET" class="mt-4">
        <input type="text" name="search" placeholder="Search users..." class="px-4 py-2 border rounded w-full" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    </form>

    <!-- Display search results -->
    <?php if (!empty($searchResults)): ?>
        <ul class="mt-4">
            <?php foreach ($searchResults as $user): ?>
                <li class="py-2 border-b">
                    <a href="index.php?IDno=<?php echo $user['IDno']; ?>" class="text-blue-500">
                        <?php echo htmlspecialchars($user['Fname']) . ' ' . htmlspecialchars($user['Sname']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php elseif (isset($_GET['search']) && empty($searchResults)): ?>
        <div class="text-red-500 mt-2">No users found with that name.</div>
    <?php endif; ?>

<!-- Display conversation partners -->
<div class="mt-6">
    <h3 class="font-semibold text-lg">Your Conversations</h3>
    <ul class="space-y-4 mt-4">
        <?php foreach ($conversationPartners as $partner): ?>
            <li class="py-2 border-b">
                <a href="index.php?IDno=<?php echo $partner['IDno']; ?>" class="flex items-center justify-between py-2 px-4 rounded hover:bg-gray-100">
                    
                    <!-- Bold the name and message if the message is unread -->
                    <span class="<?php echo $partner['is_read'] ? '' : 'font-bold'; ?>">
                        <?php echo htmlspecialchars($partner['Fname']) . ' ' . htmlspecialchars($partner['Sname']); ?>
                    </span>
                    
                    <div class="text-sm flex items-center space-x-2 text-gray-500">
                        <!-- Display the latest message, bold if unread -->
                        <span class="<?php echo $partner['is_read'] ? '' : 'font-bold'; ?> truncate max-w-xs">
                            <?php echo htmlspecialchars($partner['message']); ?>
                        </span>
                        
                        <!-- Display timestamp -->
                        <span class="text-xs"><?php echo date('g:i A', strtotime($partner['timestamp'])); ?></span>
                    </div>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

</div>


<!-- Chat area -->
<div class="flex-1 bg-white p-4 flex flex-col">
    <?php if ($selected_user_id): ?>
        <div class="mb-6 overflow-y-auto space-y-4 mt-4 max-h-[500px]" id="content">
            <h2 class="text-2xl">Chat with <?php echo htmlspecialchars($selectedUser['Fname']); ?></h2>
            
            <!-- Message container with scrolling enabled and fixed height -->
            <div class="flex-1 overflow-y-auto space-y-4 mt-4 max-h-[300px]" id="messageContainer">
                <?php
                // Fetch and display the latest messages first
                $messagesLimit = 10; // Set a limit for how many messages to show initially
                $latestMessages = array_slice(array_reverse($messages), 0, $messagesLimit);
                ?>

                <?php foreach ($latestMessages as $message): ?>
                    <div class="flex <?php echo $message['from_user_id'] === $userID ? 'justify-end' : 'justify-start'; ?>">
                        <div class="bg-gray-200 p-3 rounded-lg max-w-xs">
                            <p><?php echo htmlspecialchars($message['message']); ?></p>
                            <div class="text-xs text-gray-500 mt-2">
                                <?php echo date('g:i A', strtotime($message['timestamp'])); ?>
                            </div>

                            <!-- Read/Unread Status -->
                            <?php if ($message['is_read'] == 1): ?>
                                <span class="text-green-500 text-xs">Read</span>
                            <?php else: ?>
                                <span class="text-gray-500 text-xs">Unread</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Message input form with sticky positioning -->
        <form method="POST" class="flex space-x-2 mt-4">
            <input type="text" name="message" placeholder="Type a message..." class="flex-1 p-2 border rounded" required>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded">Send</button>
        </form>

    <?php else: ?>
        <p class="text-center text-xl">Select a user to start chatting.</p>
    <?php endif; ?>
</div>

<script>
    // Function to scroll the content to the bottom
    function scrollToBottom() {
        const content = document.getElementById('content');
        content.scrollTop = content.scrollHeight;
    }

    // Call this function to make sure the scroll starts at the bottom
    window.onload = scrollToBottom;
</script>

</div>
</body>
</html>
