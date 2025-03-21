<?php
include '../../config.php'; // Include the database connection file

// Check if the connection is established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$filename = 'AttendanceRecord-'.date('Y-m-d').'.csv';

// SQL query to fetch the attendance records
$query = "SELECT * FROM attendance";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$file = fopen($filename, "w");

// Add column headers to CSV
$array = array("ID", "STUDENT ID", "TIME IN", "TIME OUT", "LOG DATE", "STATUS");
fputcsv($file, $array);

// Write the records to the CSV file
while ($row = mysqli_fetch_array($result)) {
    // Check if the required columns exist in the result
    $ID = isset($row['ID']) ? $row['ID'] : '';
    $STUDENTID = isset($row['STUDENTID']) ? $row['STUDENTID'] : ''; // Ensure this column exists in the database
    $TIMEIN = isset($row['TIMEIN']) ? $row['TIMEIN'] : '';
    $TIMEOUT = isset($row['TIMEOUT']) ? $row['TIMEOUT'] : '';
    $LOGDATE = isset($row['LOGDATE']) ? $row['LOGDATE'] : '';
    $STATUS = isset($row['STATUS']) ? $row['STATUS'] : '';

    // Create an array for each record and write it to the CSV file
    $array = array($ID, $STUDENTID, $TIMEIN, $TIMEOUT, $LOGDATE, $STATUS);
    fputcsv($file, $array);
}

// Close the file after writing
fclose($file);

// Send headers to prompt the download
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=$filename");
header("Content-Type: application/csv; charset=UTF-8");

// Read the file and send to the browser
readfile($filename);

// Optionally delete the file after download
unlink($filename);

exit();
?>
