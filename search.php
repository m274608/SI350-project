<?php
require_once('lib_read_csv.php');
echo json_encode(read_csv('boards.csv', True, False));
?>