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
        <h2 class="text-2xl font-semibold mb-6 text-center">Returned Books Report</h2>
        <div class="flex flex-col lg:flex-row gap-8 justify-center">
            <!-- Returned by Course -->
            <div class="chart-container w-full lg:w-1/2  h-[80vh] overflow-hidden"> <!-- Set height to 70% of viewport height -->
                <h3 class="chart-title text-xl font-semibold mb-4 text-center">Program</h3>
                <canvas id="returnedCourseChart" class="h-[80%] w-full"></canvas> <!-- Set canvas to 100% of container height -->
            </div>
            <!-- Returned by College -->
            <div class="chart-container w-full lg:w-1/2  h-[80vh] overflow-hidden"> <!-- Set height to 70% of viewport height -->
                <h3 class="chart-title text-xl font-semibold mb-4 text-center">College</h3>
                <canvas id="returnedCollegeChart" class="h-[80%] w-full"></canvas> <!-- Set canvas to 100% of container height -->
            </div>
        </div>
    </div>

    <?php
    // SQL Queries for Returned Books
    $returnedQuery = "
      SELECT u.course, u.college, COUNT(bb.ID) AS return_count
      FROM borrow_book AS bb
      LEFT JOIN users_info AS u ON bb.IDno = u.IDno
      WHERE bb.return_date IS NOT NULL
      GROUP BY u.course, u.college
  ";
    $returnedResult = $conn->query($returnedQuery);

    $returnedCourseData = [];
    $returnedCourseLabels = [];
    $returnedCollegeData = [];
    $returnedCollegeLabels = [];

    while ($row = $returnedResult->fetch_assoc()) {
        // For courses: Aggregate the return count by course
        if (isset($returnedCourseData[$row['course']])) {
            $returnedCourseData[$row['course']] += $row['return_count'];
        } else {
            $returnedCourseLabels[] = $row['course'];
            $returnedCourseData[$row['course']] = $row['return_count'];
        }

        // For colleges: Aggregate the return count by college
        if (isset($returnedCollegeData[$row['college']])) {
            $returnedCollegeData[$row['college']] += $row['return_count'];
        } else {
            $returnedCollegeLabels[] = $row['college'];
            $returnedCollegeData[$row['college']] = $row['return_count'];
        }
    }

    // Sort data by return count in descending order
    arsort($returnedCourseData);
    arsort($returnedCollegeData);

    // Convert associative arrays back to indexed arrays for Chart.js
    $returnedCourseLabels = array_values($returnedCourseLabels);
    $returnedCourseData = array_values($returnedCourseData);
    $returnedCollegeLabels = array_values($returnedCollegeLabels);
    $returnedCollegeData = array_values($returnedCollegeData);
    ?>

    <!-- Chart.js Scripts -->
    <script>
        // Borrowed Data from PHP
        const returnedCourseLabels = <?php echo json_encode($returnedCourseLabels); ?>;
        const returnedCourseData = <?php echo json_encode($returnedCourseData); ?>;
        const returnedCollegeLabels = <?php echo json_encode($returnedCollegeLabels); ?>;
        const returnedCollegeData = <?php echo json_encode($returnedCollegeData); ?>;

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
        const courseDatasets = returnedCourseLabels.map((label, index) => ({
            label: label, // Use the course name as the dataset label
            data: [returnedCourseData[index]], // Data for this course
            backgroundColor: generateColors(1), // Unique color for each course
        }));

        // Dynamic datasets for colleges
        const collegeDatasets = returnedCollegeLabels.map((label, index) => ({
            label: label, // Use the college name as the dataset label
            data: [returnedCollegeData[index]], // Data for this college
            backgroundColor: generateColors(1), // Unique color for each college
        }));

        // Returned Books by Course Chart
        new Chart(document.getElementById('returnedCourseChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Returned Books'], // Single bar for all datasets
                datasets: courseDatasets,
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                        position: 'right', // Legend on the right side
                    }
                },
                responsive: true,
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Return Count'
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: 5, // Set step size to 5 on the Y-axis
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Courses'
                        },
                        reverse: true, // Reverses the order of bars so the highest appears on the right
                    }
                }
            }
        });

        // Returned Books by College Chart
        new Chart(document.getElementById('returnedCollegeChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Returned Books'], // Single bar for all datasets
                datasets: collegeDatasets,
            },
            options: {
                plugins: {
                    legend: {
                        display: true,
                        position: 'right', // Legend on the right side
                    }
                },
                responsive: true,
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Return Count'
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: 5, // Set step size to 5 on the Y-axis
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Colleges'
                        },
                        reverse: true, // Reverses the order of bars so the highest appears on the right
                    }
                }
            }
        });
    </script>