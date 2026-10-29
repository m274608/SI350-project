<?php
$errors=[];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$name = $_POST['textex'] ?? '';
$email = $_POST['email'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['pwd'] ?? '';
$message = $_POST['message'] ?? '';
$date = $_POST['date'] ?? '';
$radiobox = $_POST['radioex'] ?? '';
$checkbox = $_POST['box'] ?? [];


if (empty($name)) {
    $errors[] = "Fill in name!";
}

if (empty($username)) {
    $errors[] = "Type username!";
}


if (empty($checkbox)) {
    $errors[] = "Check a box!";
}

if (empty($radiobox)) {
    $errors[] = "Select rank!";
}

if (!preg_match('/[A-Z]/', $password)) {
    $errors[] = "Password must contain at least one uppercase letter.";
}

if (!preg_match('/[a-z]/', $password)) {
    $errors[] = "Password must contain at least one lowercase letter.";
}

if (!preg_match('/\d/', $password)) {
    $errors[] = "Password must contain at least one digit.";
}

$file = 'LOG.txt';
if (!empty($email) && file_exists($file)) {
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);  // https://www.php.net/manual/en/function.file.php //
    foreach ($lines as $index => $line) {
        if ($index === 0) continue; 
            
        $fields = explode("\t", $line);
            
        if (isset($fields[2]) && trim($fields[2]) === trim($email)) {   // https://www.w3schools.com/Php/func_var_isset.asp //
            $errors[] = "Error: This email is already registered.";
            break;
        }
    }
}

if (!empty($errors)) {
    echo "<h2>Form Error</h2>";
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul>";
}
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Plebe SMT Accounability </title>

</head>

<body>
<?php
if ($_SERVER["REQUEST_METHOD"] != "POST" || !empty($errors)) {
?>

<form method="post" action="submit.php">

<b> Plebe SMT Accounability Form</b>
     
<br> 
Name (Last, First) <input type="text" name="textex"> 
</br>


Rank:
<input type="radio" id="1/C" name="radioex" value="1/C">
<label for="1/C">1/C</label>
<input type="radio" id="2/C" name="radioex" value="2/C">
<label for="2/C">2/C</label>
<input type="radio" id="3/C" name="radioex" value="3/C">
<label for="3/C">3/C</label>
<input type="radio" id="4/C" name="radioex" value="4/C">
<label for="4/C">4/C</label>
<br>

Email <input type="email" name="email" required> 

<br>
  <label for="username">Username:</label><br>
  <input type="text" id="username" name="username"><br> 
  <label for="pwd">Password:</label><br>
  <input type="password" id="pwd" name="pwd"> 
</br>

<br> Type of chit </br>
<input type="checkbox" id="SIQ" name="box[]" value="SIQ">
<label for="SIQ">SIQ</label><br>
<input type="checkbox" id="Light Duty" name="box[]" value="Light Duty">
<label for="Light Duty">Light Duty</label><br>
<input type="checkbox" id="Upper Body" name="box[]" value="Upper Body">
<label for="Upper Body">Only upper body</label><br>
<input type="checkbox" id="None" name="box[]" value="None">
<label for="None">None</label><br>
</br>


<br> 
What is your favotire workout exercise and why? <label for="message"></label>
<textarea id="message" name="message" rows="3" cols="40"></textarea>
</br>

<br> 
Which date works for you? 
<select name="date"> 
  <option value="Sep05">Sep05</option> 
  <option value="Sep12">Sep12</option>
  <option value="Sep19">Sep19</option> 
</select>


<br> 
<input type="submit" value="Submit"> </br>
  <input type="reset" value="Reset">
</form>

<?php
}
?>


</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($errors)) {

    $chit_string = implode("|", $checkbox);
?>
    <h2>Registration Confirmation</h2>
    Your registration has been confirmed with the following details:<br><br>
    <strong>Name:</strong> <?php echo htmlspecialchars($name); ?><br>
    <strong>Rank:</strong> <?php echo htmlspecialchars($radiobox); ?><br>
    <strong>Email:</strong> <?php echo htmlspecialchars($email); ?><br>
    <strong>Username:</strong> <?php echo htmlspecialchars($username); ?><br>
    <strong>Password:</strong>*** <br>
    <strong>Date:</strong> <?php echo htmlspecialchars($date); ?><br>
    <strong>Chit Type:</strong> <?php echo htmlspecialchars($chit_string); ?><br>
    <strong>Favorite Exercise:</strong> <?php echo nl2br(htmlspecialchars($message)); ?><br><br>
    
    <?php
    $file = 'LOG.txt';
    

    if (!file_exists($file) || filesize($file) == 0) {
        $header = implode("\t", ["name", "rank", "email", "username", "password", "date", "chit_type", "favorite_exercise"]) . PHP_EOL;        
        file_put_contents($file, $header, FILE_APPEND | LOCK_EX);
    }

    $clean_message = str_replace(array("\r\n", "\n", "\r"), "&&", $message); 

    $log_entry = implode("\t", [
            $name,
            $radiobox,
            $email,
            $username,
            $password,
            $date,
            $chit_string,
            $clean_message
        ]) . PHP_EOL;

    file_put_contents($file, $log_entry, FILE_APPEND | LOCK_EX);
}
?>