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
$_SESSION['QueryAction'] = "Delete";

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Final Grades</title>
</head>
<body>

<h2>Final Grade: Delete Functionality</h2>
<p>
    The `Delete` functionality

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

</body>

<h3>NOTE: add the security checks (username set, etc..)</h3>

</html>

