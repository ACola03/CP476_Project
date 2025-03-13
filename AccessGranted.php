<?php
    session_start();

    // User must be logged in
    if (!isset($_SESSION['username'])) {
        header("Location: index.php"); // Redirect if not logged in
        exit();
    }

    // In case they access the file directly through localhost
    if ($_SESSION["loginAttempts"] <= 0) {
        header("Location: LockedOut.php");
        exit();
    }

    // Generate final grades (for display)
    try {
        $db = new PDO('mysql:host=localhost;dbname=cp476_project', 'root', 'pass123');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt1 = $db->prepare("
            SELECT N.`Student ID`, N.`Student Name`, C.`Course Code`, C.`Final Grade`
            FROM (
                SELECT `Student ID`, `Course Code`, 
                (0.2 * `Test 1` + 0.2 * `Test 2` + 0.2 * `Test 3` + 0.4 * `Final Exam`) as 'Final Grade'
                FROM coursetable
            ) AS C
            JOIN nametable N ON C.`Student ID` = N.`Student ID`
            ORDER BY N.`Student ID`
        ");
        $stmt1->execute();

        // Store the table
        $grades = $stmt1->fetchAll();

        // Create the final grades table
        $stmt2 = $db->prepare("CREATE TABLE IF NOT EXISTS final_grades (
            `Student ID` INT NOT NULL,
            `Student Name` VARCHAR(255) NOT NULL,
            `Course Code` VARCHAR(5) NOT NULL,
            `Final Grade` DECIMAL(5,1) NOT NULL,
            PRIMARY KEY (`Student ID`, `Course Code`)
        )");
        $stmt2->execute();

        // Populate final_grades
        $stmt3 = $db->prepare("
            INSERT IGNORE INTO final_grades (`Student ID`, `Student Name`, `Course Code`, `Final Grade`)
            SELECT N.`Student ID`, N.`Student Name`, C.`Course Code`, C.`Final Grade`
            FROM (
                SELECT `Student ID`, `Course Code`, 
                (0.2 * `Test 1` + 0.2 * `Test 2` + 0.2 * `Test 3` + 0.4 * `Final Exam`) as 'Final Grade'
                FROM cp476_project.coursetable
            ) AS C
            JOIN cp476_project.nametable N ON C.`Student ID` = N.`Student ID`
            ORDER BY N.`Student ID`
        ");
        $stmt3->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Granted</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 800px;
            text-align: center;
        }
        .container h2 {
            margin-bottom: 20px;
        }
        .container table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .container table, .container th, .container td {
            border: 1px solid #ccc;
        }
        .container th, .container td {
            padding: 10px;
            text-align: left;
        }
        .container th {
            background-color: #f2f2f2;
        }
        .container ul {
            list-style-type: none;
            padding: 0;
        }
        .container ul li {
            display: inline;
            margin-right: 10px;
        }
        .container ul li a {
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }
        .container ul li a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Access Granted, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>

    <h3>The Final Grades Table is as follows:</h3>

    <table>
        <tr>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Course Code</th>
            <th>Final Grade</th>
        </tr>
        <?php foreach ($grades as $grade) { ?>
            <tr>
                <td><?php echo htmlspecialchars($grade['Student ID']); ?></td>
                <td><?php echo htmlspecialchars($grade['Student Name']); ?></td>
                <td><?php echo htmlspecialchars($grade['Course Code']); ?></td>
                <td><?php echo htmlspecialchars($grade['Final Grade']); ?></td>
            </tr>
        <?php } ?>
    </table>

    <h3>Choose an action to operate on the database:</h3>
    <ul>
        <li><a href="search.php">Search</a></li>
        <li><a href="update.php">Update</a></li>
        <li><a href="delete.php">Delete</a></li>
    </ul>
</div>

</body>
</html>