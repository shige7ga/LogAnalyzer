<?php
require_once 'dbOperation.php';

$count = (int)$argv[1];
$pdo = connectPdo();
getMostViewedPage($pdo, $count);
