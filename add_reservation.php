<?php
session_start();
include 'db.php';

if (!isset($_SESSION['email'])) {
    die("Unauthorized access. Please <a href='login.php'>login</a> first.");
}

$selected_train = $_GET['train_id'] ?? '';

// Fetch available trains
$train_result = mysqli_query($conn, "SELECT train_id, train_name FROM trains");
if (!$train_result) {
    die("Error fetching trains: " . mysqli_error($conn));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $train_id = $_POST['train_id'];
    $seat_id = $_POST['seat_id'];
    $booking_date = $_POST['booking_date'];
    $passenger_name = $_POST['passenger_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $user_email = $_SESSION['email'];

    // Get user ID
    $user_result = mysqli_query($conn, "SELECT user_id FROM users WHERE email = '$user_email'");
    if ($user_result && mysqli_num_rows($user_result) > 0) {
        $user = mysqli_fetch_assoc($user_result);
        $user_id = $user['user_id'];

        // Insert reservation
        $sql = "INSERT INTO reservations (user_id, train_id, seat_id, booking_date, passenger_name, age, gender)
                VALUES ('$user_id', '$train_id', '$seat_id', '$booking_date', '$passenger_name', '$age', '$gender')";

        if (mysqli_query($conn, $sql)) {
            $success = "✅ Reservation successful!";
        } else {
            $error = "❌ Error: " . mysqli_error($conn);
        }
    } else {
        $error = "❌ User not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Make a Reservation</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .dashboard-btn {
            display: block;
            text-align: center;
            background-color: #28a745;
            color: white;
            font-weight: bold;
            text-decoration: none;
            padding: 10px;
            margin: 20px auto 0;
            border-radius: 8px;
            width: 200px;
            transition: background-color 0.3s ease;
        }

        .dashboard-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>🚆 Book Your Train Ticket</h2>

        <?php if (isset($success)) echo "<p style='color:green;'>" . htmlspecialchars($success) . "</p>"; ?>
        <?php if (isset($error)) echo "<p style='color:red;'>" . htmlspecialchars($error) . "</p>"; ?>

        <form method="POST">
            <label for="train_id">Select Train:</label>
            <select name="train_id" id="train_id" required>
                <option value="">-- Select Train --</option>
                <?php while ($row = mysqli_fetch_assoc($train_result)) { ?>
                    <option value="<?= htmlspecialchars($row['train_id']) ?>" <?= ($selected_train == $row['train_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['train_id']) ?> - <?= htmlspecialchars($row['train_name']) ?>
                    </option>
                <?php } ?>
            </select>

            <label for="seat_id">Seat Class:</label>
            <select name="seat_id" id="seat_id" required>
                <option value="">-- Select Seat Class --</option>
                <option value="1">1 - First Class</option>
                <option value="2">2 - Second Class</option>
                <option value="3">3 - Sleeper</option>
            </select>

            <label for="passenger_name">Passenger Name:</label>
            <input type="text" name="passenger_name" id="passenger_name" placeholder="Full Name" required>

            <label for="age">Age:</label>
            <input type="number" name="age" id="age" min="0" required>

            <label for="gender">Gender:</label>
            <select name="gender" id="gender" required>
                <option value="">-- Select Gender --</option>
                <option value="Male">M</option>
                <option value="Female">F</option>
            </select>

            <label for="booking_date">Travel Date:</label>
            <input type="date" name="booking_date" id="booking_date" required>

            <input type="submit" value="Book Now">
        </form>

        <!-- Go to Dashboard Button -->
        <a href="dashboard.php" class="dashboard-btn">Go to Dashboard</a>
    </div>
</body>
</html>