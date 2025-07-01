<?php
require 'config.php';
include "navbar.php";
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $select_user = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$user_id'");
}
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
    $select_event = mysqli_query($conn, "SELECT * FROM events WHERE event_id = '$event_id'");
    if (mysqli_num_rows($select_event) > 0) {
        $event_details = mysqli_fetch_assoc($select_event);
?>

        <body>
            <?php  ?>
            <div class="event">
                <img src="<?php echo $event_details['banner']; ?>" alt="<?php echo $event_details['event_name']; ?>" width="100%">
            </div>
            <div class="description">
                <h2><?php echo $event_details['event_name']; ?></h2>
                <br>
                <p><?php echo $event_details['description']; ?></p>
                <br>
            </div>
            <div class="seatplan">
                <img src="<?php echo $event_details['seatplan']; ?>" alt="<?php echo $event_details['event_name']; ?>" width="100%">
            </div>
            <form action="checkout.php" method="post">
                <div class="category">
                    <table>
                        <thead>
                            <tr>
                                <th colspan="3">Event Category</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $grand_total = 0;
                            $select_categories = mysqli_query($conn, "SELECT * FROM categories INNER JOIN event_categories ON categories.category_id = event_categories.category_id WHERE event_categories.event_id = '$event_id'");
                            while ($category = mysqli_fetch_assoc($select_categories)) {
                            ?>
                                <tr>
                                    <td colspan="2">
                                        <?php echo $category['category_name']; ?> <br>
                                        <?php echo $category['price']; ?>
                                    </td>
                                    <td>
                                        <select name="quantity[<?php echo $category['category_id']; ?>]" class="quantity-dropdown" data-category-id="<?php echo $category['category_id']; ?>">
                                            <?php for ($i = 0; $i <= 4; $i++) { ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <span class="subtotal" data-price="<?php echo $category['price']; ?>">0</span>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" align="right">Grand Total:</td>
                                <td id="grand-total">0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="checkout">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                    <input type="submit" name="update_cart" value="Order Now" />
                </div>
            </form>
            <br> <br>
            <hr>
            <br><br>
            <div class="tnc">
                <h2>Terms and Conditions</h2>
                <br> <br>
                <?php while ($event = mysqli_fetch_assoc($select_event)) { ?>
                    <ul>
                        <li> By purchasing a ticket for <?php echo $event['event_name']; ?>,
                            the Event Attendee has agreed to all applicable Terms and Conditions. All forms of violation of
                            the terms and conditions will be dealt with strictly.</li>
                        <!-- Other terms and conditions list items here -->
                    </ul>
                <?php } ?>
            </div>
            <?php include "footer.php"; ?>
            <script>
                function updateQuantity(element) {
                    var categoryId = element.getAttribute('data-category-id');
                    var quantity = element.value;
                    var quantityInputs = document.querySelectorAll('.category-quantity');

                    // Perbarui nilai quantity[] sesuai dengan nilai dropdown yang dipilih
                    for (var i = 0; i < quantityInputs.length; i++) {
                        if (quantityInputs[i].getAttribute('data-category-id') === categoryId) {
                            quantityInputs[i].value = quantity;
                        }
                    }
                }
            </script>
            <script>
                $(document).ready(function() {
                    // Function to update subtotal and grand total
                    function updateTotals() {
                        var grandTotal = 0;
                        $('.category tbody tr').each(function() {
                            var quantity = parseFloat($(this).find('.quantity-dropdown').val());
                            var price = parseFloat($(this).find('.subtotal').attr('data-price'));
                            var subtotal = price * quantity;
                            if (!isNaN(subtotal)) {
                                $(this).find('.subtotal').text(subtotal.toFixed(0));
                                grandTotal += subtotal; // Accumulate subtotal for grand total calculation
                            }
                        });
                        $('#grand-total').text(grandTotal.toFixed(0));
                    }
                    // Update totals on quantity change
                    $('.quantity-dropdown').change(function() {
                        // Limit the quantity to maximum 4
                        var quantity = $(this).val();
                        if (quantity > 4) {
                            $(this).val(4);
                            quantity = 4;
                        }
                        updateTotals();
                    });
                    // Call updateTotals() once on page load
                    updateTotals();
                });
            </script>
        </body>

        </html>
<?php
    } else {
        echo "<p>Event not found!</p>";
    }
} else {
    echo "<p>Event ID not specified!</p>";
}
?>
