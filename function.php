<?php
function databaseQuery($pdo, $mode, $string)
{
    $stmt = $pdo->prepare("select * from url_data where " . $mode . " = ?");
    $stmt->bindValue(1, $string);
    $stmt->execute();
    $result = array();
    $result['result'] = $stmt->fetchAll();
    $result['num'] = $stmt->rowCount();
    return $result;
}

function get_http_type()
{
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    return $http_type;
}

function is_url($url)
{
    $preg = "/http[s]?:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is";
    if (preg_match($preg, $url)) {
        return true;
    } else {
        return false;
    }
}

function pdoConnect()
{
    return new PDO('mysql:host=' . $GLOBALS['DB_HOST'] . ';dbname=' . $GLOBALS['DB_NAME'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASS']);
}

function GetRandStr($length)
{
    $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $len = strlen($str) - 1;
    $randstr = '';
    for ($i = 0; $i < $length; $i++) {
        $num = mt_rand(0, $len);
        $randstr .= $str[$num];
    }
    return $randstr;
}
