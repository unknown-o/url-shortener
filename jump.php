<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>跳转中 - <?php echo $TITLE ?></title>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/mdui@1.0.0/dist/css/mdui.min.css" />
    <meta http-equiv="refresh" content="<?php echo $JUMP_TIME ?>;url=<?php echo $databaseQuery['result'][0]['url'] ?>">
</head>

<body class="mdui-theme-primary-pink mdui-theme-accent-pink">

    <body>
        <div id='imgBox' class="mdui-container" style="max-width: 400px; ">
            <br><br><br>
            <div class="mdui-card">
                <div class="mdui-card-media">
                    <img id='imgSrc' src="" />
                    <div class="mdui-card-media-covered">
                        <div class="mdui-card-primary">
                            <div id='imgName' class="mdui-card-primary-title"><?php echo $databaseQuery['result'][0]['url'] ?></div>
                            <div id='imgUrl' class="mdui-card-primary-subtitle"><?php echo "正在准备跳转到" . $databaseQuery['result'][0]['url'] . "中，请稍后" ?></div>
                        </div>
                    </div>
                </div>
                <div id='Remind'>
                    <div class="mdui-card-content">
                        <center><img src="https://static.llilii.cn/images/other/loading.png" /></center><br>
                        <div class="mdui-progress">
                            <div id='loadingStatus' class="mdui-progress-determinate" style="width: 0%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <br><br><br><br>
        </div>

        <script src="//cdn.jsdelivr.net/npm/mdui@1.0.0/dist/js/mdui.min.js"></script>
    </body>

</html>

<script>
    image_get();

    mdui.snackbar({
        message: '正在准备跳转中...',
        timeout: 1000
    });

    function image_get() {
        document.getElementById("imgBox").style = "max-width: 400px;";
        document.getElementById("imgSrc").src = '';
        document.getElementById("Remind").innerHTML = '<div class="mdui-card-content"><center><img src="https://static.llilii.cn/images/other/loading.png"/></center><br><div class="mdui-progress"><div id=\'loadingStatus\' class="mdui-progress-determinate" style="width: 0%;"></div></div></div>';
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            switch (xhr.readyState) {
                case 4:
                    if ((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304) {
                        img_name = JSON.parse(xhr.responseText)['file_name'][RandomNumBoth(0, JSON.parse(xhr.responseText)['file_num'])];
                        img_url = "https://img.llilii.cn/kagamine/" + img_name;
                        load_img(img_url);
                    }
                    break;
            }
        }
        xhr.open('get', 'https://static.llilii.cn/json/img_list.json');
        xhr.send(null);
    }

    function RandomNumBoth(Min, Max) {
        var Range = Max - Min;
        var Rand = Math.random();
        var num = Min + Math.round(Rand * Range);
        return num;
    }

    function load_img(url) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url);
        xhr.onprogress = function(event) {
            if (event.lengthComputable) {
                document.getElementById("loadingStatus").style = 'width: ' + parseInt((event.loaded / event.total) * 100).toString() + '%;';
            }
        };
        xhr.onreadystatechange = function() {
            switch (xhr.readyState) {
                case 4:
                    if ((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304) {
                        get_image_size(url);
                    } else {
                        mdui.alert(name + "抱歉，图片加载失败！");
                    }
            }
        };
        xhr.send();
    }

    function get_image_size(url) {
        var img = new Image();
        img.src = url;
        img.onerror = function() {
            mdui.alert("抱歉，图片加载失败！");
            return false;
        };

        if (img.complete) {
            display_image(url, img);
        } else {
            img.onload = function() {
                display_image(url, img);
                img.onload = null;
            }
        }
    }

    function display_image(url, img) {
        document.getElementById("imgBox").style = "max-width: " + img.width + 'px;';
        document.getElementById("imgSrc").src = url;
        document.getElementById("Remind").innerHTML = '';
    }
</script>