<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "railway_reservation";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch reservation data
$sql = "SELECT 
            reservations.reservation_id, 
            COALESCE(trains.train_name, 'Unknown') AS train_name, 
            reservations.passenger_name, 
            reservations.age, 
            reservations.gender, 
            reservations.booking_date 
        FROM reservations
        LEFT JOIN trains ON reservations.train_id = trains.train_id";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error executing query: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Railway Dashboard</title>
    <link rel="stylesheet" href="dash.css">
    <style>
        .btn {
            padding: 6px 12px;
            text-decoration: none;
            color: white;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s ease;
            margin-right: 5px;
        }

        .update-btn {
            background-color: #28a745;
        }

        .update-btn:hover {
            background-color: #218838;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <h2>Railway Management</h2>
            </div>
            <ul class="nav-links">
                <li><a href="#"></a></li>
                <li><a href="#"></a></li>
                <li><a href="index.html">Home</a></li>
                <li><a href="trains.php">Train Schedules</a></li>
                <li><a href="reservation.html">Reservations</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header>
                <h1>Reservation List</h1>
            </header>

            <div class="content">
                <table class="reservation-table" border="1" cellpadding="10" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Reservation ID</th>
                            <th>Train Name</th>
                            <th>Passenger Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Booking Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['reservation_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['train_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['passenger_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['age']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['booking_date']) . "</td>";
                                echo "<td>
                                        <a href='update.php?id=" . $row['reservation_id'] . "' class='btn update-btn'>Update</a>
                                        <a href='delete.php?id=" . $row['reservation_id'] . "' class='btn delete-btn' onclick=\"return confirm('Are you sure you want to delete this reservation?');\">Delete</a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No reservations found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <footer>
        <p>&copy; 2025 Railway Management System | All Rights Reserved</p>
    </footer>

    <?php $conn->close(); ?>
</body>
</html>
