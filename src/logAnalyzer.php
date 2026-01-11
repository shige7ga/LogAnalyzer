<?php
require_once 'dbOperation.php';
require_once 'selectTable.php';
require_once 'outputs.php';

// テーブル作成済みか確認して、未設定の場合はテーブル新規作成
$pdo = connectPdo();
$stmt = $pdo->query("SHOW TABLE STATUS LIKE 'logs'");
$tableData = $stmt->fetch();
if (!$tableData) {
    initializeTable();
}

firstOutput();

while (true) {
    echo '使いたい機能を選択してください：';
    $input = trim(fgets(STDIN));

    if ($input === '1') {
        outputMostViewedPage();
    } elseif ($input === '2') {
        outputTotalViewsPerDmain();
    } elseif ($input === '9') {
        echo 'ツールを終了します' . PHP_EOL;
        break;
    } else {
        echo '1, 2, 9のいずれかから選択してください' . PHP_EOL;
    }
}
