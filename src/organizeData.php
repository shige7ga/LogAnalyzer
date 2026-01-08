<?php

$file = 'test.txt';
$handle = fopen($file, 'r');
$lines = [];

if ($handle) {
    while (($lines[] = fgets($handle)) !== false) {}
    if ($lines[count($lines) - 1] === false) {
        array_pop($lines);
    }
    echo count($lines) . "件のデータを読み込みました" . PHP_EOL;
    fclose($handle);
} else {
    echo "ファイルを開けませんでした";
}

$values = [];
foreach ($lines as $line) {
    $values[] = explode(' ', $line);
}

$insertValues = '';

foreach ($values as $value) {
    if ($value[0] === '""') {
        // $value[0] = "''";
        $insertValues .= '(' . "''" . ", '" . $value[1] . "', " . $value[2] . ', ' . trim($value[3]) . '), ' . PHP_EOL;
    } else {
        $insertValues .= "('" . $value[0] . "', '" . $value[1] . "', " . $value[2] . ', ' . trim($value[3]) . '), ' . PHP_EOL;
    }
}

$insertSentence = "INSERT INTO logs (
  domain_code,
  page_title,
  count_views,
  total_response_size
) VALUES " . substr($insertValues, 0, -3) . ';';

echo $insertSentence . PHP_EOL;
