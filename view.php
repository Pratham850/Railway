<?php
include 'db.php';

$sql = "SELECT r.reservation_id, u.name, t.train_name, s.seat_number, r.booking_date
        FROM reservations r
        JOIN users u ON r.user_id = u.user_id
        JOIN trains t ON r.train_id = t.train_id
        JOIN seats s ON r.seat_id = s.seat_id";

$result = mysqli_query($conn, $sql);

echo "<h2>Reservations</h2><table border='1'>
<tr><th>ID</th><th>Name</th><th>Train</th><th>Seat</th><th>Date</th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['reservation_id']}</td>
        <td>{$row['name']}</td>
        <td>{$row['train_name']}</td>
        <td>{$row['seat_number']}</td>
        <td>{$row['booking_date']}</td>
    </tr>";
}

echo "</table>";
?>
