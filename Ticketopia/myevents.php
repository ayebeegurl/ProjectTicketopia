<?php
require_once "config.php";
include 'navbar.php';
if (isset($_GET['message'])) {
    $message = $_GET['message'];
    echo '<div class="notification">' . $message . '</div>';
}
$sql = "SELECT t.ticket_id, u.username, e.event_name, c.category_name, t.quantity, t.purchase_date
        FROM tickets t
        INNER JOIN events e ON t.event_id = e.event_id
        INNER JOIN users u ON t.user_id = u.user_id
        INNER JOIN categories c ON t.category_id = c.category_id WHERE t.quantity != 0";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>My Events</title>
        <link rel="stylesheet" href="style.css">
        <style>
            .notification {
                background-color: #4CAF50;
                margin: 0 auto;
                max-width: 900px;
                color: white;
                text-align: center;
                padding: 9px;
                margin-bottom: 20px;
            }

            .delete-button {
                background-color: #f44336;
                color: white;
                border: none;
                padding: 4px 10px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .delete-button:hover {
                background-color: #d32f2f;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="title">My Events</div>
            <div class="hello">Your purchased items</div>

            <div class="events-list">
                <table>
                    <tr>
                        <th>Event Name</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Purchase Date</th>
                        <th>Aksi</th>
                    </tr>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <tr>
                            <td><?php echo $row['event_name']; ?></td>
                            <td><?php echo $row['category_name']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><?php echo date('Y-m-d', strtotime($row['purchase_date'])); ?></td>
                            <td>
                                <form action="delete.php" method="post">
                                    <input type="hidden" name="ticket_id" value="<?php echo $row['ticket_id']; ?>">
                                    <input class="delete-button" type="submit" value="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus tiket ini?');">
                                </form>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>

        <?php include "footer.php"; ?>
    </body>

    </html>
<?php
} else {
    echo "<p>No events found.</p>";
}

mysqli_close($conn);
?>