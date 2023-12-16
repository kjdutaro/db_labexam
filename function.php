<?php
include('db_conn.php');

$createFunctionSQL = "
DELIMITER //

CREATE FUNCTION dbfunct()
RETURNS INT
BEGIN
    DECLARE totalEmployees INT;

    SELECT COUNT(*) INTO totalEmployees FROM employees;

    RETURN totalEmployees;
END //

DELIMITER ;
";

if ($conn->multi_query($createFunctionSQL)) {
    echo 'Stored function created successfully.';
} else {
    echo 'Error creating stored function: ' . $conn->error;
}

$conn->close();
?>
