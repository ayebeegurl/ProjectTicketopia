<?php
require 'config.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve event form data
    $eventName = $_POST['eventName'];
    $eventDateTime = $_POST['eventDateTime']; // Assuming this is the combined date and time
    $description = $_POST['description'];
    $image = $_FILES['uploadedImage']['name']; // Assuming the input name is 'uploadedImage'
    $seatplan = $_FILES['seatplan']['name'];

    // Upload image and seatplan
    $targetDir = "image/";
    $targetImage = $targetDir . basename($_FILES["uploadedImage"]["name"]);
    $targetSeatplan = $targetDir . basename($_FILES["seatplan"]["name"]);

    if (move_uploaded_file($_FILES["uploadedImage"]["tmp_name"], $targetImage) && move_uploaded_file($_FILES["seatplan"]["tmp_name"], $targetSeatplan)) {
        // Insert event data into events table
        $stmt = $conn->prepare("INSERT INTO events (event_name, event_date_time, description, banner, seatplan) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $eventName, $eventDateTime, $description, $image, $seatplan);

        if ($stmt->execute() === TRUE) {
            // Get the last inserted event ID
            $lastEventId = $conn->insert_id;
            echo "<script>alert('Event added successfully');</script>";
            // Redirect to the category addition page with the event ID as a parameter
            header("Location: addcategories.php?eventId=$lastEventId");
            exit();
        } else {
            echo "<script>alert('Error: There was an error adding the event');</script>";
        }

        // Close prepared statement
        $stmt->close();
    } else {
        echo "<script>alert('Error: There was an error uploading files');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Add Event</title>
</head>

<body>
    <h2>Add Event</h2>
        <form class="container" id="eventForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <label for="eventName">Event Name:</label><br>
            <input type="text" id="eventName" name="eventName" required><br><br>

            <label for="eventDateTime">Event Date and Time:</label><br>
            <input type="datetime-local" id="eventDateTime" name="eventDateTime" required><br><br>

            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50"></textarea><br><br>

            <label for="uploadedImage">Upload Image:</label><br>
            <input type="file" id="uploadedImage" name="uploadedImage" accept=".jpg, .jpeg, .png, .webp"><br><br>

            <label for="seatplan">Upload Seatplan:</label><br>
            <input type="file" id="seatplan" name="seatplan" accept=".jpg, .jpeg, .png, .webp"><br><br>

            <button class="submitButton" type="submit">Add Event</button>
    </form>
</body>

</html>