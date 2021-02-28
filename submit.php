<?php
session_start();
include('config.php');
include('function.php');
$url = $_POST['url'];
$code = $_POST['code'];
if (empty($url) || empty($code)) {
    exit('{"code":-1,"msg":"参数缺失","result":""}');
}
if ($_SESSION['vcode'] != md5($code . $VERIFICATION_KEY) && $IMAGE_VERIFICATION) {
    exit('{"code":-2,"msg":"抱歉，人机验证失败","result":""}');
}
$_SESSION['vcode'] = "Kagamine Yes!";

if (!is_url($url)) {
    exit('{"code":-3,"msg":"不是一个正确的链接","result":""}');
}
$pdo = pdoConnect();
$shortCode = GetRandStr($URL_SHORTENER_LENGHT);
$id = rand(100000000, 999999999);
if (databaseQuery($pdo, "code", $shortCode)['num'] != 0 || databaseQuery($pdo, "id", $id)['num'] != 0) {
    exit('{"code":-3,"msg":"抱歉出现了一个致命错误，请重试！","result":""}');
}
$databaseQuery = databaseQuery($pdo, "url", $url);
if ($REWRITE) {
    $rewriteStr = "";
} else {
    $rewriteStr = "?c=";
}
if ($databaseQuery['num'] == 0) {
    $stmt = $pdo->prepare("insert into url_data(id,url,code)values(?,?,?)");
    $stmt->bindValue(1, $id);
    $stmt->bindValue(2, $url);
    $stmt->bindValue(3, $shortCode);
    if ($stmt->execute()) {
        exit('{"code":1,"msg":"success","result":"' . get_http_type() . $_SERVER['HTTP_HOST'] . '/' . $rewriteStr . $shortCode . '"}');
    }
} else {
    exit('{"code":1,"msg":"success","result":"' . get_http_type() . $_SERVER['HTTP_HOST'] . '/' . $rewriteStr . $databaseQuery['result'][0]['code'] . '"}');
}
