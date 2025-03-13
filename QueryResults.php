<?php
    session_start();

    // User must be logged in
    if (!isset($_SESSION['username'])) {
        header("Location: index.php"); // Redirect if not logged in
        exit();
    }

    # In case they access the file directly through localhost
    if ($_SESSION["loginAttempts"] <=0) {
        header("Location: LockedOut.php");
        exit();
    }

    
    if ($_SESSION["QueryAction"] == "Search"){
        # I should probably specify the types for each (after specialChars?)
        $StudentID = isset($_POST["StudentID"]) ? trim(htmlspecialchars($_POST["StudentID"])) : "";
        $StudentName = isset($_POST["StudentName"]) ? trim(htmlspecialchars($_POST["StudentName"])) : "";
        $CourseCode = isset($_POST["CourseCode"]) ? trim(htmlspecialchars($_POST["CourseCode"])) : "";
        $FinalGrade = isset($_POST["FinalGrade"]) ? trim(htmlspecialchars($_POST["FinalGrade"])) : "";

        # now merge/concatenate the statements to form an entire predicate (WHERE)
        
        # prepare to create the predicate
        $queryString = "";
        $trueConditions = 0;
        $conditions = [];

        # =============================================
        # /////////////////////////////////////////////

        # only add if the string isn't empty
        if (!empty($StudentID)) {

            # First condition (no preceeding AND)
            if ($trueConditions == 0){
                $queryString = $queryString." `Student ID` = ?";
            }

            # This isn't needed but just for consistency
            else{
                $queryString = $queryString." AND `Student ID` = ?";
            }

            $conditions["StudentID"] = $StudentID; 
            $trueConditions += 1;

        }

        if (!empty($StudentName)) {
            # First condition (no preceeding AND)
            if ($trueConditions == 0){
                $queryString = $queryString." `Student Name` = ?";
            }
            
            # If the preceeding features had specified conditions 
            else{
                $queryString = $queryString." AND `Student Name` = ?";
            }

            $conditions["StudentName"] = $StudentName; 
            $trueConditions += 1;
        }
        if (!empty($CourseCode)) {
            if ($trueConditions == 0){
                $queryString = $queryString." `Course Code` = ?";
            }
            
            # If the preceeding features had specified conditions 
            else{
                $queryString = $queryString." AND `Course Code` = ?";
            }

            $conditions["CourseCode"] = $CourseCode; 
            $trueConditions += 1;
        }
        if (!empty($FinalGrade)) {
            if ($trueConditions == 0){
                $queryString = $queryString." `Final Grade` >= ?";
            }
            
            # If the preceeding features had specified conditions 
            else{
                $queryString = $queryString." AND `Final Grade` >= ?";
            }

            $conditions["FinalGrade"] = $FinalGrade; 
            $trueConditions += 1; // Example for range (could be changed based on needs)
        }
        # /////////////////////////////////////////////
        # =============================================
        # \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

        $finalQuery = "SELECT * FROM final_grades";
        if (!empty($queryString)) {
            $finalQuery .= " WHERE" . $queryString;
        }

        $db = new PDO('mysql:host=localhost;dbname=cp476_project', 'root', 'Lahonto123#');
        $stmt = $db->prepare($finalQuery);
        $index = 1;

       foreach($conditions as $key => $value){
            if ($key === "FinalGrade" || $key === "StudentID"){
                $stmt -> bindValue($index, $value, PDO::PARAM_INT);
            }
            else{
                $stmt -> bindValue($index, $value, PDO::PARAM_STR);
            }
            $index += 1;
       }

       $stmt->execute();
       $selectOutput = $stmt->fetchAll();
    }

    if ($_SESSION["QueryAction"] == "Delete"){
        # I should probably specify the types for each (after specialChars?)
        $StudentID = isset($_POST["StudentID"]) ? trim(htmlspecialchars($_POST["StudentID"])) : "";
        $StudentName = isset($_POST["StudentName"]) ? trim(htmlspecialchars($_POST["StudentName"])) : "";
        $CourseCode = isset($_POST["CourseCode"]) ? trim(htmlspecialchars($_POST["CourseCode"])) : "";

        # now merge/concatenate the statements to form an entire predicate (WHERE)
        
        # prepare to create the predicate
        $queryString = "";
        $trueConditions = 0;
        $conditions = [];

        # =============================================
        # /////////////////////////////////////////////

        # only add if the string isn't empty
        if (!empty($StudentID)) {

            # First condition (no preceeding AND)
            if ($trueConditions == 0){
                $queryString = $queryString." `Student ID` = ?";
            }

            # This isn't needed but just for consistency
            else{
                $queryString = $queryString." AND `Student ID` = ?";
            }

            $conditions["StudentID"] = $StudentID; 
            $trueConditions += 1;

        }

        if (!empty($StudentName)) {
            # First condition (no preceeding AND)
            if ($trueConditions == 0){
                $queryString = $queryString." `Student Name` = ?";
            }
            
            # If the preceeding features had specified conditions 
            else{
                $queryString = $queryString." AND `Student Name` = ?";
            }

            $conditions["StudentName"] = $StudentName; 
            $trueConditions += 1;
        }
        if (!empty($CourseCode)) {
            if ($trueConditions == 0){
                $queryString = $queryString." `Course Code` = ?";
            }
            
            # If the preceeding features had specified conditions 
            else{
                $queryString = $queryString." AND `Course Code` = ?";
            }

            $conditions["CourseCode"] = $CourseCode; 
            $trueConditions += 1;
        }
        
        # /////////////////////////////////////////////
        # =============================================
        # \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


        # if the string is empty we don't want to delete the entire table
        $db = new PDO('mysql:host=localhost;dbname=cp476_project', 'root', 'Lahonto123#');

        if (!empty($queryString)) {

            $finalQuery = "DELETE FROM final_grades";
            $finalQuery .= " WHERE" . $queryString;

            
            $stmt = $db->prepare($finalQuery);
            $index = 1;

            foreach($conditions as $key => $value){
                if ($key === "FinalGrade" || $key === "StudentID"){
                    $stmt -> bindValue($index, $value, PDO::PARAM_INT);
                }
                else{
                    $stmt -> bindValue($index, $value, PDO::PARAM_STR);
                }
                $index += 1;
            }

            # delete the rows
            $stmt->execute();
        }

       # return the resulting dataset 
       $stmt2 = $db->prepare("SELECT * FROM final_grades");
       $stmt2->execute();
       $selectOutput = $stmt2->fetchAll();
    }

    if ($_SESSION["QueryAction"] == "Update"){
        # I should probably specify the types for each (after specialChars?)
        $StudentID = isset($_POST["StudentID"]) ? trim(htmlspecialchars($_POST["StudentID"])) : "";
        $CourseCode = isset($_POST["CourseCode"]) ? trim(htmlspecialchars($_POST["CourseCode"])) : "";
        $Test1 = $_POST["Test1"];
        $Test2 = $_POST["Test2"];
        $Test3 = $_POST["Test3"];
        $Exam =  $_POST["Exam"];

        # =============================================
        # /////////////////////////////////////////////

        # Update the table

        $finalQuery = "UPDATE coursetable 
                       SET `Test 1` = ?, `Test 2` = ?, `Test 3` = ?, `Final Exam` = ? 
                       WHERE `Student ID` = ? AND `Course Code` = ? ";

        $db = new PDO('mysql:host=localhost;dbname=cp476_project', 'root', 'Lahonto123#');
        $stmt = $db->prepare($finalQuery);
        
        $stmt->bindValue(1, $Test1, type: PDO::PARAM_INT);
        $stmt->bindValue(2, $Test2, type: PDO::PARAM_INT);
        $stmt->bindValue(3, $Test3, type: PDO::PARAM_INT);
        $stmt->bindValue(4, $Exam, type: PDO::PARAM_INT);
        $stmt->bindValue(5, $StudentID, type: PDO::PARAM_INT);
        $stmt->bindValue(6, $CourseCode, type: PDO::PARAM_STR);

       $stmt->execute();
    
        # =============================================
        # /////////////////////////////////////////////

        # Populate final_grades
        $stmt2 = $db -> prepare("
        UPDATE final_grades FG
        JOIN (
            SELECT N.`Student ID`, C.`Course Code`, 
            (0.2 * `Test 1` + 0.2 * `Test 2` + 0.2 * `Test 3` + 0.4 * `Final Exam`) AS 'Final Grade'
            FROM cp476_project.coursetable C
            JOIN cp476_project.nametable N ON C.`Student ID` = N.`Student ID`
        ) AS NewGrades
        ON FG.`Student ID` = NewGrades.`Student ID` AND FG.`Course Code` = NewGrades.`Course Code`
        SET FG.`Final Grade` = NewGrades.`Final Grade`");

        $stmt2 -> execute();

        # Generate final grades (for display)
        $stmt3 = $db -> prepare("SELECT * FROM final_grades");
        $stmt3 -> execute();
        $selectOutput = $stmt3->fetchAll();

    }

    # =======================================================
    ?>

<h3>The Selected Observations Are As Follows: </h3>

<table border="1">
        <tr>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Course Code</th>
            <th>Final Grade</th>
        </tr>
        <?php foreach ($selectOutput as $column){ ?>
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
    