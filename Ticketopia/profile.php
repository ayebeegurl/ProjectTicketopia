<?php
require 'config.php';
include "navbar.php";


// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Get the user ID from the session variable
$user_id = $_SESSION["username"];
$username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <?php  ?>
    </header>
    <div class="container">
        <div class="title">User Profile</div>
        <div class="hello"> Hello <?= $username; ?> </div>
        <div class="nav-buttons">
            <button onclick="window.location.href='profile.php'">Dashboard</button>
            <button onclick="window.location.href='addevent.php'">Host an Event</button>
            <button onclick="window.location.href='myaccount.php'">Account Details</button>
            <button onclick="window.location.href='logout.php'">Log Out</button>
        </div>
    </div>
    </div>
    <?php include 'footer.php' ?>
</body>

</html>