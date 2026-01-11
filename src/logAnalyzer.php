<?php
require_once 'dbOperation.php';
require_once 'selectTable.php';

$pdo = connectPdo();
$stmt = $pdo->query("SHOW TABLE STATUS LIKE 'logs'");
$tableData = $stmt->fetch();

if (!$tableData) {
    initializeTable();
}

echo 'Wikipediaログ解析ツールです' . PHP_EOL;
echo '下記から操作を選択してください' . PHP_EOL;
echo 'ーーーーーーーーーーーーーーー' . PHP_EOL;
echo '1：最もビュー数の多い情報を表示する' . PHP_EOL;
echo '2：ドメインコードごとの合計ビュー数を表示する' . PHP_EOL;
echo '9：ツールを終了する' . PHP_EOL;
echo 'ーーーーーーーーーーーーーーー' . PHP_EOL;

while (true) {
    echo '使いたい機能を選択してください：';
    $input = trim(fgets(STDIN));

    if ($input === '1') {
        echo '何件表示しますか(数値で入力してください)：';
        $count = (int)trim(fgets(STDIN));
        $pdo = connectPdo();

        echo '<検索結果>' . PHP_EOL;
        echo 'ーーーーーーーーーーーーーーー' . PHP_EOL;
        getMostViewedPage($pdo, $count);
        echo 'ーーーーーーーーーーーーーーー' . PHP_EOL;
    } elseif ($input === '2') {
        echo '検索するドメインコードを入力してください(例：en, ja, de)' . PHP_EOL;
        echo '複数検索する場合、半角スペースで区切って入力してください(例：en de)：';
        $domainCode = trim(fgets(STDIN));
        $domainCodes = explode(' ', $domainCode);
        $pdo = connectPdo();

        echo '<検索結果>' . PHP_EOL;
        echo 'ーーーーーーーーーーーーーーー' . PHP_EOL;
        getTotalViewsPerDmain($pdo, $domainCodes);
        echo 'ーーーーーーーーーーーーーーー' . PHP_EOL;
    } elseif ($input === '9') {
        echo 'ツールを終了します' . PHP_EOL;
        break;
    } else {
        echo '1, 2, 9のいずれかから選択してください' . PHP_EOL;
    }
}
