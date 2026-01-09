<?php
require_once 'dbOperation.php';
require_once 'selectTable.php';

$domainCodes = array_slice($argv, 1);
$pdo = connectPdo();
getTotalViewsPerDmain($pdo, $domainCodes);
