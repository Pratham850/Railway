<?php
session_start();
include 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    $message = "Unauthorized access. Please <a href='login.php'>login</a> first.";
    $status = "error";
} else {
    $reservation_id = $_GET['id'] ?? null;

    // Validate reservation_id
    if (!$reservation_id || !is_numeric($reservation_id)) {
        $message = "Invalid or missing reservation ID.";
        $status = "error";
    } else {
        $user_email = $_SESSION['email'];

        // Use prepared statement to get user_id
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if (!$user) {
            $message = "User not found.";
            $status = "error";
        } else {
            $user_id = $user['user_id'];

            // Check ownership
            $stmt = $conn->prepare("SELECT reservation_id FROM reservations WHERE reservation_id = ? AND user_id = ?");
            $stmt->bind_param("ii", $reservation_id, $user_id);
            $stmt->execute();
            $res_result = $stmt->get_result();
            $stmt->close();

            if ($res_result->num_rows === 0) {
                $message = "Reservation not found or not authorized.";
                $status = "error";
            } else {
                // Delete reservation
                $stmt = $conn->prepare("DELETE FROM reservations WHERE reservation_id = ? AND user_id = ?");
                $stmt->bind_param("ii", $reservation_id, $user_id);
                if ($stmt->execute()) {
                    $message = "✅ Reservation deleted successfully!";
                    $status = "success";
                } else {
                    $message = "❌ Something went wrong. Please try again.";
                    $status = "error";
                }
                $stmt->close();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reservation Status</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f7fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .card {
            background: white;
            border-radius: 12px;
            padding: 30px 40px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            max-width: 500px;
            text-align: center;
        }
        .card.success {
            border-left: 6px solid #28a745;
        }
        .card.error {
            border-left: 6px solid #dc3545;
        }
        h2 {
            color: #333;
        }
        .message {
            font-size: 1.1rem;
            margin: 20px 0;
            color: #555;
        }
        a.button {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            margin-top: 15px;
            font-weight: bold;
        }
        a.button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
<div class="card <?= htmlspecialchars($status) ?>">
    <h2>Reservation Status</h2>
    <p class="message"><?= htmlspecialchars($message) ?></p>
    <a class="button" href="dashboard.php">Return to Dashboard</a>
</div>
</body>
</html>
