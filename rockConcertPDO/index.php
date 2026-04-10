<?php 
	include 'dbconfig.php';

	$update_mode = false;
	$id = 0;
	$name = $venue = $city = $count = $date = "";

	// 1. CREATE or UPDATE: Handle form submission
	if (isset($_POST['save_concert'])) {
		$name = $_POST['event_name'];
		$venue = $_POST['venue'];
		$city = $_POST['city'];
		$count = $_POST['attendance_count'];
		$date = $_POST['event_date'];

		if (isset($_POST['id']) && !empty($_POST['id'])) {
			// UPDATE existing record
			$id = $_POST['id'];
			$conn->query("UPDATE attendances SET event_name='$name', venue='$venue', city='$city', 
						attendance_count=$count, event_date='$date' WHERE id=$id");
		} else {
			// CREATE new record
			$conn->query("INSERT INTO attendances (event_name, venue, city, attendance_count, event_date) 
						VALUES ('$name', '$venue', '$city', $count, '$date')");
		}
		header("Location: index.php");
	}

	// 2. DELETE: Handle delete request
	if (isset($_GET['delete'])) {
		$id = $_GET['delete'];
		$conn->query("DELETE FROM attendances WHERE id=$id");
		header("Location: index.php");
	}

	// 3. EDIT: Fetch specific record to pre-fill the form
	if (isset($_GET['edit'])) {
		$id = $_GET['edit'];
		$update_mode = true;
		$record = $conn->query("SELECT * FROM attendances WHERE id=$id");
		if ($record->num_rows == 1) {
			$n = $record->fetch_assoc();
			$name = $n['event_name'];
			$venue = $n['venue'];
			$city = $n['city'];
			$count = $n['attendance_count'];
			$date = $n['event_date'];
		}
	}

	// Execute query
	$result = $conn->query("SELECT * FROM attendances");

	// Get all rows at once
	$all_data = $result->fetch_all(MYSQLI_ASSOC);

	echo "<h3>3. fetch_all() with print_r</h3>";
	echo "<pre>";
	print_r($all_data);
	echo "</pre>";

	// Execute query
	$result = $conn->query("SELECT * FROM attendances");

	// Get only the first row
	$single_row = $result->fetch_assoc();

	echo "<h3>4. fetch_assoc() with print_r</h3>";
	echo "<pre>";
	print_r($single_row);
	echo "</pre>";

	// READ
	$result = $conn->query("SELECT * FROM attendances ORDER BY event_date DESC");

	$sql = "SELECT event_name, venue, city, attendance_count, event_date FROM attendances";
	$result = $conn->query($sql);
?>



<!DOCTYPE html>
<html>
<head>
	<title>Rock Concert Manager</title>
	<style>
        table { width: 80%; border-collapse: collapse; margin: 20px 0; font-family: Arial, sans-serif; }
        th { background-color: #333; color: white; padding: 10px; }
        td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>

	<h2>Rock Concert Attendance Report</h2>

    <table>
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Venue</th>
                <th>City</th>
                <th>Attendance</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // 2. Check if there are results and loop through them
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['event_name'] . "</td>";
                    echo "<td>" . $row['venue'] . "</td>";
                    echo "<td>" . $row['city'] . "</td>";
                    echo "<td>" . number_format($row['attendance_count']) . "</td>";
                    echo "<td>" . $row['event_date'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>

	<h2>
		<?= $update_mode ? "Edit" : "Add New" ?> Concert
	</h2>

    <form method="POST" style="margin-bottom: 20px; border: 1px solid #ccc; padding: 10px; width: 300px;">
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="text" name="event_name" placeholder="Band Name" value="<?= $name ?>" required><br><br>
        <input type="text" name="venue" placeholder="Venue Name" value="<?= $venue ?>" required><br><br>
        <input type="text" name="city" placeholder="City" value="<?= $city ?>" required><br><br>
        <input type="number" name="attendance_count" placeholder="Attendance" value="<?= $count ?>" required><br><br>
        <input type="date" name="event_date" value="<?= $date ?>" required><br><br>
        
        <?php if ($update_mode): ?>
            <button type="submit" name="save_concert">Update Record</button>
            <a href="index.php">Cancel</a>
        <?php else: ?>
            <button type="submit" name="save_concert">Add Attendance</button>
        <?php endif; ?>
    </form>

    <hr>

    <h2>Concert Attendance List</h2>
    <table border="1" cellpadding="10" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr style="background: #f2f2f2;">
                <th>Band/Event</th><th>Venue (City)</th><th>Attendance</th><th>Date</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['event_name'] ?></td>
                <td><?= $row['venue'] ?> (<?= $row['city'] ?>)</td>
                <td><?= number_format($row['attendance_count']) ?></td>
                <td><?= $row['event_date'] ?></td>
                <td>
                    <a href="index.php?edit=<?= $row['id'] ?>">Edit</a> | 
                    <a href="index.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

	

</body>
</html>
