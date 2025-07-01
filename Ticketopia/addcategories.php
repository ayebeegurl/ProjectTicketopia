<?php
require 'config.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $eventId = $_POST['eventId'];
    $categoryNames = $_POST['categoryName'];
    $quantities = $_POST['quantity'];
    $prices = $_POST['price'];

    // Prepare and execute the INSERT query for each category
    $stmt = $conn->prepare("INSERT INTO categories (category_name, quantity, price) VALUES (?, ?, ?)");
    $stmt->bind_param("sid", $categoryName, $quantity, $price);

    // Initialize success flag
    $success = true;

    // Execute the statement for adding categories
    for ($i = 0; $i < count($categoryNames); $i++) {
        $categoryName = $categoryNames[$i];
        $quantity = $quantities[$i];
        $price = $prices[$i];
        
        // Execute the statement
        if (!$stmt->execute()) {
            // If an error occurs, set success flag to false
            $success = false;
            // Output error message for debugging
            echo "Error: " . $stmt->error;
        }
    }

    // Close the first statement
    $stmt->close();

    // Prepare and execute the INSERT query for event categories
    $stmt2 = $conn->prepare("INSERT INTO event_categories (event_id, category_id) VALUES (?, ?)");
    $stmt2->bind_param("ii", $eventId, $categoryId);

    // Execute the statement for adding event categories
    foreach ($categoryNames as $categoryName) {
        // Retrieve category ID based on category name
        $sql = "SELECT category_id FROM categories WHERE category_name = ?";
        $stmt3 = $conn->prepare($sql);
        $stmt3->bind_param("s", $categoryName);
        $stmt3->execute();
        $result = $stmt3->get_result();
        $row = $result->fetch_assoc();
        $categoryId = $row['category_id'];

        // Execute the statement for adding event category
        if (!$stmt2->execute()) {
            // If an error occurs, set success flag to false
            $success = false;
            // Output error message for debugging
            echo "Error: " . $stmt2->error;
        }
    }

    // Close the second statement
    $stmt2->close();

    // Redirect to the home page if all insertions were successful
    if ($success) {
        header("Location: home.php");
        exit();
    } else {
        // If insertion fails, redirect to an error page or display an error message
        echo "Error occurred while adding categories. Please try again.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Categories</title>
<script>
  // Function to add new category input fields
  function addCategory() {
    var container = document.getElementById("categoryContainer");
    var newCategory = document.createElement("div");
    newCategory.innerHTML = `
      <label>Category Name:</label><br>
      <input type="text" name="categoryName[]" required><br><br>
      
      <label>Quantity:</label><br>
      <input type="number" name="quantity[]" required><br><br>

      <label>Price:</label><br>
      <input type="number" name="price[]" step="0.01" required><br><br>
    `;
    container.appendChild(newCategory);
  }
</script>
</head>
<body>
  <h2>Add Categories</h2>
  <form id="categoryForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <input type="hidden" name="eventId" value="<?php echo htmlspecialchars($_GET['eventId'] ?? ''); ?>">
    
    <div id="categoryContainer">
      <!-- Initial category input fields -->
      <div>
        <label>Category Name:</label><br>
        <input type="text" name="categoryName[]" required><br><br>
        
        <label>Quantity:</label><br>
        <input type="number" name="quantity[]" required><br><br>

        <label>Price:</label><br>
        <input type="number" name="price[]" step="0.01" required><br><br>
      </div>
    </div>

    <!-- Button to add more categories -->
    <button type="button" onclick="addCategory()">Add Category</button>
    <button type="submit">Done</button>
  </form>
</body>
</html>
