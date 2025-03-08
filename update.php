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
$_SESSION['QueryAction'] = "Update";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Course Grades</title>
</head>
<body>

<h2>Course Table: Update Functionality</h2>
<p>
    This webpage provides the ability to update existing student <br>
    grades within `Course Table`. For a specific course code and <br>
    student identification number, you are able to update the scores <br>
    for each of their assessments (Tests 1-3 & Final Exam). Should a <br>
    grade be outside the bounds of 0-100, they will be truncated to fit <br>
    within the desirable range. 

</p>

<h3>Update specific entries based on the following conditions:</h3>
<ol>
    <li>Student ID: the student's identification number</li>
    <ul>
        <li>For example: WHERE `Student ID` = 154102471</li>
    </ul>
    <br>
    
    <li>Course Code: the course identifier</li>
    <ul>
        <li>For example: WHERE `Course Code` = "CP465"</li>
    </ul>
    <br>
    <li>Tests & Final Exam: the grades to update</li>
    <ul>
        <li>Enter them according to the format below</li>
    </ul>

</ol>

<h3>Provide your search query below:</h3>
<p>For simplicity, the features are handled separately.<br>
Please enter the test scores as integers.</p>

<form action="QueryResults.php" method="POST">
    <label>
        Student ID: <input type="text" name="StudentID" required>
    </label>
    <br>

    <label>
        Course Code: <input type="text" name="CourseCode" required>
    </label>
    <br>

    <label>
        Test 1: <input type="number" name="Test1" min="0" max ="100" required>
    </label>
    <br>
    
    <label>
        Test 2: <input type="number" name="Test2" min="0" max ="100" required>
    </label>
    <br>

    <label>
        Test 3: <input type="number" name="Test3" min="0" max ="100" required>
    </label>
    <br>

    <label>
        Final Exam: <input type="number" name="Exam" min="0" max ="100" required>
    </label>
    <br>

    <br>
    <input type="submit" value="Update">
</form>

</body>
</html>