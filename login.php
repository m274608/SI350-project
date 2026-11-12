<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo "Enter an email and password.";
        exit;
    }

    $filepath = "accounts.txt";

    if (!file_exists($filepath)) {
        echo "No accounts found.";
        exit;
    }

    $fileLines = file($filepath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $isMatch = false;

    foreach ($fileLines as $line) {

        $fields = explode("\t", $line);

        if (($fields[0] ?? '') === "email" ||
            ($fields[0] ?? '') === "RENTAL") {
            continue;
        }

        $storedEmail = $fields[0] ?? '';
        $storedPassword = $fields[1] ?? '';

        if ($storedEmail === $email &&
            password_verify($password, $storedPassword)) {

            $isMatch = true;
            break;
        }
    }

    if ($isMatch) {

        session_regenerate_id(true);
        $_SESSION['email'] = $email;

        header("Location: rent.php");
        exit;

    } else {

        echo "Invalid email or password.";
        echo "<br><a href='login.html'>Try again</a>";
    }
}
?>