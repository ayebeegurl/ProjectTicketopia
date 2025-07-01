<?php
include "navbar.php";
require 'config.php';

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $password = htmlspecialchars($_POST['password']);
    $newEmail = htmlspecialchars($_POST['email']);
    $newUsername = htmlspecialchars($_POST['username']);

    // Check if the password matches the logged-in user's password
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        if (password_verify($password, $hashedPassword)) {
            // Password is correct, update email and username
            if (!empty($newEmail)) {
                $stmt = $conn->prepare("UPDATE users SET email = ? WHERE username = ?");
                $stmt->bind_param("ss", $newEmail, $_SESSION['username']);
                $stmt->execute();
                $_SESSION['email'] = $newEmail;
            }
            if (!empty($newUsername)) {
                $stmt = $conn->prepare("UPDATE users SET username = ? WHERE username = ?");
                $stmt->bind_param("ss", $newUsername, $_SESSION['username']);
                $stmt->execute();
                $_SESSION['username'] = $newUsername;
            }
            // Redirect to profile page or display success message
            header("Location: profile.php");
            exit();
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "User not found";
    }
}
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

    <div class="container">
        <div class="title">User Profile</div>
        <div class="hello">Hello, <?php echo $_SESSION['username']; ?></div>
        <div class="nav-buttons">
            <button onclick="window.location.href='profile.php'">Dashboard</button>
            <button onclick="window.location.href='addevent.php'">Host an Event</button>
            <button onclick="window.location.href='accdetails.php'">Account Details</button>
            <button onclick="window.location.href='logout.php'">Log Out</button>
        </div>

        <div class="profile-info">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div>
                    <!-- <strong>Email :</strong> <?php echo $_SESSION['email']; ?> -->
                </div>
                <label for="email">Change Email:</label><br>
                <input type="email" id="email" name="email" value="<?php echo $_SESSION['email']; ?>"><br><br>

                <div>
                    <!-- <strong>Username :</strong> <?php echo $_SESSION['username']; ?> -->
                </div>
                <label for="username">Change Username:</label><br>
                <input type="text" id="username" name="username" value="<?php echo $_SESSION['username']; ?>"><br><br>

                <div>
                    <label for="password">Password:</label><br>
                    <input type="password" id="password" name="password" placeholder="Password"><br>
                    <?php if (isset($error)) echo "<span class='error'>$error</span>"; ?><br>
                </div>
                <input type="submit" value="Update">
            </form>
        </div>
    </div>

    <?php include 'footer.php' ?>
</body>

</html>