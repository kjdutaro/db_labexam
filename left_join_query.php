<?php
include('db_conn.php');

$query = "SELECT users.username, orders.total_amount
          FROM users
          LEFT JOIN orders ON users.id = orders.user_id";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo '<ul>';
    while ($row = $result->fetch_assoc()) {
        echo '<li>' . $row['username'] . ' - ' . $row['total_amount'] . '</li>';
    }
    echo '</ul>';
} else {
    echo 'No results found.';
}

$conn->close();
?>
