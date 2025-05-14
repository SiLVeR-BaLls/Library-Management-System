<?php
// Include the database connection
include '../config.php';
?>

<div class="flex">
    <!-- Sidebar Section -->
    <?php if (!empty($userType) && isset($sidebars[$userType]) && file_exists($sidebars[$userType])) {
        include $sidebars[$userType];
    } ?>
    <div class="w-full min-h-screen bg-gray-100">
        <!-- BrowseBook Content Section -->
        <div class="flex-grow ">
            <!-- Header at the Top -->
            <?php include 'include/header.php'; ?>
            <form method="post" action="export_excel.php" class="mb-4">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Export to Excel
                </button>
            </form>

            <div class="overflow-x-auto px-6 py-4 bg-white shadow-lg rounded-lg">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Book List</h2>
                <table class="min-w-full text-sm text-left text-gray-700 border">
                    <thead class="text-xs text-white uppercase bg-gray-800 sticky top-0">
                        <tr class="bg-gray-800 text-white">
                            <th class="p-4 text-left">Title</th>
                            <th class="p-4 text-left">Subtitle</th>
                            <th class="p-4 text-left">Author</th>
                            <th class="p-4 text-center">Edition</th>
                            <th class="p-4 text-center">Material Type</th>
                            <th class="p-4 text-center">Subtype</th>
                            <th class="p-4 text-center">ISBN</th>
                            <th class="p-4 text-center">ISSN</th>
                            <th class="p-4 text-center">Place</th>
                            <th class="p-4 text-center">Publisher</th>
                            <th class="p-4 text-center">Pdate</th>
                            <th class="p-4 text-center">Copyright</th>
                            <th class="p-4 text-center">Extent</th>
                            <th class="p-4 text-center">Odetails</th>
                            <th class="p-4 text-center">Size</th>
                            <th class="p-4 text-center">URL</th>
                            <th class="p-4 text-center">Description</th>
                            <th class="p-4 text-center">UTitle</th>
                            <th class="p-4 text-center">Vform</th>
                            <th class="p-4 text-center">SUTitle</th>
                            <th class="p-4 text-center">Volume</th>
                            <th class="p-4 text-center">Note</th>
                            <th class="p-4 text-center">Photo</th>
                            <th class="p-4 text-left">Subject</th>
                            <th class="p-4 text-left">Co-Author(s)</th>
                        </tr>
                    </thead>
                    <tbody id="bookTableBody" class="bg-white">
                        <?php
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

                        $resultBook = $conn->query($sqlBook);

                        if ($resultBook->num_rows > 0) {
                            while ($rowBook = $resultBook->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='p-4 text-left'>" . htmlspecialchars($rowBook['B_title']) . "</td>";
                                echo "<td class='p-4 text-left'>" . htmlspecialchars($rowBook['subtitle']) . "</td>";
                                echo "<td class='p-4 text-left'>" . htmlspecialchars($rowBook['author']) . "</td>";
                                echo "<td class='p-4 text-center'>" . htmlspecialchars($rowBook['edition']) . "</td>";
                                echo "<td class='p-4 text-center'>" . htmlspecialchars($rowBook['MT']) . "</td>";
                                echo "<td class='p-4 text-center'>" . htmlspecialchars($rowBook['ST']) . "</td>";
                                echo "<td class='p-4 text-center'>" . htmlspecialchars($rowBook['ISBN']) . "</td>";
                                echo "<td class='p-4 text-center'>" . htmlspecialchars($rowBook['ISSN']) . "</td>";
                                echo "<td class='p-4 text-center'>" . htmlspecialchars($rowBook['place']) . "</td>";
                                echo "<td class='p-4 text-center'>" . htmlspecialchars($rowBook['publisher']) . "</td>";
                                echo "<td class='p-4 text-center'>" . htmlspecialchars($rowBook['Pdate']) . "</td>";
                                echo "<td class='p-4 text-center'>" . htmlspecialchars($rowBook['copyright']) . "</td>";
                                echo "<td class='p-4 text-center'>" . htmlspecialchars($rowBook['extent']) . "</td>";
                                echo "<td class='p-4 text-center'>" . htmlspecialchars($rowBook['Odetail']) . "</td>";
                                echo "<td class='p-4 text-center'>" . htmlspecialchars($rowBook['size']) . "</td>";
                                echo "<td class='p-4 text-center'>" . htmlspecialchars($rowBook['url']) . "</td>";
                                echo "<td class='p-4 text-center'>" . htmlspecialchars($rowBook['Description']) . "</td>";
                                echo "<td class='p-4 text-center'>" . htmlspecialchars($rowBook['UTitle']) . "</td>";
                                echo "<td class='p-4 text-center'>" . htmlspecialchars($rowBook['VForm']) . "</td>";
                                echo "<td class='p-4 text-center'>" . htmlspecialchars($rowBook['SUTitle']) . "</td>";
                                echo "<td class='p-4 text-center'>" . htmlspecialchars($rowBook['volume']) . "</td>";
                                echo "<td class='p-4 text-center'>" . htmlspecialchars($rowBook['note']) . "</td>";
                                echo "<td class='p-4 text-center'>" . htmlspecialchars($rowBook['photo']) . "</td>";
                                echo "<td class='p-4 text-left'>" . htmlspecialchars($rowBook['subjects']) . "</td>";
                                echo "<td class='p-4 text-left'>" . htmlspecialchars($rowBook['co_authors']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td class='p-4 text-center' colspan='26'>No books found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
<!-- Footer at the Bottom -->
<footer>
    <?php include 'include/footer.php'; ?>
</footer>
</div>

<?php
// Close the database connection
$conn->close();
?>