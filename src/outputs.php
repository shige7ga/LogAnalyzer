<?php

function firstOutput()
{
    echo 'Wikipediaログ解析ツールです。下記から操作を選択してください。' . PHP_EOL;
    echo 'ーーーーーーーーーー' . PHP_EOL;
    echo '1：最もビュー数の多いページ情報を表示する' . PHP_EOL;
    echo '2：ドメインコードごとの合計ビュー数を表示する' . PHP_EOL;
    echo '9：ツールを終了する' . PHP_EOL;
    echo 'ーーーーーーーーーー' . PHP_EOL;
}

function outputMostViewedPage(): void
{
    echo '何件表示しますか(数値で入力してください)：';
    $count = (int)trim(fgets(STDIN));
    $pdo = connectPdo();

    echo '<検索結果>ーーーーー' . PHP_EOL;
    getMostViewedPage($pdo, $count);
    echo 'ーーーーーーーーーー' . PHP_EOL;
}

function outputTotalViewsPerDmain(): void
{
    echo '検索するドメインコードを入力してください(例：en, ja, de)' . PHP_EOL;
    echo '複数検索する場合、半角スペースで区切ってください(例：en de ja)：';
    $domainCode = trim(fgets(STDIN));
    $domainCodes = explode(' ', $domainCode);
    $pdo = connectPdo();

    echo '<検索結果>ーーーーー' . PHP_EOL;
    getTotalViewsPerDmain($pdo, $domainCodes);
    echo 'ーーーーーーーーーー' . PHP_EOL;
}
