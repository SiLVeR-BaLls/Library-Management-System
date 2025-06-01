<?php
// Search Form and Top Borrowed Books (Reusable Include)
// Assumes $topBorrowedBooks and generateBookImage() are available in the parent file
?>
<div id="searchForm" class="flex flex-col space-y-4">
    <div class="flex flex-row space-x-4">
        <div class="flex flex-col w-1/5">
            <div class="flex-1">
                <select id="materialType" class="w-full p-2 border rounded">
                    <option value="all">All</option>
                    <option selected value="">Select Material Type</option>
                    <option value="Book">Book</option>
                    <option value="Computer File">Computer File</option>
                    <option value="Electronic Book">Electronic Book (E-Book)</option>
                    <option value="Map">Map</option>
                    <option value="Picture">Picture</option>
                    <option value="Serial">Serial</option>
                    <option value="Video">Video</option>
                    <option value="Journal">Journal</option>
                </select>
            </div>
            <div class="flex-1">
                <select id="subType" class="w-full p-2 border rounded">
                    <option value="all">All</option>
                    <option value="Not Assigned">Not Assigned</option>
                    <option value="Hardcover">Hardcover</option>
                    <option value="Microform">Microform</option>
                    <option value="Online">Online</option>
                    <option value="Paperback">Paperback</option>
                    <option value="Braille">Braille</option>
                    <option value="Dictionary">Dictionary</option>
                    <option value="Picture">Picture</option>
                    <option value="Video">Video</option>
                    <option value="Ebook">Ebook</option>
                </select>
            </div>
            <div class="flex-1">
                <select id="ddcMainClass" class="w-full p-2 border rounded">
                    <option value="all">Display All Books</option>
                    <option value="other">Books Other</option>
                    <option value="000-099">General Works & Computer Science</option>
                    <option value="100-199">Philosophy & Psychology</option>
                    <option value="200-299">Religion</option>
                    <option value="300-399">Social Sciences</option>
                    <option value="400-499">Language</option>
                    <option value="500-599">Pure Sciences</option>
                    <option value="600-699">Technology (Applied Sciences)</option>
                    <option value="700-799">The Arts</option>
                    <option value="800-899">Literature & Rhetoric</option>
                    <option value="900-999">History & Geography</option>
                </select>
            </div>
        </div>
        <div class="flex flex-col w-4/5">
            <div class="flex w-full items-center">
                <input type="text" placeholder="Enter search term..." class="w-full py-2 px-4 rounded-lg" id="searchInput" />
                <button type="button" id="searchButton" class="ml-4 px-6 search-btn block px-4 py-2 rounded-lg text-center cursor-pointer transition duration-300 py-2 rounded-lg font-semibold">Search</button>
                <button type="button" id="resetButton" class="ml-4 px-6 bg-gray-500 text-white rounded-lg px-4 py-2 text-center cursor-pointer transition duration-300 hover:bg-gray-600">Reset</button>
            </div>
            <div class="space-y-2 w-full W-1/6 py-3">
                <div id="searchButtons" class="flex gap-3">
                    <div class="flex w-full gap-3 flex-col">
                        <label class="search-label W-FULL cursor-pointer">
                            <input type="radio" name="searchByOption" value="title" class="hidden" id="titleButton">
                            <span class="search-btn block px-4 py-2 rounded-lg text-center cursor-pointer transition duration-300">Title</span>
                        </label>
                        <label class="search-label W-FULL cursor-pointer">
                            <input type="radio" name="searchByOption" value="all" class="hidden" id="allButton">
                            <span class="search-btn block bg-blue-600 px-4 py-2 rounded-lg text-center cursor-pointer transition duration-300">All</span>
                        </label>
                    </div>
                    <div class="flex w-full gap-3 flex-col">
                        <label class="search-label W-FULL cursor-pointer">
                            <input type="radio" name="searchByOption" value="author" class="hidden" id="authorButton">
                            <span class="search-btn block px-4 py-2 rounded-lg text-center cursor-pointer transition duration-300">Author</span>
                        </label>
                        <label class="search-label W-FULL cursor-pointer">
                            <input type="radio" name="searchByOption" value="coauthor" class="hidden" id="coauthorButton">
                            <span class="search-btn block px-4 py-2 rounded-lg text-center cursor-pointer transition duration-300">Co-Author</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="top-borrowed-books">
    <h3 class="text-lg font-semibold mb-3">Top 10 Most Borrowed Books</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 rounded-md">
            <thead class="bg-gray-200">
                <tr>
                    <th class="text-left px-4 py-2 border-b">count</th>
                    <th class="text-left px-4 py-2 border-b">Photo</th>
                    <th class="text-left px-4 py-2 border-b">Book Title</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($topBorrowedBooks)): ?>
                    <?php foreach ($topBorrowedBooks as $topBook): ?>
                        <tr
                            class="hover:bg-gray-50 cursor-pointer"
                            onclick="createRowClickHandler('<?php echo htmlspecialchars($topBook['book_id']); ?>')()">
                            <td class="px-4 py-2 border-b"><?php echo $topBook['borrow_count']; ?></td>
                            <td class="px-4 py-2 border-b text-gray-900">
                                <div class="image-container">
                                    <?php echo generateBookImage($topBook['photo']); ?>
                                    <div class="image-overlay"></div>
                                </div>
                            </td>
                            <td class="px-4 py-2 border-b text-gray-900"><?php echo htmlspecialchars($topBook['B_title']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="px-4 py-2 text-center text-gray-600">No data available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
