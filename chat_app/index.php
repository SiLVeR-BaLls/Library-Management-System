<?php
include('../dashboard/config.php');

// Check if the user is logged in and is either admin, student, or librarian
if (!isset($_SESSION['admin']) && !isset($_SESSION['student']) && !isset($_SESSION['librarian'])) {
    header('Location: ../../Registration/log_in.php');
    exit();
}

// Fetch data for the logged-in user (admin, student, or librarian)
if (isset($_SESSION['admin'])) {
    $userType = 'admin';
    $userID = $_SESSION['admin']['IDno'];
} elseif (isset($_SESSION['student'])) {
    $userType = 'student';
    $userID = $_SESSION['student']['IDno'];
} elseif (isset($_SESSION['librarian'])) {
    $userType = 'librarian';
    $userID = $_SESSION['librarian']['IDno'];
} else {
    header("Location: ../../Registration/log_in.php");
    exit();
}

// Get the selected user to chat with
$selected_user_id = isset($_GET['IDno']) ? $_GET['IDno'] : null;

// Handle sending a message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message']) && $selected_user_id) {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $query = "INSERT INTO messages (from_user_id, to_user_id, message, timestamp) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss', $userID, $selected_user_id, $message);
        $stmt->execute();
        header("Location: index.php?IDno=" . $selected_user_id);
        exit();
    }
}

// Handle update is_read status
if (isset($_POST['update_read']) && isset($_POST['message_id'])) {
    $messageId = $_POST['message_id'];
    $updateQuery = "UPDATE messages SET is_read = 1 WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('i', $messageId);
    $updateStmt->execute();
    exit();
}

// Fetch messages between the logged-in user and selected user, order by oldest first
$messages = [];
if ($selected_user_id) {
    $query = "SELECT * FROM messages 
              WHERE (from_user_id = ? AND to_user_id = ?) 
                 OR (from_user_id = ? AND to_user_id = ?) 
              ORDER BY timestamp desc"; // Order by oldest first
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
if ($selected_user_id) {
    $updateQuery = "UPDATE messages SET is_read = 1 WHERE to_user_id = ? AND from_user_id = ? AND is_read = 0";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('ss', $userID, $selected_user_id);
    $updateStmt->execute();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Application</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <style>
    .scrollbar-hidden::-webkit-scrollbar { display: none; }
    .scrollbar-hidden { -ms-overflow-style: none; scrollbar-width: none; }
    .message-container { max-height: calc(100vh - 200px); overflow-y: auto; }
    .message-input-container { display: flex; align-items: center; }
    .message-input { flex-grow: 1; height: 50px; padding: 10px; border: 1px solid #e2e8f0; border-radius: 5px; margin-right: 10px; }
    .send-button { background-color: #3b82f6; color: white; padding: 10px 15px; border-radius: 5px; cursor: pointer; }
    body { font-size: 16px; }
    h2 { font-size: 1.25rem; }
    p, span, li, input, button { font-size: 1rem; }
    body.dark-mode { background-color: #1a202c; color: #e2e8f0; }
    body.dark-mode .bg-white { background-color: #2d3748; }
    body.dark-mode .bg-gray-100 { background-color: #4a5568; }
    body.dark-mode .text-gray-800 { color: #cbd5e0; }
    body.dark-mode .text-gray-700 { color: #a0aec0; }
    body.dark-mode .text-gray-500 { color: #718096; }
    body.dark-mode .border-gray-300 { border-color: #4a5568; }
    body.dark-mode input, body.dark-mode textarea { background-color: #2d3748; color: #e2e8f0; }
    @media (max-width: 768px) {
    .flex.w-full.h-full {
        flex-direction: column;
    }

    aside.w-1 {
        width: 100%;
        height: auto;
    }

    main.flex-1 {
        width: 100%;
        height: 100vh; /* Or another valid height calculation */
    }

    .message-container {
        max-height: calc(100vh - 200px);
    }

    .message-input-container {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        width: 100%;
    }
}

</style>
</head>
<body class="bg-gray-100 h-screen flex">

<div class="flex w-full h-full">

    <aside class="w-1/4 bg-white shadow-md p-4 flex flex-col space-y-3 overflow-y-auto scrollbar-hidden">

        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Chats</h2>
            <div class="flex items-center space-x-2">
                <button id="darkModeToggle" class="text-gray-500 hover:text-gray-700"><i class="fas fa-moon"></i></button>
                <a href="../" class="text-sm text-red-500 hover:text-red-700"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>

        <form id="search-form" action="index.php" method="GET">
            <div class="relative">
                <input type="text" name="search" placeholder="Search..."
                       class="px-3 py-2 pl-8 rounded-md border border-gray-300 w-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                    <i class="fas fa-search text-gray-400 text-xs"></i>
                </div>
            </div>
        </form>

        <?php if (!empty($searchResults)): ?>
            <ul class="space-y-2 mt-3">
                <?php foreach ($searchResults as $user): ?>
                    <li class="hover:bg-gray-100 rounded-md p-3 cursor-pointer">
                        <a href="index.php?IDno=<?php echo $user['IDno']; ?>" class="flex items-center">
                            <span class="text-gray-700"><?php echo htmlspecialchars($user['Fname']) . ' ' . htmlspecialchars($user['Sname']); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php elseif (isset($_GET['search']) && empty($searchResults)): ?>
            <p class="text-red-500 mt-3">No users found.</p>
        <?php endif; ?>

        <div class="mt-3">
            <h3 class="font-semibold text-gray-800">Your Chats</h3>
            <ul class="space-y-2 mt-2">
                <?php foreach ($conversationPartners as $partner): ?>
                    <li class="p-3 rounded-md hover:bg-gray-100">
                        <a href="index.php?IDno=<?php echo $partner['IDno']; ?>" class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="<?php echo $partner['is_read'] ? '' : 'font-semibold'; ?> text-gray-700">
                                    <?php echo htmlspecialchars($partner['Fname']) . ' ' . htmlspecialchars($partner['Sname']); ?>
                                </span>
                            </div>
                            <div class="text-sm text-gray-500">
                                <span class="truncate max-w-xs"><?php echo htmlspecialchars($partner['message']); ?></span>
                                <span class="ml-1"><?php echo date('g:i A', strtotime($partner['timestamp'])); ?></span>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </aside>

    <main class="flex-1 bg-white shadow-md p-5 flex flex-col">
        <?php if ($selected_user_id): ?>
            <div class="flex flex-col flex-grow message-container scrollbar-hidden" id="messageDisplayArea">
                <?php foreach (array_reverse($messages) as $message): ?>
                    <div class="flex <?php echo $message['from_user_id'] === $userID ? 'justify-end' : 'justify-start'; ?>">
                        <div class="bg-gray-100 p-3 rounded-md max-w-2xl">
                            <p class="text-gray-800"><?php echo htmlspecialchars($message['message']); ?></p>
                            <div class="flex items-center justify-between mt-2 text-sm text-gray-500">
                                <span><?php echo date('g:i A', strtotime($message['timestamp'])); ?></span>
                                <span class="<?php echo $message['is_read'] == 1 ? 'text-green-500' : 'text-gray-500'; ?>">
                                    <?php echo $message['is_read'] == 1 ? 'Read' : 'Unread'; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="message-input-container">
                <input type="text" id="messageInput" placeholder="Type a message..." class="message-input">
                <button id="sendMessageButton" class="send-button">Send</button>
            </div>

        <?php else: ?>
            <p class="text-center text-gray-500">Select a user to chat.</p>
        <?php endif; ?>
    </main>

</div>

<script>
    Pusher.logToConsole = true;

    var pusher = new Pusher('YOUR_PUSHER_KEY', {
        cluster: 'YOUR_CLUSTER'
    });

    var channel = pusher.subscribe('chat-room-<?php echo $selected_user_id; ?>');

    function scrollToBottom() {
        const messageDisplayArea = document.getElementById('messageDisplayArea');
        messageDisplayArea.scrollTop = messageDisplayArea.scrollHeight;
    }

    window.onload = scrollToBottom;

    channel.bind('new-message', function(data) {
        const messageContainer = document.getElementById('messageDisplayArea');
        const newMessage = data.message;

        const messageDiv = document.createElement('div');
        messageDiv.classList.add('flex', newMessage.from_user_id === '<?php echo $userID; ?>' ? 'justify-end' : 'justify-start');
        messageDiv.innerHTML = `
            <div class="bg-gray-100 p-3 rounded-md max-w-2xl">
                <p class="text-gray-800">${newMessage.message}</p>
                <div class="flex items-center justify-between mt-2 text-sm text-gray-500">
                    <span>${newMessage.timestamp}</span>
                    <span class="text-gray-500">${newMessage.is_read == 1 ? 'Read' : 'Unread'}</span>
                </div>
            </div>
        `;
        messageContainer.appendChild(messageDiv);

        scrollToBottom();

        // Update read status for the new message
        if (newMessage.to_user_id === '<?php echo $userID; ?>') {
            fetch('index.php?IDno=<?php echo $selected_user_id; ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'update_read=true&message_id=' + encodeURIComponent(newMessage.id)
            });
        }
    });

    const sendMessageButton = document.getElementById('sendMessageButton');
    const messageInput = document.getElementById('messageInput');
    const messageDisplayArea = document.getElementById('messageDisplayArea');

    sendMessageButton.addEventListener('click', function() {
        const message = messageInput.value.trim();
        if (message && '<?php echo $selected_user_id; ?>') {
            fetch('index.php?IDno=<?php echo $selected_user_id; ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'message=' + encodeURIComponent(message)
            })
            .then(() => {
                // Add the message to the display immediately
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('flex', 'justify-end'); // Your own message
                messageDiv.innerHTML = `
                    <div class="bg-gray-100 p-3 rounded-md max-w-2xl">
                        <p class="text-gray-800">${message}</p>
                        <div class="flex items-center justify-between mt-2 text-sm text-gray-500">
                            <span>Now</span>
                            <span class="text-gray-500">Unread</span>
                        </div>
                    </div>
                `;
                messageDisplayArea.appendChild(messageDiv);
                messageInput.value = '';
                scrollToBottom();
            })
            .then(() => {
              // Fetch latest messages after sending
              fetchMessages();
            });
        }
    });

    messageInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            sendMessageButton.click();
        }
    });

    const darkModeToggle = document.getElementById('darkModeToggle');

    if (localStorage.getItem('darkMode') === 'enabled') {
        document.body.classList.add('dark-mode');
        darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
    }

    darkModeToggle.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        if (document.body.classList.contains('dark-mode')) {
            darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            localStorage.setItem('darkMode', 'enabled');
        } else {
            darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            localStorage.setItem('darkMode', 'disabled');
        }
    });

    function fetchMessages(userId, lastTimestamp) {
    fetch('your_php_file.php?userId=' + userId + (lastTimestamp ? '&lastTimestamp=' + lastTimestamp : ''))
        .then(response => response.json())
        .then(data => {
            const messageContainer = document.getElementById('messageDisplayArea');
            if (data && Array.isArray(data)) {
                data.forEach(message => {
                    const messageDiv = document.createElement('div');
                    messageDiv.classList.add('message', 'p-2', 'rounded', 'mb-2');
                    messageDiv.classList.add(message.sender === 'right' ? 'bg-blue-100' : 'bg-gray-100');
                    messageDiv.dataset.timestamp = message.timestamp;
                    messageDiv.textContent = message.message;
                    messageContainer.appendChild(messageDiv);
                });
            } else if (data && data.message) {
              messageContainer.innerHTML = `<p class="text-gray-500 text-sm">${data.message}</p>`;
            } else if (data && data.error){
                console.error(data.error);
            }
            scrollToBottom();
        });
}
    // Call fetchMessages initially to load messages
    fetchMessages();

    // Refresh messages every few seconds
    setInterval(fetchMessages, 5000); // Fetch every 5 seconds
</script>
</body>
</html>