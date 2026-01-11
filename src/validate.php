<?php

function validateMostViewedPage($input)
{
    $errors = [];
    if (!strlen($input)) {
        $errors['count'] = '数字を入力してください';
    } elseif ($input <= 0) {
        $errors['count'] = '1以上の整数で入力してください';
    }
    return $errors;
}

function validateTotalViewsPerDmain($input)
{
    $errors = [];
    if (!strlen($input)) {
        $errors['count'] = 'ドメインコードを入力してください';
    }
    return $errors;
}
