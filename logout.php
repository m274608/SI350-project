<?php
session_start();

if (!isset($_SESSION['email'])) {
    echo "Your not logged in";
    echo "<br><a href='login.html'>Login</a>";
    exit;
}

unset($_SESSION['email']);

echo "You have been logged out.";
echo "<br><a href='login.html'>Go to Login</a>";
?>



