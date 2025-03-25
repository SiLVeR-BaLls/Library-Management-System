    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    
    <style>
        .chart-container {
            width: 100%;
            margin: 0 auto;
            height: 50%;
        }

        .ratingsChart {
            width: 100%;
            height: 30%;
            background-color: #f2f2f2;
            margin: 0 auto;

        }
    </style>

    <!-- Main Content -->
    <div class="flex-grow m-10">
        <h2 class="text-2xl font-semibold mb-6 text-center">Book Ratings</h2>
        <div class="charts-wrapper">
            <!-- Chart container with reduced height -->
            <div class="chart-container w-auto">
                <h3 class="text-lg font-semibold mb-2">Ratings Distribution</h3>
                <canvas id="ratingsChart" class="ratingsChart" style="height: 350px;"></canvas>
            </div>
        </div>
    </div>

    <?php
    // SQL Query to Get Count of Books by Rating
    $ratingQuery = "
        SELECT rating, COUNT(*) AS rating_count 
        FROM book_copies
        WHERE rating IS NOT NULL AND status IN ('Available', 'Borrowed')
        GROUP BY rating
    ";
    $ratingResult = $conn->query($ratingQuery);

    // Arrays to store rating data for the chart
    $ratingCounts = [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
    $booksByRating = [];

    // Fetch the rating data from the database
    while ($row = $ratingResult->fetch_assoc()) {
        $ratingCounts[(int)$row['rating']] = $row['rating_count'];

        // Get the titles of books with the specific rating
        $bookTitlesQuery = "
            SELECT B_title FROM book_copies 
            WHERE rating = '" . $row['rating'] . "' AND status IN ('Available', 'Borrowed')
        ";
        $titlesResult = $conn->query($bookTitlesQuery);

        $bookTitles = [];
        while ($titleRow = $titlesResult->fetch_assoc()) {
            $bookTitles[] = $titleRow['B_title'];
        }

        // Store book titles for hover display
        $booksByRating[(int)$row['rating']] = $bookTitles;
    }
    ?>

    <!-- Chart.js Scripts -->
    <script>
        // Rating Data for Bar Chart
        // Rating Data for Bar Chart
        const ratingLabels = ['0', '1', '2', '3', '4', '5'];
        const ratingData = <?php echo json_encode(array_values($ratingCounts)); ?>;
        const bookTitles = <?php echo json_encode($booksByRating); ?>;

        // Initialize the Chart.js Bar Chart
        const ctx = document.getElementById('ratingsChart').getContext('2d');
        const ratingsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ratingLabels,
                datasets: [{
                    label: 'Number of Books',
                    data: ratingData,
                    backgroundColor: '#4CAF50',
                    borderColor: '#388E3C',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            // Display the book titles and count on hover
                            afterLabel: function(tooltipItem) {
                                const rating = tooltipItem.label;
                                const titles = bookTitles[rating];
                                return titles ? 'Books: ' + titles.join(', ') : 'No books available';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Book Count'
                        },
                        ticks: {
                            stepSize: 5, // Increment y-axis labels by 5
                            precision: 0 // Ensure no decimal values
                        }
                    }
                }
            }
        });
    </script>