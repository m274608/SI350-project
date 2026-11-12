<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit();
}

$errors = [];
$success = false;

$boards = [
    1 => "Shortboard",
    2 => "Longboard",
    3 => "Phish",
    4 => "Funboard",
    5 => "Gun",
    6 => "Long Phish",
    7 => "Foam Board",
    8 => "Performance Board",
    9 => "Mid-size Board"
];
// https://www.php.net/manual/en/language.types.array.php

$prices = [
    "1 Day" => 50,
    "3 Days" => 100,
    "1 Week" => 250
];

$board = $_POST['board'] ?? ($_GET['board'] ?? '');
$time = $_POST['time'] ?? '';
$date = $_POST['date'] ?? '';
$datetime = DateTime::createFromFormat('!Y-m-d', $date);
// https://www.geeksforgeeks.org/php/php-datetime-createfromformat-function/

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($boards[$board])) {
        $errors[] = "Select a surfboard!";
    }

    if (!isset($prices[$time])) {
        $errors[] = "Select rental length!";
    }

    if (empty($date) || !$datetime
        || $datetime->format('Y-m-d') !== $date
        || $date < date('Y-m-d')) {
        $errors[] = "Select a valid future rental date!";
    }

    if (empty($errors)) {

        $boardName = $boards[$board];
        $price = $prices[$time];

        $file = "rentals.txt";


    if (!file_exists($file) || filesize($file) == 0) {

        $header = implode("\t", [
            "email",
            "board_id",
            "board_name",
            "duration",
            "start_date",
            "price"
        ]) . PHP_EOL;

        file_put_contents($file, $header, FILE_APPEND | LOCK_EX);
    }

    $log_entry = implode("\t", [
        $_SESSION['email'],
        $board,
        $boardName,
        $time,
        $date,
        $price
    ]) . PHP_EOL;

    if (file_put_contents($file, $log_entry, FILE_APPEND | LOCK_EX) !== false) {
        $success = true;
    } else {
        $errors[] = "Unable to save rental.";
    }

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Surfboard Rentals</title>

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

    <h2 class="text-center mb-4">Rent a Surfboard</h2>

    <?php if (!empty($errors)) { ?>

        <div class="alert alert-danger">
            <?php foreach ($errors as $error) { ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php } ?>
        </div>

    <?php } ?>

    <?php if ($success) { ?>

        <div class="alert alert-success">
            <h3>Rental Confirmed!</h3>
            <p><b>Board:</b> <?php echo htmlspecialchars($boardName); ?></p>
            <p><b>Board ID:</b> <?php echo htmlspecialchars((string)$board); ?></p>
            <p><b>Rental Length:</b> <?php echo htmlspecialchars($time); ?></p>
            <p><b>Date:</b> <?php echo htmlspecialchars($date); ?></p>
            <p><b>Total Price:</b> $<?php echo $price; ?></p>
            <a href="Surfboards.html" class="btn btn-primary">Back to Surfboards</a>
        </div>

    <?php } else { ?>

    <form method="POST" action="rent.php">

        <h5>Select a Surfboard:</h5>

        <?php foreach ($boards as $id => $name) { ?>

            <div class="form-check">

                <input class="form-check-input"
                       type="radio"
                       name="board"
                       id="board<?php echo $id; ?>"
                       value="<?php echo $id; ?>"
                       <?php if ((string)$board === (string)$id) echo 'checked'; ?>
                       required>

                <label class="form-check-label" for="board<?php echo $id; ?>">
                    <?php echo htmlspecialchars($name); ?>
                </label>

            </div>

        <?php } ?>

        <hr>

        <h5>Length of Rental:</h5>

        <div class="form-check">
            <input class="form-check-input" type="radio"
                   name="time" value="1 Day" required
                   <?php if ($time === "1 Day") echo 'checked'; ?>>
            <label class="form-check-label">1 Day - $50</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="radio"
                   name="time" value="3 Days"
                   <?php if ($time === "3 Days") echo 'checked'; ?>>
            <label class="form-check-label">3 Days - $100</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="radio"
                   name="time" value="1 Week"
                   <?php if ($time === "1 Week") echo 'checked'; ?>>
            <label class="form-check-label">1 Week - $250</label>
        </div>

        <hr>

        <h5>Rental Start Date:</h5>

        <input type="date"
               class="form-control mb-4"
               name="date"
               min="<?php echo date('Y-m-d'); ?>"
               value="<?php echo htmlspecialchars($date); ?>"
               required>

        <button type="submit" class="btn btn-primary">
            Confirm Rental
        </button>

        <button type="reset" class="btn btn-secondary">
            Reset
        </button>

    </form>

    <?php } ?>

</div>

</body>
</html>