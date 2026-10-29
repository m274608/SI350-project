<?php
session_start();

if (!isset($_SESSION['email'])) {
    echo "Your not logged in";
    echo "<br><a href='login.html'>Login</a>";
    exit;
}

$date = $_GET['date'];

$file = fopen("LOG.txt", "r");
$headers = fgetcsv($file, 0, "\t");  // https://www.w3schools.com/php/func_filesystem_fgetcsv.asp //
$userMatch = [];

while (($user = fgetcsv($file, 0, "\t")) !== false) {
    if ($user[5] == $date) {
        $user[4] = "**";
        $userMatch[] = $user;
    }
}

fclose($file);

usort($userMatch, function ($user1, $user2) {   // https://www.php.net/manual/en/function.usort.php //
    return strcasecmp($user1[0], $user2[0]);   // https://www.w3schools.com/php/func_string_strcasecmp.asp //
});

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Availability Report</title>

    <style type="text/CSS">
        table, td, th {
            border: 1px solid black;
        }
    </style>
</head>

<body>

    <h1>Availability Report</h1>

    <p>
        Below are the <?php echo count($userMatch); ?>
        user submissions for <?php echo $date; ?> SMT,
        sorted by last name.
    </p>

    <table>
        <thead>
            <tr>
                <?php foreach ($headers as $heading): ?>
                    <th><?php echo $heading; ?></th>
                <?php endforeach; // https://www.w3schools.com/php/keyword_endforeach.asp // ?>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($userMatch as $user): ?>
                <tr>
                    <?php foreach ($user as $value): ?>
                        <td><?php echo $value; ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>