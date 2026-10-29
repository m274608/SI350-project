<?php
session_start();

$errors=[];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'] ?? '';
    $password = $_POST['pwd'] ?? '';

    if (empty($email)) {
        echo "Enter a email.";
        exit;
    }

    if (empty($password)) {
        echo "Enter a password.";
        exit;
    }

    $filepath = "LOG.txt";
    $fileLines = file($filepath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $isMatch = false;

    foreach ($fileLines as $line) {

    $fields = explode("\t", $line); //https://www.w3schools.com/php/func_string_explode.asp //

    $storedEmail = $fields[2] ?? '';
    $storedPassword = $fields[4] ?? '';

    if ($storedEmail === $email && $storedPassword === $password) {
        $isMatch = true;
        break;
    }
}

if ($isMatch) {
    $_SESSION['email'] = $email;
    header("Location: requestReport.php");
    exit;
    
} else {
    echo "Invalid email or password.";
    echo "<br><a href='login.html'>Try again</a>";
}
}
?>