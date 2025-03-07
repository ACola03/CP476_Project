<html>
    <h2>User Is Locked Out.</h2>
    <p>Too many login attempts, user is locked out. Please try again later.</p>
    <p>For Debugging, reset the browser cookies.</p>
</html>
<?php 
session_unset();
exit(); ?>