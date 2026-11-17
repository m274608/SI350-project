<?php 
session_start();
if (isset($_SESSION['email'])){


$attendance = $_POST['admin'] ?? '';
$file = "accounts.txt";
if ($file) {

    $lines = file('accounts.txt');
    $data = [];
    $count = 0;
    foreach ($lines as $line) {
        $parts = explode("\t", trim($line));
 
        if ($parts[9] === $choice){
            $data[] = [
            'name' => $parts[0] ?? '',
            'admin' => $parts[2] ?? '',
            'status' => $parts[3] ?? '',
           
                ];
                $count = $count + 1;
          
        }
            }


if (!empty($data) ){
$sort_column = array_column($data, 'name');
if (empty($attendence)){

array_multisort($sort_column, SORT_ASC, $data);}
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        
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
       <li class="nav-item">
        <a class="nav-link" href="/~m274608/SI350/project/admin.php">Admin Only</a>
      </li>
    </ul>
  </div>
</nav>


<?php
echo "<b>Details on how everyone's accounts";

echo "<div class='table-responsive'>";
echo "<table id='userTable' class = 'table' border = '1' cellpadding = '5' cellspacing = '0'>";
echo "<tr><th>Email</th><th>Date</th><th>admin</th><th>Status</th>";
foreach($data as $row){
    echo "<tr>";
    echo "<td>". htmlspecialchars($row['email'] ?? '') . "</td>";
    echo "<td class = 'role'>". htmlspecialchars($row['admin'] ?? '') . "</td>";
    echo "<td class='status'>". htmlspecialchars($row['status'] ?? '') . "</td>";
    echo "<td>";
    echo "<button class='btn btn-sm btn-warning js-action' data-action=" echo $row['status'] === 'active' ? 'disable' : 'enable';">"
    echo $row['status'] === 'active' ? 'Disable' : 'Enable';
    "</button>"
    "<span class =msg ms-2></span>"
}
echo "</table>";
echo"</div>";
}else{
    echo "None admin does not have access to this information";
}

exit;

}
else{
 echo "Login <a class='nav-link' href='/~m274608/SI350/project/login.html'>here</a>";
}
  ?>
<script>
$(document).ready(function(){
  $("p").click(function(){
    $(this).hide();
  });
});
</script>
<title>Julian's Lab07 createReport</title>

