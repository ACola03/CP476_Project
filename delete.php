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
    The `Delete` functionality operates on the `Final Grades` table which was <br>
    previously presented to the user. This operation is used to drop rows from <br>
    this table based on the values of their features. For example, if a student <br>
    no longer attends the school, or a course is no longer offered, after some <br>
    period of time, these records are no longer needed and can be safely removed. 
</p>

<h3>Delete specific entries based on the following conditions:</h3>
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
</ol>

<h3>Provide your search query below:</h3>
<p>
    For simplicity, the features are handled separately.<br>
    Please submit a single entry for the fields you desire.
</p>

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
    <br><br>
    <input type="submit" value="Delete">
</form>

</body>
</html>

