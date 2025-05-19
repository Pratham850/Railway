<?php
include 'db.php';

$source = $_POST['source'];
$destination = $_POST['destination'];
$travel_date = $_POST['travel_date'];

$sql = "SELECT * FROM trains WHERE source = '$source' AND destination = '$destination' AND travel_date = '$travel_date'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<h2>Available Trains</h2><ul>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>" . $row['train_name'] . " from " . $row['source'] . " to " . $row['destination'] . " on " . $row['travel_date'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "No trains found.";
}

mysqli_close($conn);
?>
