<?php

require_once 'dbOperation.php';

$link = dbConnect();
$insertSentence = getInsertSentence('rawData.txt') . PHP_EOL;
insertData($link, $insertSentence);
mysqli_close($link);
