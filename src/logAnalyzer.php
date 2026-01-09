<?php
require_once 'dbOperation.php';

$pdo = connectPdo();
$stmt = $pdo->query("SHOW TABLE STATUS LIKE 'logs'");
$tableData = $stmt->fetch();

if (!$tableData) {
    initializeTable();
}

echo 'Wikipediaログ解析ツールです' . PHP_EOL;
echo '下記から操作を選択してください' . PHP_EOL;
echo '1：ビュー数の多い順に情報を検索する' . PHP_EOL;
echo '2：ドメインコードに対して、ビュー数の多い記事を検索する' . PHP_EOL;



// echo "指示を入力してください：";
// $input = trim(fgets(STDIN));

// echo $input . PHP_EOL;
