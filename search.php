<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Prevent access if locked out
if ($_SESSION["loginAttempts"] <= 0) {
    header("Location: LockedOut.php");
    exit();
}

// Set QueryAction for this page
$_SESSION['QueryAction'] = "Search";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Final Grades</title>
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
        .search-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 500px;
            text-align: center;
        }
        .search-container h2 {
            margin-bottom: 20px;
        }
        .search-container p {
            text-align: left;
        }
        .search-container ol {
            text-align: left;
        }
        .search-container form {
            text-align: left;
        }
        .search-container label {
            display: block;
            margin-bottom: 5px;
        }
        .search-container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .search-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        .search-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="search-container">
    <h2>Final Grade: Search Functionality</h2>
    <p>
        The resulting query will be based on the Final Grades table. For clarity, <br>
        all columns will be displayed since the combination of Student ID and <br>
        Course Code is the primary key. Without these features, and the mention <br>
        of Student Name, the results can, and will be misleading. <br>
    </p>

    <h3>Search for specific entries based on the following conditions:</h3>
    <ol>
        <li>Student ID: the student's identification number</li>
        <ul>
            <li>For example: WHERE `Student ID` = 154102471</li>
        </ul>
        <br>
        
        <li>Student Name: the student's first & last name</li>
        <ul>
            <li>For example: WHERE `Student Name` = "James Andersen"</li>
        </ul>
        <br>

        <li>Course Code: the course identifier</li>
        <ul>
            <li>For example: WHERE `Course Code` = "CP465"</li>
        </ul>
        <br>

        <li>Final Grade: the student's final grade associated with that course</li>
        <ul>
            <li>For example: WHERE `Final Grade` >= 55</li>
        </ul>
    </ol>

    <h3>Provide your search query below:</h3>
    <p>For simplicity, the features are handled separately.</p>

    <form action="QueryResults.php" method="POST">
        <label>
            Student ID: <input type="text" name="StudentID">
        </label>
        <br>
        <label>
            Student Name: <input type="text" name="StudentName">
        </label>
        <br>
        <label>
            Course Code: <input type="text" name="CourseCode">
        </label>
        <br>
        <label>
            Final Grade: <input type="text" name="FinalGrade">
        </label>
        <br><br>
        <input type="submit" value="Search">
    </form>
</div>

</body>
</html>