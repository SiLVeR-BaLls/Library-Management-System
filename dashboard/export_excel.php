<?php
include 'config.php';

// Set headers for Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=books_export.xls");

// Start table
echo "<table border='1'>";
echo "<tr>
    <th>Title</th>
    <th>Subtitle</th>
    <th>Author</th>
    <th>Edition</th>
    <th>Material Type</th>
    <th>Subtype</th>
    <th>ISBN</th>
    <th>ISSN</th>
    <th>Place</th>
    <th>Publisher</th>
    <th>Pdate</th>
    <th>Copyright</th>
    <th>Extent</th>
    <th>Odetails</th>
    <th>Size</th>
    <th>URL</th>
    <th>Description</th>
    <th>UTitle</th>
    <th>Vform</th>
    <th>SUTitle</th>
    <th>Volume</th>
    <th>Note</th>
    <th>Photo</th>
    <th>Subject</th>
    <th>Co-Author(s)</th>
</tr>";

// Query the books
$sqlBook = "SELECT
    b.B_title,
    b.subtitle,
    b.author,
    b.edition,
    b.MT,
    b.ST,
    b.ISBN,
    b.ISSN,
    b.place,
    b.publisher,
    b.Pdate,
    b.copyright,
    b.extent,
    b.Odetail,
    b.size,
    b.url,
    b.Description,
    b.UTitle,
    b.VForm,
    b.SUTitle,
    b.volume,
    b.note,
    b.photo,
    GROUP_CONCAT(DISTINCT CONCAT(s.Sub_Head, '(', s.Sub_Head_input, ')') SEPARATOR ', ') AS subjects,
    GROUP_CONCAT(DISTINCT CONCAT(ca.Co_Name, '-', ca.Co_Role, '-(', ca.Co_Date, ')') SEPARATOR ', ') AS co_authors
FROM
    `book` b
LEFT JOIN
    `subject` s ON b.book_id = s.book_id
LEFT JOIN
    `coauthor` ca ON b.book_id = ca.book_id
GROUP BY
    b.book_id";

$result = $conn->query($sqlBook);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $cell) {
            echo "<td>" . htmlspecialchars($cell) . "</td>";
        }
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='25'>No data found</td></tr>";
}

echo "</table>";

$conn->close();
?>
