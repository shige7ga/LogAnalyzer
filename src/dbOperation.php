<?php

require __DIR__ . '/vendor/autoload.php';

// データベースに接続する関数
function dbConnect()
{
    // .envファイルの読み込み(環境変数を設定)
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $dbHost = $_ENV['DB_HOST'];
    $dbUsername = $_ENV['DB_USERNAME'];
    $dbPassword = $_ENV['DB_PASSWORD'];
    $dbDatabase = $_ENV['DB_DATABASE'];

    $link = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
    if (!$link) {
        echo 'データベース接続失敗' . PHP_EOL;
        echo 'debugging error：' . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    return $link;
}

// テーブルを削除する関数
function dropTable($link)
{
    $dropTableSql = 'DROP TABLE IF EXISTS logs;';
    $result = mysqli_query($link, $dropTableSql);
    if ($result) {
        echo "テーブルを削除しました" . PHP_EOL;
    } else {
        echo "テーブル削除に失敗しました" . PHP_EOL;
        echo 'debugging error：' . mysqli_error($link) . PHP_EOL;
        exit;
    }
}

// テーブルを作成する関数
function createTable($link)
{
    $createTableSql = <<<EOT
CREATE TABLE logs (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  domain_code VARCHAR(255) NOT NULL,
  page_title VARCHAR(255) NOT NULL,
  count_views INT NOT NULL,
  total_response_size BIGINT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
EOT;
    $result = mysqli_query($link, $createTableSql);
    if ($result) {
        echo "テーブルを作成しました" . PHP_EOL;
    } else {
        echo "テーブル作成に失敗しました" . PHP_EOL;
        echo 'debugging error：' . mysqli_error($link) . PHP_EOL;
        exit;
    }
}

// ファイルからデータを取得し、INSERT文を生成する関数
function getInsertSentence($link, string $filePath): string
{
    $handle = fopen($filePath, 'r');
    if ($handle === false) {
        throw new RuntimeException('ファイルを開けませんでした') . PHP_EOL;
    }
    $insertCount = 0;
    while (($line = fgets($handle)) !== false) {
        $data = explode(' ', $line);
        if ($data[0] === '') {
            $domainCord = '(' . "''" . ", '";
        } else {
            $domainCord = "('" . $data[0] . "', '";
        }
        $insertValues = $domainCord . $data[1] . "', " . $data[2] . ', ' . trim($data[3]) . ')';
        $insertSentence = <<<EOT
            INSERT INTO logs (
                domain_code,
                page_title,
                count_views,
                total_response_size
                ) VALUES {$insertValues};
        EOT;
        insertData($link, $insertSentence);
        $insertCount++;
    }
    fclose($handle);
    return strval($insertCount) . '件のデータを挿入しました' . PHP_EOL;
}

// データを挿入する関数
function insertData($link, string $insertSentence)
{
    $result = mysqli_query($link, $insertSentence);
    if (!$result) {
        echo "データの挿入に失敗しました" . PHP_EOL;
        echo 'debugging error：' . mysqli_error($link) . PHP_EOL;
        exit;
    }
}
