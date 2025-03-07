<?php
    # User must be first logged-in (would also cause loginAttempt issues)
    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location: index.php"); 
        exit();
    }

    # If they've exceeded their attempts no use showing the html page below
    if ($_SESSION["loginAttempts"] <=0) {
        header("Location: LockedOut.php");
        exit();
    }

?>

<html>
    <body>
        <h2> Invalid Credentials </h2>
        <p>
            You are seeing this page because you 
            entered an invalid username and/or password.
        </p>

        <p>
            Should you wish to retry to access the database, 
            return to the login page by clicking the anchor text below.
        </p>

        <p>
            User has <?php echo($_SESSION["loginAttempts"])?> attempts remaining.
        </p>

        <a href = "index.php">Return me to the login page!</a>

    </body>
</html>
