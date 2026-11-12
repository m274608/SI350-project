
<?php
$file = "rentals.txt";
$email = trim($_POST["email"] ?? "");
$message = "";
$rentals = [];

$prices = [
    1 => 50,
    3 => 100,
    7 => 250
];

if (isset($_POST["extend"]) && file_exists($file)) {

    $lineNumber = (int)($_POST["line"] ?? -1);
    $extraDays = (int)($_POST["extraDays"] ?? 0);

    if (isset($prices[$extraDays])) {

        $lines = file($file);
        
        if (isset($lines[$lineNumber])) {

            $line = trim($lines[$lineNumber]);

            $pattern = '/^(\S+)\s+(\d+)\s+(.+?)\s+(\d+)\s+(Day|Days|Week|Weeks)\s+(\d{4}-\d{2}-\d{2})\s+(\d+(?:\.\d+)?)$/';

            if (preg_match($pattern, $line, $parts)) {

                $rentalEmail = $parts[1];
                $boardID = $parts[2];
                $boardName = $parts[3];
                $days = (int)$parts[4];
                $unit = $parts[5];
                $startDate = $parts[6];
                $price = (float)$parts[7];

                if ($unit == "Week" || $unit == "Weeks") {
                    $days = $days * 7;
                }

                if ($rentalEmail == $email) {

                    $days = $days + $extraDays;
                    $price = $price + $prices[$extraDays];

                    $lines[$lineNumber] =
                        $rentalEmail . " " .
                        $boardID . " " .
                        $boardName . " " .
                        $days . " Days " .
                        $startDate . " " .
                        $price . "\n";

                    if (file_put_contents($file, implode("", $lines), LOCK_EX) !== false) {
                        $message = "Rental extended successfully!";
                    } else {
                        $message = "Unable to update rental.";
                    }
                }
            }
        }
    }
}

if ($email != "" && file_exists($file)) {

    $lines = file($file);

    foreach ($lines as $index => $line) {

        $line = trim($line);

        $pattern = '/^(\S+)\s+(\d+)\s+(.+?)\s+(\d+)\s+(Day|Days|Week|Weeks)\s+(\d{4}-\d{2}-\d{2})\s+(\d+(?:\.\d+)?)$/';

        if (preg_match($pattern, $line, $parts)) {

            $rentalEmail = $parts[1];

            if ($rentalEmail == $email) {

                $days = (int)$parts[4];

                if ($parts[5] == "Week" || $parts[5] == "Weeks") {
                    $days = $days * 7;
                }

                $pickup = strtotime($parts[6] . " 09:00:00");

                $dropoff = strtotime("+" . $days . " days", $pickup);
                $dropoff = strtotime(date("Y-m-d", $dropoff) . " 08:00:00");

                $rentals[] = [
                    "line" => $index,
                    "id" => $parts[2],
                    "board" => $parts[3],
                    "days" => $days,
                    "price" => $parts[7],
                    "pickup" => date("F j, Y g:i A", $pickup),
                    "dropoff" => date("F j, Y g:i A", $dropoff)
                ];
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Account</title>

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
        <a class="nav-link" href="/~m274608/SI350/project/account.php">Account</a>
      </li>
        <li class="nav-item">
        <a class="nav-link" href="/~m274608/SI350/project/contactus.html">Contact Us</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container mt-5">

    <h2 class="text-center mb-4">My Rentals</h2>

    <form method="POST" class="mb-4">

        <label class="form-label">Enter your email:</label>

        <input type="text" name="email" class="form-control"
               value="<?php echo htmlspecialchars($email); ?>" required>

        <button type="submit" class="btn btn-primary mt-3">
            Find Rentals
        </button>

    </form>

    <?php
    if ($message != "") {
        echo '<div class="alert alert-info">' .
             htmlspecialchars($message) . '</div>';
    }
    ?>

    <?php
    if ($email != "" && count($rentals) == 0) {
        echo "<p>No rentals found for this email.</p>";
    }
    ?>

    <div class="row">

    <?php
    foreach ($rentals as $rental) {
    ?>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">

                    <h4>
                        <?php echo htmlspecialchars($rental["board"]); ?>
                    </h4>

                    <p>
                        Board ID:
                        <?php echo htmlspecialchars($rental["id"]); ?>
                    </p>

                    <p>
                        Pickup:
                        <?php echo $rental["pickup"]; ?>
                    </p>

                    <p>
                        Drop-off:
                        <?php echo $rental["dropoff"]; ?>
                    </p>

                    <p>
                        Duration:
                        <?php echo $rental["days"]; ?> Days
                    </p>

                    <p>
                        Total Price:
                        $<?php echo number_format($rental["price"], 2); ?>
                    </p>

                    <hr>

                    <h5>Extend Rental</h5>

                    <form method="POST">

                        <input type="hidden" name="email"
                               value="<?php echo htmlspecialchars($email); ?>">

                        <input type="hidden" name="line"
                               value="<?php echo $rental["line"]; ?>">

                        <select name="extraDays" class="form-select">
                            <option value="1">1 Day - $50</option>
                            <option value="3">3 Days - $100</option>
                            <option value="7">1 Week - $250</option>
                        </select>

                        <button type="submit" name="extend"
                                class="btn btn-success mt-3">
                            Extend Rental
                        </button>

                    </form>

                </div>
            </div>
        </div>

    <?php
    }
    ?>

    </div>

</div>

</body>
</html>
