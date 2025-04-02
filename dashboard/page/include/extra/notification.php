<?php
    // Database connection
    $mysqli = new mysqli('localhost', 'root', '', 'lms');

    // Check for connection errors
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Initialize notifications array
    $notifications = [];

    // Pending user count notification (admin/librarian only)
    if ($userType === 'admin' || $userType === 'librarian') {
        $queryPending = "SELECT COUNT(*) as pending_count FROM users_info WHERE status_log = 'pending'";
        $resultPending = $mysqli->query($queryPending);

        if ($resultPending && $resultPending->num_rows > 0) {
            $rowPending = $resultPending->fetch_assoc();
            $pendingCount = $rowPending['pending_count'];

            if ($pendingCount > 0) {
                $notifications[] = [
                    'message' => "You have " . $pendingCount . " pending user(s) for approval.",
                    'link' => "pending.php"
                ];
            }
        }
    }

    $mysqli->close();

    // Encode notifications array for passing to JS
    $notificationsJson = json_encode($notifications);
?>
<script src="https://kit.fontawesome.com/6b23de7647.js" crossorigin="anonymous"></script>

<script>
    document.getElementById('bell-icon').addEventListener('click', function() {
        document.getElementById('notification-dropdown').classList.toggle('hidden');
    });
</script>



<script>
    document.getElementById('bell-icon').addEventListener('click', function() {
        document.getElementById('notification-dropdown').classList.toggle('hidden');
    });

    function updateNotifications() {
        fetch('get_notifications.php') // Create get_notifications.php
            .then(response => response.json())
            .then(data => {
                const notificationCountDisplay = document.getElementById('notification-count-display');
                const notificationList = document.getElementById('notification-list');

                // Update notification count
                notificationCountDisplay.textContent = data.length;

                // Update notification list
                notificationList.innerHTML = ''; // Clear existing list
                data.forEach(note => {
                    const li = document.createElement('li');
                    li.className = 'px-4 py-2 hover:bg-gray-100';
                    li.innerHTML = `<a href="${note.link}" class="text-gray-800 hover:text-blue-600">${note.message}</a>`;
                    notificationList.appendChild(li);
                });
            });
    }

    // Update notifications every 5 seconds (adjust as needed)
    setInterval(updateNotifications, 5000);

    // Initial update
    updateNotifications();
</script>