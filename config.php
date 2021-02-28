<?php
//V1.1.2版本


//伪静态规则（目前伪静态规则只写了nginx的，Apache的话...还没写，所以..有能力的大佬可以帮忙写一下）
$REWRITE=false;

//数据库名
$DB_HOST="127.0.0.1";

//数据库名
$DB_NAME="BT_DB_NAME";

//数据库登录用户名
$DB_USER="BT_DB_USERNAME";

//数据库登录密码
$DB_PASS="BT_DB_PASSWORD";

//几秒后跳转（等于0为关闭跳转页面）
$JUMP_TIME=10;

//站点名称
$TITLE="未知的短链";

//生成短链的长度
$URL_SHORTENER_LENGHT=6;

//是否开启人机验证（注意，使用本功能，请务必开启php的GD库，当然一般默认是开启的）
$IMAGE_VERIFICATION=false;

//我也不知道这个选项干什么的，23333
$VERIFICATION_KEY="镜音双子是2007年12月27日生日的";
