<?php
    // SQL query to get the count of borrowed books grouped by B_title, sorted by Borrowed_Count in descending order
    $sql = "SELECT bc.B_title, COUNT(bb.book_copy) AS Borrowed_Count
            FROM borrow_book bb
            JOIN book_copies bc ON bb.book_copy = bc.book_copy
            GROUP BY bc.B_title
            ORDER BY Borrowed_Count DESC";

    $result = $conn->query($sql);

    // Check for query errors
    if (!$result) {
        die("Error in query: " . $conn->error);
    }

    // Fetch the results into arrays for Chart.js
    $titles = [];
    $counts = [];

    // Populate arrays for chart data
    while ($row = $result->fetch_assoc()) {
        $titles[] = $row['B_title'];
        $counts[] = $row['Borrowed_Count'];
    }

    // Close the connection
    $conn->close();
?>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


  <div class="container mx-auto">
    <h2 class="text-2xl font-semibold mb-6 text-center">Borrowed Book Count</h2>

    <!-- Chart Container -->
    <div class="flex justify-center bg-[#f2f2f2] m-5">
      <canvas id="borrowedChart" width="800" height="250"></canvas>
    </div>
  </div>

  <script>
   // Get the data from PHP
   const titles = <?php echo json_encode($titles); ?>;
        const counts = <?php echo json_encode($counts); ?>;

        // Function to generate a random color
        function generateColors(count) {
            const colors = [];
            for (let i = 0; i < count; i++) {
                const r = Math.floor(Math.random() * 256);
                const g = Math.floor(Math.random() * 256);
                const b = Math.floor(Math.random() * 256);
                colors.push(`rgb(${r}, ${g}, ${b})`);
            }
            return colors;
        }

    // Ensure only valid data (borrowed count > 0) is used
    const filteredTitles = [];
    const filteredCounts = [];
    const filteredColors = [];

    for (let i = 0; i < titles.length; i++) {
      if (counts[i] > 0) {
        filteredTitles.push(titles[i]);
        filteredCounts.push(counts[i]);
        filteredColors.push(generateColors(1)[0]);
      }
    }

    // Dynamic datasets for each title
    const datasets = filteredTitles.map((title, index) => ({
      label: title,  // Use the book title as the label
      data: [filteredCounts[index]],  // Corresponding borrowed count
      backgroundColor: filteredColors[index],  // Unique color for the bar
      borderColor: 'rgba(0,0,0,0.1)',  // Border color for each bar
      borderWidth: 1  // Border width for each bar
    }));

    // Create the chart
    const ctx = document.getElementById('borrowedChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',  // Bar chart type
      data: {
        labels: [''],  // Empty label (so that we can position the bars correctly)
        datasets: datasets  // Multiple datasets (one for each book)
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'right',
            labels: {
              font: {
                size: 14
              },
              boxWidth: 20,
              padding: 15
            }
          }
        },
        scales: {
          x: {
            ticks: {
              autoSkip: false,
              maxRotation: 45,
              minRotation: 45  // Rotate labels if they overlap
            },
            grid: {
              display: false  // Hide the gridlines for the x-axis
            }
          },
          y: {
            beginAtZero: true,  // Start y-axis at zero
            ticks: {
              stepSize: 5,  // Set step size to 5
            }
          }
        }
      }

    });
  </script>
