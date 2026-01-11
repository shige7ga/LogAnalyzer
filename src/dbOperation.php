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
                // SQLエラーを例外(Exception)として投げる設定
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
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
        $deleteLogs = "DROP TABLE IF EXISTS logs";
        $deleteTotalViews = "DROP TABLE IF EXISTS total_views_domain";
        $pdo->exec($deleteLogs);
        $pdo->exec($deleteTotalViews);
    } catch (PDOException $error) {
        echo 'エラー発生：'. $error->getMessage() . PHP_EOL;
    }
}

// テーブルを作成する関数
function createTable($pdo)
{
    $createLogsTable = <<<EOT
        CREATE TABLE logs (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            domain_code VARCHAR(255) NOT NULL,
            page_title VARCHAR(255) NOT NULL,
            count_views INT NOT NULL,
            total_response_size BIGINT NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    EOT;

    $createTotalVeiwsTable = <<<EOT
        CREATE TABLE total_views_domain (
            domain_code VARCHAR(100) NOT NULL PRIMARY KEY,
            total_views BIGINT NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    EOT;

    try {
        $pdo->exec($createLogsTable);
        $pdo->exec($createTotalVeiwsTable);
    } catch (PDOException $error) {
        echo 'エラー発生：'. $error->getMessage() . PHP_EOL;
    }
}

// データを挿入する関数
function insertData($pdo)
{
    // rawData.txtからBulk Insert
    $insertToLogs = <<<EOT
        LOAD DATA INFILE '/var/lib/mysql-files/rawData.txt'
        INTO TABLE logs
        FIELDS TERMINATED BY ' '
        LINES TERMINATED BY '\n'
        (domain_code, page_title, count_views, total_response_size)
    EOT;

    // 合計ビュー数を取得するクエリ高速化の為、Summaryテーブル作成
    $insertToTotalViews = <<<EOT
        INSERT INTO total_views_domain (domain_code, total_views)
        SELECT domain_code, SUM(count_views)
        FROM logs
        GROUP BY domain_code
        ON DUPLICATE KEY UPDATE
            total_views = VALUES(total_views);
    EOT;

    try {
        $pdo->exec($insertToLogs);
        $pdo->exec($insertToTotalViews);
    } catch (PDOException $error) {
        echo 'エラー発生：'. $error->getMessage() . PHP_EOL;
    }
}

// Indexの設定(クエリ高速化のため)
function createIndex($pdo) {
    $sql = "CREATE INDEX idx_logs_count_views_desc ON logs(count_views DESC);";
    try {
        $pdo->exec($sql);
    } catch (PDOException $error) {
            echo 'エラー発生：' . $error->getMessage() . PHP_EOL;
    }
}

// テーブルの初期化
function initializeTable() {
    $pdo = connectPdo();
    dropTable($pdo);
    createTable($pdo);
    createIndex($pdo);
    echo '初回のテーブル設定をしています... 少々お待ちください ٩(¨ )ว=͟͟͞͞' . PHP_EOL;
    insertData($pdo);
    echo '初期設定が完了しました' . PHP_EOL;
}
