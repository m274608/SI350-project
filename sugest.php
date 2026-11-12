<?php
  header('Content-Type: application/json; charset=utf-8');
    require_once('lib_read_csv.php');
  
    $q = $_GET["q"];
    $hint = [];
    $limit = 20;
    $boards = (read_csv('boards.csv', True, False));

if ($q !== "") {
  $len = strlen($q);

  foreach ($boards as $board) {

     $boardtype = $board['Surfboard Type'];

    if (strncasecmp($q, $boardtype, $len) === 0) {
       
        $hint[] = $board;
        if(count($hint) >= $limit) {
            break;
        }
      
    }
  }
}

echo json_encode($hint);
?>
