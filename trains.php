<?php
include 'db.php';

$sql = "SELECT * FROM trains";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Trains</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .train-table {
            width: 80%;
            margin: 40px auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .train-table th, .train-table td {
            padding: 12px 20px;
            border: 1px solid #ccc;
            text-align: left;
        }

        .train-table th {
            background-color: #0078D7;
            color: white;
        }

        .train-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <h2>🚄 Available Trains</h2>

    <table class="train-table">
        <tr>
            <th>Train ID</th>
            <th>Train Name</th>
            <th>Source</th>
            <th>Destination</th>
            <th>Departure Time</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['train_id']; ?></td>
                <td><?php echo $row['train_name']; ?></td>
                <td><?php echo $row['source']; ?></td>
                <td><?php echo $row['destination']; ?></td>
                <td><?php echo date("g:i A", strtotime($row['departure_time'])); ?></td>
            </tr>
            <!-- Add this column header -->
<th>Action</th>

<!-- Inside the while loop -->
<td>
    <a href="add_reservation.php?train_id=<?php echo $row['train_id']; ?>">
        <button style="padding:6px 10px;">Book Now</button>
    </a>
</td>

        <?php } ?>
    </table>
</body>
</html>
