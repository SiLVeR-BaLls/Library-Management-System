<?php
    include '../config.php';

    // Determine User Type
    $userTypes = ['admin', 'student', 'librarian', 'faculty'];
    $userType = null;
    $idno = null;

    foreach ($userTypes as $type) {
        if (!empty($_SESSION[$type]['IDno'])) {
            $userType = $type;
            $idno = $_SESSION[$type]['IDno'];
            break;
        }
    }

    // Define the main DDC ranges for the dropdown
    $mainDDCClasses = [
        'all' => 'Display All Books',
        'other' => 'Books Other',
        '000-099' => 'General Works & Computer Science',
        '100-199' => 'Philosophy & Psychology',
        '200-299' => 'Religion',
        '300-399' => 'Social Sciences',
        '400-499' => 'Language',
        '500-599' => 'Pure Sciences',
        '600-699' => 'Technology (Applied Sciences)',
        '700-799' => 'The Arts',
        '800-899' => 'Literature & Rhetoric',
        '900-999' => 'History & Geography'
    ];

    // Retrieve search parameters
    $searchTerm = $_GET['searchTerm'] ?? '';
    $searchBy = $_GET['searchByOption'] ?? 'all';
    $materialType = $_GET['materialType'] ?? 'all';
    $subType = $_GET['subType'] ?? 'all';
    $ddcMainClass = $_GET['ddcMainClass'] ?? 'all';

    // Build SQL query
    $sql = "SELECT
        b.B_title AS title,
        b.author,
        (SELECT COUNT(bc_inner.book_copy_ID) FROM book_copies bc_inner WHERE bc_inner.book_id = b.book_id) AS total_copies,
        (SELECT COUNT(bc_avail.book_copy_ID) FROM book_copies bc_avail WHERE bc_avail.book_id = b.book_id AND bc_avail.status = 'Available') AS available_copies
    FROM
        book b
    LEFT JOIN
        book_copies bc ON b.book_id = bc.book_id
    LEFT JOIN
        coauthor ca ON b.book_id = ca.book_id
    WHERE 1=1";

    if (!empty($searchTerm)) {
        if ($searchBy === 'title') {
            $sql .= " AND b.B_title LIKE '%$searchTerm%'";
        } elseif ($searchBy === 'author') {
            $sql .= " AND b.author LIKE '%$searchTerm%'";
        } elseif ($searchBy === 'coauthor') {
            $sql .= " AND ca.Co_Name LIKE '%$searchTerm%'";
        } else {
            $sql .= " AND (b.B_title LIKE '%$searchTerm%' OR b.author LIKE '%$searchTerm%' OR ca.Co_Name LIKE '%$searchTerm%')";
        }
    }

    if ($materialType !== 'all') {
        $sql .= " AND b.MT = '$materialType'";
    }

    if ($subType !== 'all') {
        $sql .= " AND b.ST = '$subType'";
    }

    if ($ddcMainClass !== 'all') {
        if ($ddcMainClass === 'other') {
            $sql .= " AND NOT SUBSTR(bc.callNumber, 1, 1) IN ('0','1','2','3','4','5','6','7','8','9')";
        } elseif (strpos($ddcMainClass, '-') !== false) {
            list($start, $end) = explode('-', $ddcMainClass);
            $start = sprintf("%03d", $start);
            $end = sprintf("%03d", $end);
            $sql .= " AND SUBSTR(bc.callNumber, 1, 3) BETWEEN '$start' AND '$end'";
        } else {
            $sql .= " AND SUBSTR(bc.callNumber, 1, 3) LIKE '$ddcMainClass%'";
        }
    }

    $sql .= " GROUP BY b.book_id, b.B_title, b.author ORDER BY b.B_title";

    $result = $conn->query($sql);
    $resultCount = $result ? $result->num_rows : 0;
?>


<div class="flex">
    <div class="sidebar">
        <?php include $sidebars[$userType] ?? ''; ?>
    </div>

    <div class="flex flex-col w-screen">
        <?php include 'include/header.php'; ?>

       <div class="p-3">
        <div class="p-3 rounded-xl shadow-md flex flex-col" style="background-color: <?= $background ?>; color: <?= $text1 ?>;">
            <h2 class="text-xl font-semibold mb-4">Search Results</h2>

            <div class="mb-4 flex flex-row gap-4">
                <p><strong class="block md:inline">Keyword:</strong> <span class="block md:inline"><?= htmlspecialchars($searchTerm ?: 'N/A') ?></span></p>
                <p><strong class="block md:inline">Search By:</strong> <span class="block md:inline"><?= htmlspecialchars(ucfirst($searchBy)) ?></span></p>
                <p><strong class="block md:inline">Material Type:</strong> <span class="block md:inline"><?= htmlspecialchars($materialType !== 'all' ? $materialType : 'All') ?></span></p>
                <p><strong class="block md:inline">Subtype:</strong> <span class="block md:inline"><?= htmlspecialchars($subType !== 'all' ? $subType : 'All') ?></span></p>
                <p><strong class="block md:inline">DDC Main Class:</strong> <span class="block md:inline"><?= htmlspecialchars($ddcMainClass !== 'all' ? ($mainDDCClasses[$ddcMainClass] ?? ucfirst($ddcMainClass)) : 'All') ?></span></p>
            </div>

            <p class="mb-4">Found <strong><?= $resultCount ?></strong> results.</p>

            <?php if ($result && $result->num_rows > 0): ?>
                <table class="w-full border-collapse rounded-lg overflow-hidden">
                    <thead>
                        <tr style="background-color: <?= $sidebar ?>; color: <?= $text1 ?>;">
                            <th class="p-3 text-left md:table-cell hidden">Title</th>
                            <th class="p-3 text-left md:table-cell hidden">Author</th>
                            <th class="p-3 text-center md:table-cell hidden">Total Copies</th>
                            <th class="p-3 text-center md:table-cell hidden">Available Copies</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #ffffff;" >
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="md:table-row flex flex-col md:flex-row border-b border-gray-200 dark:border-gray-700">
                                <td class="p-3 font-semibold md:hidden block"><?= htmlspecialchars($row['title']) ?></td>
                                <td class="p-3 md:table-cell block"><span class="md:hidden font-semibold">Title:</span> <?= htmlspecialchars($row['title']) ?></td>
                                <td class="p-3 md:table-cell block"><span class="md:hidden font-semibold">Author:</span> <?= htmlspecialchars($row['author']) ?></td>
                                <td class="p-3 text-center md:table-cell block"><span class="md:hidden font-semibold">Total:</span> <?= $row['total_copies'] ?></td>
                                <td class="p-3 text-center md:table-cell block"><span class="md:hidden font-semibold">Available:</span> <?= $row['available_copies'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No results found.</p>
            <?php endif; ?>

            <div class="mt-4">
                <a href="search.php" class="text-blue-500 hover:underline">Return to Search</a>
            </div>
        </div>
    </div>

        <footer class="mt-auto">
            <?php include 'include/footer.php'; ?>
        </footer>
    </div>
</div>