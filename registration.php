<?php
$message = "";
$file = "accounts.txt";
$board = $_GET["board"] ?? $_POST["board"] ?? 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (!file_exists($file) || filesize($file) == 0) {
        $header = implode("\t", ["email", "password"]) . PHP_EOL;
        file_put_contents($file, $header, FILE_APPEND | LOCK_EX);
    }

    $exists = false;

    foreach (file($file) as $line) {

        $data = explode("\t", trim($line));

        if (($data[0] ?? '') === "RENTAL" ||
            ($data[0] ?? '') === "email") {
            continue;
        }

        if (($data[0] ?? '') === $email) {
            $exists = true;
            break;
        }
    }

    if ($exists) {
        $message = "Account already exists!";
    } else {

        $log_entry = implode("\t", [
            $email,
            password_hash($password, PASSWORD_DEFAULT)
        ]) . PHP_EOL;

        file_put_contents($file, $log_entry, FILE_APPEND | LOCK_EX);

        header("Location: login.html");
        exit(); 
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
        <li class="nav-item">
        <a class="nav-link" href="/~m274608/SI350/project/index.html">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/~m274608/SI350/project/Surfboards.html">Surfboards</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/~m274608/SI350/project/login.html">Login</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/~m274608/SI350/project/logout.php">Logout</a>
      </li>
        <li class="nav-item">
        <a class="nav-link" href="/~m274608/SI350/project/contactus.html">Contact Us</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container mt-5">

    <h2>Create Account</h2>

    <p><?php echo htmlspecialchars($message); ?></p>

    <form method="POST">

        <input type="hidden" name="board" value="<?php echo (int)$board; ?>">

        <input type="email" name="email" placeholder="Email" class="form-control mb-3" required>

        <input type="password" name="password" placeholder="Password" class="form-control mb-3" required>

        <button class="btn btn-primary">Register</button>

    </form>

</div>

</body>
</html>