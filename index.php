<?php
include('config.php');
include('function.php');
error_reporting(0);
try{
    $pdo = pdoConnect();
    $databaseQuery = databaseQuery($pdo, "code", str_replace("/","",$_GET['c']));
} catch (Exception $e) {
    ?>
    <!DOCTYPE html>
    <head>
        <meta charset="UTF-8">
        <style>
            a{
            text-decoration:none;
            color:#4D4D4D;
            }
            .one{ font-weight: normal; }
            .two{ font-weight: bold; }
            .three{ font-weight: 200; }
        </style>
        <title><?php echo $TITLE?></title>
    </head>
    <body>
        <center>
            <h1 class="one">抱歉！出错啦！</h1>
            <h2 class="three">连接数据库似乎出现了一个致命的错误</h2>
        </center>
    </body>
    <?  
    exit();
}
if($databaseQuery['num']!=0){
    if($JUMP_TIME>0){
        include('jump.php');
        exit();
    } else {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: '.$databaseQuery['result'][0]['url']);//
    }
}


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title><?php echo $TITLE?></title>
    <link rel="stylesheet" href="//static.llilii.cn/css/other/background.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/mdui@1.0.0/dist/css/mdui.min.css" />
    <script src="https://static.llilii.cn/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        a{
            text-decoration:none
        }
        .hide {
            position: inherit;
            width: 10PX;
            height: calc(20vh);
        }
    </style>
</head>

<body class="mdui-appbar-with-toolbar  mdui-theme-primary-pink mdui-theme-accent-pink">

    <div class="mdui-container " style="max-width: 400px; ">
        <div class="hide">
        </div>
        <div class="mdui-card" style="border-radius: 16px;">
            <div class="mdui-card-media">
                <div class="mdui-card-menu">
                </div>
            </div>
            <div class="mdui-card-primary">
                <div class="mdui-card-primary-title">短链生成器</div>
                <div class="mdui-card-primary-subtitle">在此处您可以一键生成您的短链接</div>
            </div>
            <div class="mdui-card-content">
                <div class="mdui-textfield">
                    <label class="mdui-textfield-label">需要生成短链的网址</label>
                    <input class="mdui-textfield-input" id="url" placeholder="https://kagamine.top" type="text" />
                </div>
                <br>
            </div>
            <div class="mdui-card-actions">
                <button class="mdui-btn mdui-color-theme-accent mdui-ripple mdui-float-right" id="submitbtn" onclick='submit()' style="border-radius: 10px;">生成短链</button>
            </div>
        </div>
        <footer>
            <!-- 本程序使用GPL2.0协议开源，请遵守此协议，请勿删除本处版权，否则原作者保留一切法律权利 -->
            <!-- 如果看不懂GPL2.0协议请自行查看根目录人话版解释。如果想删除本处版权的请直接不要使用本程序。 -->
            <center><p style="color:white;">&copy; 2020 Copyright <a style="color:white;" target="_blank" href="https://www.wunote.cn/">UnknownO</a></p></center>
        </footer>

    </div>

    <script src="//cdn.jsdelivr.net/npm/mdui@1.0.0/dist/js/mdui.min.js"></script>
    <script>
        function submit() {
            $("#submitbtn").attr("disabled", true);
            url = $("#url").val();
            if (<?php if($IMAGE_VERIFICATION) echo 'true'; else echo 'false'; ?>) {
                imageVerification(function(answer) {
                    request(url, answer)
                })
            } else {
                request(url, '0000');
            }

        }

        function imageVerification(callback) {
            mdui.dialog({
                title: '请输入图片中的验证码',
                content: '<center><div class="mdui-row"> <div class="mdui-col-xs-9"> <div class="mdui-textfield"> <input class="mdui-textfield-input" id="answer" type="text" placeholder="请输入您的答案" /></div> </div> <div class="mdui-col-xs-3"> <img style="position: relative;top:15px" id="vcode" src="./vcode.php" /> </div> </div></center>',
                modal: true,
                buttons: [{
                        text: '取消'
                    },
                    {
                        text: '确认',
                        onClick: function(inst) {
                            callback(document.getElementById('answer').value);
                        }
                    }
                ]
            });
        }

        function request(url, answer) {
            $.ajax({
                type: 'post',
                url: './submit.php',
                data: {
                    url: url,
                    code: answer,
                },
                dataType: 'text',
                success: function(data) {
                    console.log(data)
                    data = JSON.parse(data);
                    if (data.code == 1) {
                        mdui.alert('<div class="mdui-typo">您的短链接为：<a href="'+data.result+'" target="_blank">'+data.result+'</a></div>', '生成成功');
                        $("#url").val("");
                    } else {
                        mdui.snackbar({
                            message: data.msg,
                            position: 'right-top'
                        });
                    }
                    $("#submitbtn").attr("disabled", false);
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    $.each(errors.errors, function(key, value) {
                        mdui.snackbar({
                            message: "出现了一个未知错误",
                            position: 'right-top'
                        });
                    });
                },
            });
        }
    </script>
    <div id="background">
        <div class="bg-image" style="background: url('//static.llilii.cn/images/kagamine/32639516_p2.jpg') no-repeat center center; display: block;"></div>
    </div>
</body>

</html>