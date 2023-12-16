<?php
include('db_conn.php');

$createProcedureSQL = "
DELIMITER //

CREATE PROCEDURE proc()
BEGIN
    SELECT emp_no, first_name, last_name
    FROM employees
    WHERE gender = 'M';
END //

DELIMITER ;
";

if ($conn->multi_query($createProcedureSQL)) {
    echo 'Stored procedure created successfully.';
} else {
    echo 'Error creating stored procedure: ' . $conn->error;
}

$conn->close();
?>
