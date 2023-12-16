<?php
include('db_conn.php');


$functionCall = "SELECT dbfunct() AS result";

$result = $conn->query($functionCall);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<p>Total Employees: ' . $row['result'] . '</p>';
    }
} else {
    echo 'No results found.';
}

$conn->close();
?>
