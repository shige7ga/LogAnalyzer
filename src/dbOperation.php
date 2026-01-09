<?php

require __DIR__ . '/vendor/autoload.php';

// PDO接続を確立する関数
function connectPdo()
{
    // .envファイルの読み込み(環境変数を設定)
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $dbHost = $_ENV['DB_HOST'];
    $dbUsername = $_ENV['DB_USERNAME'];
    $dbPassword = $_ENV['DB_PASSWORD'];
    $dbDatabase = $_ENV['DB_DATABASE'];

    $dsn = "mysql:host=$dbHost;dbname=$dbDatabase;charset=utf8mb4";
    try {
        $pdo = new PDO(
            $dsn,
            $dbUsername,
            $dbPassword,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_LOCAL_INFILE => true,
            ]
        );
    } catch (PDOException $error) {
        error_log($error->getMessage());
        echo 'DB接続失敗' . PHP_EOL;
        exit;
    }
    return $pdo;
}

// テーブルを削除する関数
function dropTable($pdo)
{
    try {
        $sql = "DROP TABLE IF EXISTS logs";
        $pdo->exec($sql);
        echo "テーブルを削除しました" . PHP_EOL;
    } catch (PDOException $error) {
        echo 'エラー発生：'. $error->getMessage() . PHP_EOL;
    }
}

// テーブルを作成する関数
function createTable($pdo)
{
    $sql = <<<EOT
        CREATE TABLE logs (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            domain_code VARCHAR(255) NOT NULL,
            page_title VARCHAR(255) NOT NULL,
            count_views INT NOT NULL,
            total_response_size BIGINT NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    EOT;

    try {
        $pdo->exec($sql);
        echo "テーブルを作成しました" . PHP_EOL;
    } catch (PDOException $error) {
        echo 'エラー発生：'. $error->getMessage() . PHP_EOL;
    }
}

// データを挿入する関数
function insertData($pdo)
{
    $sql = <<<EOT
        LOAD DATA INFILE '/var/lib/mysql-files/rawData.txt'
        INTO TABLE logs
        FIELDS TERMINATED BY ' '
        LINES TERMINATED BY '\n'
        (domain_code, page_title, count_views, total_response_size)
    EOT;

    try {
        $insertCount = $pdo->exec($sql);
        echo "{$insertCount}件のデータを挿入しました" . PHP_EOL;
    } catch (PDOException $error) {
        echo 'エラー発生：'. $error->getMessage() . PHP_EOL;
    }
}

function getMostViewedPage($pdo, int $count)
{
    $sql = <<<EOT
        SELECT
            domain_code,
            page_title,
            count_views
        FROM logs
        ORDER by count_views DESC
        LIMIT {$count};
    EOT;

    try {
        $stmt = $pdo->query($sql);
    } catch (PDOException $error) {
        echo 'エラー発生：' .$error->getMessage() . PHP_EOL;
    }

    while ($row = $stmt->fetch()) {
        echo "\"{$row['domain_code']}\", \"{$row['page_title']}\", {$row['count_views']}" . PHP_EOL;
    }
}
