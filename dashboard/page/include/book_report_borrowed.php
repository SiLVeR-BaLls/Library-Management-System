    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <style>
        .chart-container {
            width: 100%;
            margin: 0 auto;
            /* Center the container */
            height: 40%;
            /* Reduced height */
        }

        .ratingsChart {
            width: 100%;
            height: 40%;
            /* Reduced canvas height */
        }
    </style>

    <!-- Main Content -->
    <div class="container mx-auto">
        <h2 class="text-2xl font-semibold mb-6 text-center">Borrowed Books Report</h2>
        <div class="flex flex-col lg:flex-row gap-8 justify-center">
            <!-- Borrowed by Course -->
            <div class="chart-container w-full lg:w-1/2 h-[80vh] overflow-hidden">
                <h3 class="chart-title text-xl font-semibold mb-4 text-center">Program</h3>
                <canvas id="borrowedCourseChart" class="h-[80%] w-full"></canvas>
            </div>
            <!-- Borrowed by College -->
            <div class="chart-container w-full lg:w-1/2 h-[80vh] overflow-hidden">
                <h3 class="chart-title text-xl font-semibold mb-4 text-center">College</h3>
                <canvas id="borrowedCollegeChart" class="h-[80%] w-full"></canvas>
            </div>
        </div>
    </div>

    <?php
            // SQL Queries for Borrowed Books
            $borrowedQuery = "
            SELECT u.course, u.college, COUNT(bb.ID) AS borrow_count
            FROM borrow_book AS bb
            LEFT JOIN users_info AS u ON bb.IDno = u.IDno
            WHERE bb.borrow_date IS NOT NULL
            GROUP BY u.course, u.college
        ";
            $borrowedResult = $conn->query($borrowedQuery);

            $borrowedCourseData = [];
            $borrowedCourseLabels = [];
            $borrowedCollegeData = [];
            $borrowedCollegeLabels = [];

            while ($row = $borrowedResult->fetch_assoc()) {
                // For courses: Aggregate the borrow count by course
                if (isset($borrowedCourseData[$row['course']])) {
                    $borrowedCourseData[$row['course']] += $row['borrow_count'];
                } else {
                    $borrowedCourseLabels[] = $row['course'];
                    $borrowedCourseData[$row['course']] = $row['borrow_count'];
                }

                // For colleges: Aggregate the borrow count by college
                if (isset($borrowedCollegeData[$row['college']])) {
                    $borrowedCollegeData[$row['college']] += $row['borrow_count'];
                } else {
                    $borrowedCollegeLabels[] = $row['college'];
                    $borrowedCollegeData[$row['college']] = $row['borrow_count'];
                }
            }

            // Sort data by borrow count in descending order
            arsort($borrowedCourseData);
            arsort($borrowedCollegeData);

            // Convert associative arrays back to indexed arrays for Chart.js
            $borrowedCourseLabels = array_values($borrowedCourseLabels);
            $borrowedCourseData = array_values($borrowedCourseData);
            $borrowedCollegeLabels = array_values($borrowedCollegeLabels);
            $borrowedCollegeData = array_values($borrowedCollegeData);
    ?>

    <!-- Chart.js Scripts -->
    <script>
        // Borrowed Data from PHP
        const borrowedCourseLabels = <?php echo json_encode($borrowedCourseLabels); ?>;
        const borrowedCourseData = <?php echo json_encode($borrowedCourseData); ?>;
        const borrowedCollegeLabels = <?php echo json_encode($borrowedCollegeLabels); ?>;
        const borrowedCollegeData = <?php echo json_encode($borrowedCollegeData); ?>;

        // Generate unique colors for each dataset
        function generateColors(count) {
            return Array.from({
                length: count
            }, () => {
                const r = Math.floor(Math.random() * 256);
                const g = Math.floor(Math.random() * 256);
                const b = Math.floor(Math.random() * 256);
                return `rgb(${r}, ${g}, ${b})`;
            });
        }

        // Dynamic datasets for courses
        const courseDatasets = borrowedCourseLabels.map((label, index) => ({
            label: label,
            data: [borrowedCourseData[index]],
            backgroundColor: generateColors(1),
        }));

        // Dynamic datasets for colleges
        const collegeDatasets = borrowedCollegeLabels.map((label, index) => ({
            label: label,
            data: [borrowedCollegeData[index]],
            backgroundColor: generateColors(1),
        }));

        // Borrowed Books by Course Chart
        new Chart(document.getElementById('borrowedCourseChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Borrowed Books'],
                datasets: courseDatasets,
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                    }
                },
                responsive: true,
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Borrow Count'
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: 5,
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Courses'
                        },
                        reverse: true,
                    }
                }
            }
        });

        // Borrowed Books by College Chart
        new Chart(document.getElementById('borrowedCollegeChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Borrowed Books'],
                datasets: collegeDatasets,
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                    }
                },
                responsive: true,
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Borrow Count'
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: 5,
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Colleges'
                        },
                        reverse: true,
                    }
                }
            }
        });
    </script>
