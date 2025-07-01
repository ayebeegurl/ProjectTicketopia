<?php
include "navbar.php";
require 'config.php';


// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["username"])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Check if user is authorized to make payment (optional)
// Add your authorization logic here

// Check if event_id is set
if (isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];

    // Select event details
    $select_event = mysqli_query($conn, "SELECT * FROM events WHERE event_id = '" . mysqli_real_escape_string($conn, $event_id) . "'");

    // Check if the event exists
    if (mysqli_num_rows($select_event) > 0) {
        $event_details = mysqli_fetch_assoc($select_event);
    }
}

if (isset($_POST['submit'])) {
    $event_id = $_POST['event_id'];
    $user_id = $_SESSION['user_id'];
    $payment_method = $_POST['payment_method'];
    $card_number = $_POST['card_number'];
    $expiry_date = $_POST['expiry_date'];

    // Insert payment information into payments table
    $sql_payment = "INSERT INTO payments (user_id, event_id, payment_method, card_number, expiry_date) VALUES (?, ?, ?, ?, ?)";

    // Prepare statement for payment insertion
    $stmt_payment = $conn->prepare($sql_payment);
    $stmt_payment->bind_param("iisss", $user_id, $event_id, $payment_method, $card_number, $expiry_date);

    // Execute payment insertion
    if ($stmt_payment->execute()) {

        // Loop through category_id and quantity arrays
        $quantities = $_POST['quantity'] ?? [];
        foreach ($quantities as $category_id => $quantity) {

            // Insert purchase information into purchases table
            $sql_purchase = "INSERT INTO tickets (user_id, event_id, category_id, quantity) VALUES (?, ?, ?, ?)";
            // Prepare statement for purchase insertion
            $stmt_purchase = $conn->prepare($sql_purchase);
            $stmt_purchase->bind_param("iiii", $user_id, $event_id, $category_id, $quantity);
            // Execute purchase insertion
            $stmt_purchase->execute();
        }

        // Redirect to success page
        header("Location: successful.php");
        exit();
    } else {
        // Handle payment insertion error
        header("Location: error.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Checkout</title>
</head>

<body>

    <h2>Checkout</h2>
    <div class="container">
        <div class="event-details">
            <h3><?php echo $event_details['event_name']; ?></h3>
            <p>Date: <?php echo $event_details['event_date_time']; ?></p>
            <!-- Add more event details here as needed -->
            <!-- Tambahkan input fields tersembunyi untuk menyimpan category_id dan quantity -->

        </div>
        <div class="payment-form">
            <h3>Payment Information</h3>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($_POST['event_id'] ?? $event_id); ?>">
                <?php foreach (($_POST['quantity'] ?? []) as $key => $value): ?>
                    <input type="hidden" name="quantity[<?php echo $key; ?>]" value="<?php echo $value; ?>">
                <?php endforeach; ?>
                <!-- Add payment method options (e.g., credit card, bank transfer) here -->
                <div class="form-group">
                    <label for="payment-method">Payment Method:</label>
                    <select name="payment_method" id="payment-method" required>
                        <option value="credit_card">Credit Card</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <!-- Add more options here -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="card-number">Card Number:</label>
                    <input type="text" name="card_number" id="card-number" required>
                </div>
                <div class="form-group">
                    <label for="expiry-date">Expiry Date:</label>
                    <input type="text" name="expiry_date" id="expiry-date" required>
                </div>
                <!-- Upload proof of transfer if payment method is bank transfer -->
                <div class="form-group" id="proof-of-transfer" style="display: none;">
                    <label for="proof">Proof of Transfer:</label>
                    <input type="file" name="proof" id="proof">
                </div>
                <div class="form-btn">
                    <input type="submit" value=" Complete Payment " name="submit">
                </div>
            </form>
        </div>
    </div>

    <!-- Include footer if needed -->
    <?php include "footer.php"; ?>
</body>

</html>
