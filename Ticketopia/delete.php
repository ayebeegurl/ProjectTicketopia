<?php
require_once "config.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['ticket_id'])) {
        $ticket_id = $_POST['ticket_id'];

        $sql = "DELETE FROM tickets WHERE ticket_id = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $ticket_id);

            if (mysqli_stmt_execute($stmt)) {
                header("location: myevents.php?message=EVent deleted successfully!");
                exit();
            } else {
                echo "Oops! Something went wrong, try again.";
            }
        }

        mysqli_stmt_close($stmt);
    } else {

        echo "No events available";
    }
}
mysqli_close($conn);
