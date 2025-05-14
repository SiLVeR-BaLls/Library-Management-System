<?php
include '../config.php';

$data = json_decode(file_get_contents('php://input'), true);

$searchTerm = $data['searchTerm'] ?? '';
$searchBy = $data['searchByOption'] ?? 'all';
$materialType = $data['materialType'] ?? 'all';
$subType = $data['subType'] ?? 'all';
$ddcMainClass = $data['ddcMainClass'] ?? 'all';

$sql = "SELECT
    b.book_id,
    b.B_title AS title,
    b.author,
    b.MT,
    b.ST,
    b.photo,
    GROUP_CONCAT(DISTINCT ca.Co_Name SEPARATOR ', ') AS coauthor,
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

$sql .= " GROUP BY b.book_id, b.B_title, b.author, b.MT, b.ST ORDER BY b.B_title";

$result = $conn->query($sql);
$books = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}

echo json_encode($books);
?>
