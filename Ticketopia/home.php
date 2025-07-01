<?php
include 'navbar.php';
require_once "config.php";

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Fetch upcoming events from the database
$sql = "SELECT * FROM events WHERE event_date_time >= CURDATE() ORDER BY event_date_time ASC";
$result = $conn->query($sql);

// Check for database errors
if (!$result) {
    // Handle the error, for example:
    die("Error fetching events: " . $conn->error);
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Include Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Include Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Include custom stylesheet -->
    <link rel="stylesheet" href="style.css">
    <!-- Link to Poppins font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body>

    <div class="banner">
        <a href="tds.php">
            <!-- Display banner image -->
            <img src="tds3.jpg" alt="tds" width="20%" height="20%">
        </a>
    </div>
    <div class="service">
        <?php
        // Check if there are events
        if ($result->num_rows > 0) {
            // Loop through each event
            while ($fetch_product = $result->fetch_assoc()) {
        ?>
                <div class="gallery">
                    <!-- Link to event page with event ID -->
                    <a href="event.php?event_id=<?php echo $fetch_product['event_id']; ?>">
                        <!-- Display event banner image -->
                        <img src="image/<?php echo $fetch_product['banner']; ?>" alt="<?php echo $fetch_product['event_name']; ?>" width="20%" height="20%">
                        <!-- Display event name -->
                        <div class="desc"><?php echo $fetch_product['event_name']; ?></div>
                    </a>
                </div>
        <?php
            }
        } else {
            echo "No upcoming events.";
        }
        ?>
    </div>

        

    <!-- <a href="logout.php">Logout</a> -->
</body>

</html>