<?php
include('db_conn.php');

$procedureCall = "CALL proc()";

if ($conn->multi_query($procedureCall)) {
    do {
        // Store result set
        if ($result = $conn->store_result()) {
            while ($row = $result->fetch_assoc()) {
                echo '<p>' . $row['emp_no'] . ' - ' . $row['first_name'] . ' ' . $row['last_name'] . '</p>';
            }
            $result->free();
        }
    } while ($conn->more_results() && $conn->next_result());
} else {
    echo 'Error calling stored procedure: ' . $conn->error;
}

$conn->close();
?>
