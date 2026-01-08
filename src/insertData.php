<?php

require_once 'dbOperation.php';

$link = dbConnect();
echo getInsertSentence($link, 'test.txt');
mysqli_close($link);
