<?php

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
    pdoQuery($pdo, $sql);
}

function getTotalViewsPerDmain($pdo, $domainCodes)
{
    $valueDomainCodes = '';
    foreach ($domainCodes as $domainCode) {
        $valueDomainCodes .= "'" . $domainCode . "', ";
    }
    $valueDomainCodes = substr($valueDomainCodes, 0, -2);

    $sql = <<<EOT
        SELECT
            domain_code,
            total_views
        FROM total_views_domain
        WHERE domain_code IN ({$valueDomainCodes})
        ORDER BY total_views DESC;
    EOT;
    pdoQuery($pdo, $sql);
}

function pdoQuery($pdo, $sql)
{
    try {
        $stmt = $pdo->query($sql);
    } catch (PDOException $error) {
        echo 'エラー発生：' .$error->getMessage() . PHP_EOL;
    }

    while ($rows = $stmt->fetch()) {
        $row = '';
        for ($i = 0; $i < count($rows) / 2; $i++) {
            // 数値型に変化した結果が0でない場合 or 値が0の場合
            if ((int)$rows[$i] !== 0 || $rows[$i] === '0') {
                $rows[$i] = (int)$rows[$i];
            }
            $row .= is_string($rows[$i]) ? '"' . $rows[$i] . '", ' : $rows[$i] . ', ';
        }
        echo substr($row, 0, -2) . PHP_EOL;
    }
}
