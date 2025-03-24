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

if ($_SESSION["QueryAction"] == "Search") {
    $StudentID = isset($_POST["StudentID"]) ? trim(htmlspecialchars($_POST["StudentID"])) : "";
    $StudentName = isset($_POST["StudentName"]) ? trim(htmlspecialchars($_POST["StudentName"])) : "";
    $CourseCode = isset($_POST["CourseCode"]) ? trim(htmlspecialchars($_POST["CourseCode"])) : "";
    $FinalGrade = isset($_POST["FinalGrade"]) ? trim(htmlspecialchars($_POST["FinalGrade"])) : "";

    $queryString = "";
    $trueConditions = 0;
    $conditions = [];

    if (!empty($StudentID)) {
        $queryString .= $trueConditions == 0 ? " `Student ID` = ?" : " AND `Student ID` = ?";
        $conditions["StudentID"] = $StudentID;
        $trueConditions += 1;
    }

    if (!empty($StudentName)) {
        $queryString .= $trueConditions == 0 ? " `Student Name` = ?" : " AND `Student Name` = ?";
        $conditions["StudentName"] = $StudentName;
        $trueConditions += 1;
    }

    if (!empty($CourseCode)) {
        $queryString .= $trueConditions == 0 ? " `Course Code` = ?" : " AND `Course Code` = ?";
        $conditions["CourseCode"] = $CourseCode;
        $trueConditions += 1;
    }

    if (!empty($FinalGrade)) {
        $queryString .= $trueConditions == 0 ? " `Final Grade` >= ?" : " AND `Final Grade` >= ?";
        $conditions["FinalGrade"] = $FinalGrade;
        $trueConditions += 1;
    }

    $finalQuery = "SELECT * FROM final_grades";
    if (!empty($queryString)) {
        $finalQuery .= " WHERE" . $queryString;
    }

    $db = new PDO('mysql:host=localhost;dbname=cp476_project', 'root', 'pass123');
    $stmt = $db->prepare($finalQuery);
    $index = 1;

    foreach ($conditions as $key => $value) {
        $stmt->bindValue($index, $value, ($key === "FinalGrade" || $key === "StudentID") ? PDO::PARAM_INT : PDO::PARAM_STR);
        $index += 1;
    }

    $stmt->execute();
    $selectOutput = $stmt->fetchAll();
}

if ($_SESSION["QueryAction"] == "Delete") {
    $StudentID = isset($_POST["StudentID"]) ? trim(htmlspecialchars($_POST["StudentID"])) : "";
    $StudentName = isset($_POST["StudentName"]) ? trim(htmlspecialchars($_POST["StudentName"])) : "";
    $CourseCode = isset($_POST["CourseCode"]) ? trim(htmlspecialchars($_POST["CourseCode"])) : "";

    $queryString = "";
    $trueConditions = 0;
    $conditions = [];

    if (!empty($StudentID)) {
        $queryString .= $trueConditions == 0 ? " `Student ID` = ?" : " AND `Student ID` = ?";
        $conditions["StudentID"] = $StudentID;
        $trueConditions += 1;
    }

    if (!empty($StudentName)) {
        $queryString .= $trueConditions == 0 ? " `Student Name` = ?" : " AND `Student Name` = ?";
        $conditions["StudentName"] = $StudentName;
        $trueConditions += 1;
    }

    if (!empty($CourseCode)) {
        $queryString .= $trueConditions == 0 ? " `Course Code` = ?" : " AND `Course Code` = ?";
        $conditions["CourseCode"] = $CourseCode;
        $trueConditions += 1;
    }

    $db = new PDO('mysql:host=localhost;dbname=cp476_project', 'root', 'pass123');

    if (!empty($queryString)) {
        $finalQuery = "DELETE FROM final_grades WHERE" . $queryString;
        $stmt = $db->prepare($finalQuery);
        $index = 1;

        foreach ($conditions as $key => $value) {
            $stmt->bindValue($index, $value, ($key === "FinalGrade" || $key === "StudentID") ? PDO::PARAM_INT : PDO::PARAM_STR);
            $index += 1;
        }

        $stmt->execute();
    }

    $stmt2 = $db->prepare("SELECT * FROM final_grades");
    $stmt2->execute();
    $selectOutput = $stmt2->fetchAll();
}

if ($_SESSION["QueryAction"] == "Update") {
    $StudentID = isset($_POST["StudentID"]) ? trim(htmlspecialchars($_POST["StudentID"])) : "";
    $CourseCode = isset($_POST["CourseCode"]) ? trim(htmlspecialchars($_POST["CourseCode"])) : "";
    $Test1 = $_POST["Test1"];
    $Test2 = $_POST["Test2"];
    $Test3 = $_POST["Test3"];
    $Exam = $_POST["Exam"];

    $finalQuery = "UPDATE coursetable 
                   SET `Test 1` = ?, `Test 2` = ?, `Test 3` = ?, `Final Exam` = ? 
                   WHERE `Student ID` = ? AND `Course Code` = ?";

    $db = new PDO('mysql:host=localhost;dbname=cp476_project', 'root', 'pass123');
    $stmt = $db->prepare($finalQuery);

    $stmt->bindValue(1, $Test1, PDO::PARAM_INT);
    $stmt->bindValue(2, $Test2, PDO::PARAM_INT);
    $stmt->bindValue(3, $Test3, PDO::PARAM_INT);
    $stmt->bindValue(4, $Exam, PDO::PARAM_INT);
    $stmt->bindValue(5, $StudentID, PDO::PARAM_INT);
    $stmt->bindValue(6, $CourseCode, PDO::PARAM_STR);

    $stmt->execute();

    $stmt2 = $db->prepare("
    UPDATE final_grades FG
    JOIN (
        SELECT N.`Student ID`, C.`Course Code`, 
        (0.2 * `Test 1` + 0.2 * `Test 2` + 0.2 * `Test 3` + 0.4 * `Final Exam`) AS 'Final Grade'
        FROM cp476_project.coursetable C
        JOIN cp476_project.nametable N ON C.`Student ID` = N.`Student ID`
    ) AS NewGrades
    ON FG.`Student ID` = NewGrades.`Student ID` AND FG.`Course Code` = NewGrades.`Course Code`
    SET FG.`Final Grade` = NewGrades.`Final Grade`");

    $stmt2->execute();

    $stmt3 = $db->prepare("SELECT * FROM final_grades");
    $stmt3->execute();
    $selectOutput = $stmt3->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Query Results</title>
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
            overflow-x: auto;
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
    <h2>Query Results</h2>

    <h3>The Selected Observations Are As Follows:</h3>

    <table>
        <tr>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Course Code</th>
            <th>Final Grade</th>
        </tr>
        <?php foreach ($selectOutput as $column) { ?>
            <tr>
                <td><?php echo htmlspecialchars($column['Student ID']); ?></td>
                <td><?php echo htmlspecialchars($column['Student Name']); ?></td>
                <td><?php echo htmlspecialchars($column['Course Code']); ?></td>
                <td><?php echo htmlspecialchars($column['Final Grade']); ?></td>
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