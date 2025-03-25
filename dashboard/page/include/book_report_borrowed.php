<?php
// Get the selected time period (day, week, month, or year)
$timePeriod = isset($_GET['period']) ? $_GET['period'] : 'day'; // Default to 'day' if no period is set

// Calculate date range based on the selected period
if ($timePeriod == 'day') {
    $dateGroup = "DATE(bb.borrow_date)";
    $dateFilter = "DATE(bb.borrow_date) = CURDATE()"; // Filter by today's date
} elseif ($timePeriod == 'week') {
    $dateGroup = "YEARWEEK(bb.borrow_date)";
    $dateFilter = "YEARWEEK(bb.borrow_date) = YEARWEEK(CURDATE())"; // Filter by current week
} elseif ($timePeriod == 'month') {
    $dateGroup = "MONTH(bb.borrow_date)";
    $dateFilter = "MONTH(bb.borrow_date) = MONTH(CURDATE()) AND YEAR(bb.borrow_date) = YEAR(CURDATE())"; // Filter by current month
} elseif ($timePeriod == 'year') {
    $dateGroup = "YEAR(bb.borrow_date)";
    $dateFilter = "YEAR(bb.borrow_date) = YEAR(CURDATE())"; // Filter by current year
} else {
    $dateGroup = "DATE(bb.borrow_date)";
    $dateFilter = "DATE(bb.borrow_date) = CURDATE()"; // Default to today if no valid period is selected
}

// Modified SQL query to include date filter based on selected period
$borrowedQuery = "
    SELECT $dateGroup AS period, COALESCE(u.course, 'faculty') AS course, u.college, COUNT(bb.ID) AS borrow_count
    FROM borrow_book AS bb
    LEFT JOIN users_info AS u ON bb.IDno = u.IDno
    WHERE bb.borrow_date IS NOT NULL
    AND $dateFilter
    GROUP BY period, course, u.college
    ORDER BY period DESC
";

$borrowedResult = $conn->query($borrowedQuery);

$borrowedCourseData = [];
$borrowedCourseLabels = [];
$borrowedCollegeData = [];
$borrowedCollegeLabels = [];

while ($row = $borrowedResult->fetch_assoc()) {
    // For courses: Aggregate the borrow count by course
    $course = $row['course'] ?: 'faculty'; // If course is empty, set it to 'faculty'
    
    if (isset($borrowedCourseData[$course])) {
        $borrowedCourseData[$course] += $row['borrow_count'];
    } else {
        $borrowedCourseLabels[] = $course;
        $borrowedCourseData[$course] = $row['borrow_count'];
    }

    // For colleges: Aggregate the borrow count by college
    if (isset($borrowedCollegeData[$row['college']])) {
        $borrowedCollegeData[$row['college']] += $row['borrow_count'];
    } else {
        $borrowedCollegeLabels[] = $row['college'];
        $borrowedCollegeData[$row['college']] = $row['borrow_count'];
    }

    // Optionally add the period for display (could be a date, week, month, or year)
    $borrowedPeriodLabels[] = $row['period'];
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .chart-container {
        background-color: #f2f2f2;
        width: 100%;
        margin: 0 auto;
        height: 40%;
        padding: 20px;
    }

    .ratingsChart {
        width: 100%;
        height: 40%;
    }
</style>

<!-- Dropdown to Select Time Period -->
<div class="mb-6 text-center">
    <label for="timePeriod" class="text-lg">Select Time Period:</label>
    <select id="timePeriod" class="px-4 py-2 border rounded-lg">
        <option value="day" <?= $timePeriod == 'day' ? 'selected' : '' ?>>Day</option>
        <option value="week" <?= $timePeriod == 'week' ? 'selected' : '' ?>>Week</option>
        <option value="month" <?= $timePeriod == 'month' ? 'selected' : '' ?>>Month</option>
        <option value="year" <?= $timePeriod == 'year' ? 'selected' : '' ?>>Year</option>
    </select>
</div>

<div class="flex flex-col lg:flex-row m-6 gap-8 justify-center">
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

<!-- Chart.js Scripts -->
<script>
    // Borrowed Data from PHP
    const borrowedCourseLabels = <?php echo json_encode($borrowedCourseLabels); ?>;
    const borrowedCourseData = <?php echo json_encode($borrowedCourseData); ?>;
    const borrowedCollegeLabels = <?php echo json_encode($borrowedCollegeLabels); ?>;
    const borrowedCollegeData = <?php echo json_encode($borrowedCollegeData); ?>;

    // Function to update charts when a new time period is selected
    document.getElementById('timePeriod').addEventListener('change', function() {
        const selectedPeriod = this.value;

        // Fetch new data based on selected period (AJAX request or page reload with period)
        // Here we assume an AJAX call or a page reload happens based on the selected value

        // Example: Update the charts with new data
        updateCharts(selectedPeriod);
    });

    // Update charts with the selected time period data
    function updateCharts(period) {
        window.location.href = `?period=${period}`;
    }

    // Function to generate random colors
    function generateColors(count) {
        return Array.from({ length: count }, () => {
            const r = Math.floor(Math.random() * 256);
            const g = Math.floor(Math.random() * 256);
            const b = Math.floor(Math.random() * 256);
            return `rgb(${r}, ${g}, ${b})`;
        });
    }

    // Generate course datasets dynamically
    const courseDatasets = borrowedCourseLabels.map((label, index) => ({
        label: label,
        data: [borrowedCourseData[index]],
        backgroundColor: generateColors(1),
    }));

    // Generate college datasets dynamically
    const collegeDatasets = borrowedCollegeLabels.map((label, index) => ({
        label: label,
        data: [borrowedCollegeData[index]],
        backgroundColor: generateColors(1),
    }));

    // Create Borrowed Books by Course Chart
    new Chart(document.getElementById('borrowedCourseChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Borrowed Books'],
            datasets: courseDatasets,
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right', // Set legend to the right
                }
            },
            scales: {
                y: {
                    title: { display: true, text: 'Borrow Count' },
                    ticks: {
                        beginAtZero: false, // Start from 40 instead of 0
                        min: 40, // Set minimum Y-axis value to 40
                        stepSize: 5, // Increment Y-axis by 5
                    },
                },
                x: {
                    title: { display: true, text: 'Courses' },
                    reverse: true,
                }
            }
        }
    });

    // Create Borrowed Books by College Chart
    new Chart(document.getElementById('borrowedCollegeChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Borrowed Books'],
            datasets: collegeDatasets,
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right', // Set legend to the right
                }
            },
            scales: {
                y: {
                    title: { display: true, text: 'Borrow Count' },
                    ticks: {
                        beginAtZero: false, // Start from 40 instead of 0
                        min: 40, // Set minimum Y-axis value to 40
                        stepSize: 5, // Increment Y-axis by 5
                    },
                },
                x: {
                    title: { display: true, text: 'Colleges' },
                    reverse: true,
                }
            }
        }
    });
</script>
