<?php
session_start();
include 'db.php';

if (!isset($_SESSION['email'])) {
    die("Unauthorized access. Please <a href='login.php'>login</a> first.");
}

$reservation_id = $_GET['id'] ?? null;
if (!$reservation_id) {
    die("Reservation ID missing.");
}

$user_email = $_SESSION['email'];
$user_result = mysqli_query($conn, "SELECT user_id FROM users WHERE email = '$user_email'");
$user = mysqli_fetch_assoc($user_result);
$user_id = $user['user_id'];

$res_query = "SELECT * FROM reservations WHERE reservation_id = '$reservation_id' AND user_id = '$user_id'";
$res_result = mysqli_query($conn, $res_query);
$reservation = mysqli_fetch_assoc($res_result);

if (!$reservation) {
    die("Reservation not found or not authorized.");
}

$success_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $passenger_name = $_POST['passenger_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $booking_date = $_POST['booking_date'];
    $seat_id = $_POST['seat_id'];

    $update_sql = "UPDATE reservations 
                   SET passenger_name='$passenger_name', age='$age', gender='$gender', booking_date='$booking_date', seat_id='$seat_id' 
                   WHERE reservation_id='$reservation_id' AND user_id='$user_id'";

    if (mysqli_query($conn, $update_sql)) {
        $success_message = "Reservation updated successfully!";
        // Reload latest data
        $res_result = mysqli_query($conn, $res_query);
        $reservation = mysqli_fetch_assoc($res_result);
    } else {
        $success_message = "❌ Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Reservation</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #ece9e6, #ffffff);
            margin: 0;
            padding: 0;
        }

        .form-container {
            max-width: 500px;
            background: #fff;
            margin: 60px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid #c3e6cb;
            margin-bottom: 15px;
            text-align: center;
        }

        label {
            display: block;
            margin-top: 15px;
            color: #555;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            margin-top: 25px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #007bff;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>✏ Update Reservation</h2>

    <?php if ($success_message): ?>
        <div class="success-message"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="passenger_name">Passenger Name:</label>
        <input type="text" name="passenger_name" value="<?= htmlspecialchars($reservation['passenger_name']) ?>" required>

        <label for="age">Age:</label>
        <input type="number" name="age" value="<?= htmlspecialchars($reservation['age']) ?>" min="0" required>

        <label for="gender">Gender:</label>
        <select name="gender" required>
            <option value="">-- Select Gender --</option>
            <option value="Male" <?= $reservation['gender'] === 'Male' ? 'selected' : '' ?>>M</option>
            <option value="Female" <?= $reservation['gender'] === 'Female' ? 'selected' : '' ?>>F</option>
        </select>

        <label for="booking_date">Travel Date:</label>
        <input type="date" name="booking_date" value="<?= htmlspecialchars($reservation['booking_date']) ?>" required>

        <label for="seat_id">Seat Class:</label>
        <select name="seat_id" required>
            <option value="1" <?= $reservation['seat_id'] == 1 ? 'selected' : '' ?>>1 - First Class</option>
            <option value="2" <?= $reservation['seat_id'] == 2 ? 'selected' : '' ?>>2 - Second Class</option>
            <option value="3" <?= $reservation['seat_id'] == 3 ? 'selected' : '' ?>>3 - Sleeper</option>
        </select>

        <input type="submit" value="Update">
    </form>
    <div class="back-link">
        <a href="dashboard.php">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>