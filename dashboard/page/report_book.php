<?php
// report_book.php
include '../config.php';

// Initialize variables
$message = '';
$message_type = '';
$borrowCounts = [];
$bookCopiesCounts = [];
$bookCounts = []; // To store the count of unique books
$totalCopies = 0;
$totalBooks = 0;
$totalDay = 0;
$totalWeek = 0;
$totalMonth = 0;
$totalYear = 0;
$otherRangeData = null; // Store data for the "Other" range

// Fetch all book copies and group them by DDC/Local Range
$sqlBookCopies = "SELECT callNumber, book_id FROM book_copies";
$resultBookCopies = $conn->query($sqlBookCopies);

$allBookCopiesByRange = [];
if ($resultBookCopies) {
    while ($row = $resultBookCopies->fetch_assoc()) {
        $callNumber = $row['callNumber'];
        $bookId = $row['book_id'];
        $range = 'Other';

        if (preg_match('/^[0-9]{3}/', $callNumber)) {
            $rangeStart = intval(substr($callNumber, 0, 3));
            $rangeEnd = floor($rangeStart / 100) * 100 + 99;
            $range = sprintf("%03d", floor($rangeStart / 100) * 100) . "-" . sprintf("%03d", $rangeEnd);
        }

        if (!isset($allBookCopiesByRange[$range])) {
            $allBookCopiesByRange[$range] = [];
        }
        $allBookCopiesByRange[$range][] = $bookId;
    }
}

// Fetch borrow counts by DDC/Local Range
$sqlBorrowData = "SELECT bc.callNumber, bb.borrow_date, bc.book_id FROM book_copies bc LEFT JOIN borrow_book bb ON bc.book_copy = bb.book_copy";
$resultBorrowData = $conn->query($sqlBorrowData);

if ($resultBorrowData) {
    while ($row = $resultBorrowData->fetch_assoc()) {
        $callNumber = $row['callNumber'];
        $borrowDate = $row['borrow_date'];
        $bookId = $row['book_id'];
        $range = 'Other';

        if (preg_match('/^[0-9]{3}/', $callNumber)) {
            $rangeStart = intval(substr($callNumber, 0, 3));
            $rangeEnd = floor($rangeStart / 100) * 100 + 99;
            $range = sprintf("%03d", floor($rangeStart / 100) * 100) . "-" . sprintf("%03d", $rangeEnd);
        }

        if ($range == 'Other') {
            if (!isset($otherRangeData)) {
                $otherRangeData = ['range' => $range, 'borrowCounts' => ['day' => 0, 'week' => 0, 'month' => 0, 'year' => 0], 'bookCounts' => []];
            }
            $otherRangeData['bookCounts'][$bookId] = true;
            if ($borrowDate) {
                if (date('Y-m-d', strtotime($borrowDate)) == date('Y-m-d')) $otherRangeData['borrowCounts']['day']++;
                if (date('W Y', strtotime($borrowDate)) == date('W Y')) $otherRangeData['borrowCounts']['week']++;
                if (date('m Y', strtotime($borrowDate)) == date('m Y')) $otherRangeData['borrowCounts']['month']++;
                if (date('Y', strtotime($borrowDate)) == date('Y')) $otherRangeData['borrowCounts']['year']++;

                $totalDay += (date('Y-m-d', strtotime($borrowDate)) == date('Y-m-d'));
                $totalWeek += (date('W Y', strtotime($borrowDate)) == date('W Y'));
                $totalMonth += (date('m Y', strtotime($borrowDate)) == date('m Y'));
                $totalYear += (date('Y', strtotime($borrowDate)) == date('Y'));
            }
        } else {
            if (!isset($borrowCounts[$range])) {
                $borrowCounts[$range] = ['day' => 0, 'week' => 0, 'month' => 0, 'year' => 0];
                $bookCounts[$range] = [];
            }
            $bookCounts[$range][$bookId] = true;
            if ($borrowDate) {
                if (date('Y-m-d', strtotime($borrowDate)) == date('Y-m-d')) $borrowCounts[$range]['day']++;
                if (date('W Y', strtotime($borrowDate)) == date('W Y')) $borrowCounts[$range]['week']++;
                if (date('m Y', strtotime($borrowDate)) == date('m Y')) $borrowCounts[$range]['month']++;
                if (date('Y', strtotime($borrowDate)) == date('Y')) $borrowCounts[$range]['year']++;

                $totalDay += (date('Y-m-d', strtotime($borrowDate)) == date('Y-m-d'));
                $totalWeek += (date('W Y', strtotime($borrowDate)) == date('W Y'));
                $totalMonth += (date('m Y', strtotime($borrowDate)) == date('m Y'));
                $totalYear += (date('Y', strtotime($borrowDate)) == date('Y'));
            }
        }
    }
} else {
    $message = "Error fetching borrow data: " . $conn->error;
    $message_type = "danger";
}

// Calculate total unique books and total copies
foreach ($bookCounts as $rangeBooks) {
    $totalBooks += count($rangeBooks);
}
if ($otherRangeData) {
    $totalBooks += count($otherRangeData['bookCounts']);
}
foreach ($allBookCopiesByRange as $rangeCopies) {
    $totalCopies += count($rangeCopies);
}

// Ensure $currentPage is set to the current file name
$currentPage = basename($_SERVER['PHP_SELF']);

?>

<div class="flex">
     <?php if (!empty($userType) && isset($sidebars[$userType]) && file_exists($sidebars[$userType])) {
    include $sidebars[$userType]; 
}?><div class="flex flex-col w-screen">
        <?php include 'include/header.php'; ?>

        <div class="">
            <div class="w-full h-auto mx-auto"> <div class="w-full h-16 flex sticky top-0 justify-evenly gap-4 p-2" style="background: <?= $sidebar ?>; color: <?= $text1 ?>;"> <div id="returnedSection" class="w-auto">
                        <a href="report_book.php">
                            <button class="w-full text-white p-2 rounded btn transition text-sm" style="<?= ($currentPage == 'report_book.php') ? "background-color: $button_active;" : '' ?>">
                                Statistical Reports
                            </button>
                        </a>
                    </div>

                    <div id="returnedSection" class="w-auto">
                        <a href="Report_return.php">
                            <button class="w-full text-white p-2 rounded btn transition text-sm" style="<?= ($currentPage == 'Report_return.php') ? "background-color: $button_active;" : '' ?>">
                                Returned Reports
                            </button>
                        </a>
                    </div>

                    <div id="returnedSection" class="w-auto">
                        <a href="Report_book.php">
                            <button class="w-full text-white p-2 rounded btn transition text-sm" style="<?= ($currentPage == 'Report_book.php') ? "background-color: $button_active;" : '' ?>">
                                Reports in Book
                            </button>
                        </a>
                    </div>

                    <div id="ratingSection" class="w-auto">
                        <a href="report_borrow.php">
                            <button class="w-full text-white p-2 rounded btn transition text-sm" style="<?= ($currentPage == 'report_borrow.php') ? "background-color: $button_active;" : '' ?>">
                                Borrowed Reports
                            </button>
                        </a>
                    </div>

                    <div id="borrowedSection" class="w-auto">
                        <a href="report_rating.php">
                            <button class="w-full text-white p-2 rounded btn transition text-sm" style="<?= ($currentPage == 'report_rating.php') ? "background-color: $button_active;" : '' ?>">
                                Rating Reports
                            </button>
                        </a>
                    </div>

                    <div id="borrowedSection" class="w-auto">
                        <a href="Report_book_count.php">
                            <button class="w-full btn text-white p-2 rounded transition text-sm" style="<?= ($currentPage == 'Report_book_count.php') ? "background-color: $button_active;" : '' ?>">
                                Count Reports
                            </button>
                        </a>
                    </div>
                </div>

                <div class="container mx-auto px-4 py-6 ">
                    <?php if ($message): ?>
                        <div class="alert alert-<?php echo $message_type; ?>">
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($borrowCounts) || $otherRangeData): ?>
                        <div class="">
                            <h3 class="text-xl font-bold mb-4">Borrow Counts by DDC/Local Range</h3>
                            <table class="w-full border-collapse border border-gray-300">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="border border-gray-300 p-2">DDC/Local Range</th>
                                        <th class="border border-gray-300 p-2">Total Books</th>
                                        <th class="border border-gray-300 p-2">Total Copies</th>
                                        <th class="border border-gray-300 p-2">Day</th>
                                        <th class="border border-gray-300 p-2">Week</th>
                                        <th class="border border-gray-300 p-2">Month</th>
                                        <th class="border border-gray-300 p-2">Year</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($borrowCounts as $range => $data): ?>
                                        <tr>
                                            <td class="border border-gray-300 p-2">
                                                <a href="sub_range_filter.php?range=<?php echo urlencode($range); ?>">
                                                    <?php echo htmlspecialchars($range); ?>
                                                </a>
                                            </td>
                                            <td class="border border-gray-300 p-2"><?php echo htmlspecialchars(count($bookCounts[$range])); ?></td>
                                            <td class="border border-gray-300 p-2"><?php echo htmlspecialchars(count($allBookCopiesByRange[$range] ?? [])); ?></td>
                                            <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($data['day']); ?></td>
                                            <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($data['week']); ?></td>
                                            <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($data['month']); ?></td>
                                            <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($data['year']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if ($otherRangeData): ?>
                                        <tr>
                                            <td class="border border-gray-300 p-2">
                                                <a href="range_details.php?range=<?php echo urlencode($otherRangeData['range']); ?>">
                                                    <?php echo htmlspecialchars($otherRangeData['range']); ?>
                                                </a>
                                            </td>
                                            <td class="border border-gray-300 p-2"><?php echo htmlspecialchars(count($otherRangeData['bookCounts'])); ?></td>
                                            <td class="border border-gray-300 p-2"><?php echo htmlspecialchars(count($allBookCopiesByRange[$otherRangeData['range']] ?? [])); ?></td>
                                            <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($otherRangeData['borrowCounts']['day']); ?></td>
                                            <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($otherRangeData['borrowCounts']['week']); ?></td>
                                            <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($otherRangeData['borrowCounts']['month']); ?></td>
                                            <td class="border border-gray-300 p-2"><?php echo htmlspecialchars($otherRangeData['borrowCounts']['year']); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr class="bg-gray-100">
                                        <td class="border border-gray-300 p-2 font-bold">Total</td>
                                        <td class="border border-gray-300 p-2 font-bold"><?php echo $totalBooks; ?></td>
                                        <td class="border border-gray-300 p-2 font-bold"><?php echo $totalCopies; ?></td>
                                        <td class="border border-gray-300 p-2 font-bold"><?php echo $totalDay; ?></td>
                                        <td class="border border-gray-300 p-2 font-bold"><?php echo $totalWeek; ?></td>
                                        <td class="border border-gray-300 p-2 font-bold"><?php echo $totalMonth; ?></td>
                                        <td class="border border-gray-300 p-2 font-bold"><?php echo $totalYear; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="mt-4">No borrow data found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <footer>
            <?php include 'include/footer.php'; ?>
        </footer>
    </div>
</div>