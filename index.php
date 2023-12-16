<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">

    <title>Database Queries</title>
</head>
<body>
    <h1>Database Queries</h1>

    <form method="post">
        <label for="querySelector">Select Filter:</label>
        <select id="querySelector" name="query">
            <option value="join">Join</option>
            <option value="leftJoin">Left Join</option>
            <option value="rightJoin">Right Join</option>
            <option value="subquery">Subquery</option>
            <option value="procedure">Stored Procedure</option>
            <option value="function">Stored Function</option>
        </select>
        <input type="submit" value="Filter">
    </form>

    <?php
    include('db_conn.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $selectedQuery = $_POST['query'];

        switch ($selectedQuery) {
            case 'join':
                $query = "SELECT users.username, orders.total_amount
                          FROM users
                          INNER JOIN orders ON users.id = orders.user_id";

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

                break;

            case 'leftJoin':
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

                break;

            case 'rightJoin':
                $query = "SELECT users.username, orders.total_amount
                          FROM users
                          RIGHT JOIN orders ON users.id = orders.user_id";

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

                break;

            case 'subquery':
                $query = "SELECT username, total_amount
                          FROM (
                              SELECT users.username, orders.total_amount
                              FROM users
                              INNER JOIN orders ON users.id = orders.user_id
                          ) AS subquery";

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

                break;

            case 'procedure':
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

                $procedureCall = "CALL proc()";

                if ($conn->multi_query($procedureCall)) {
                    do {
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

                break;

            case 'function':
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

                $functionCall = "SELECT dbfunct() AS result";

                $result = $conn->query($functionCall);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<p>Total Employees: ' . $row['result'] . '</p>';
                    }
                } else {
                    echo 'No results found.';
                }

                break;

            default:
                echo 'Invalid query selection';
        }
    }

    $conn->close();
    ?>

</body>
</html>
