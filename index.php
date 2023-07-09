<?
// import php
require_once('./fun/quurl.php');
require_once('./user/fun.php');
require_once('./var/database.php');
// iflogin
if (isloginz()===1) {
}else {
    $war='login';
}
if (check()===1) {
} else {
    $war='loginerror';
}


// if stu
if (!empty($_GET['stu'])) {
    switch ($_GET['stu']) {
        case 'exitlogin':
            setcookie("user",$cloud['email'],time()-3600,'/');
            setcookie("usersign",strjia($cloud['email']),time()-3600,'/');
            setcookie("pass",$cloud['pass'],time()-3600,'/');
            setcookie("passsign",strjia($cloud['pass']),time()-3600,'/');
            $url = $_SERVER['REMOTE_HOST']."/user/login/";  
            echo "<script type='text/javascript'>";  
            echo "window.location.href='$url'";  
            echo "</script>";
            exit;
            break;
        
        default:
            // 不要乱加参数啊喂
            break;
    }
}

// if stu
if (!empty($_POST['type']) and !empty($_POST['url'])) {
    if (isloginz()===1) {
        switch ($_POST['type']) {
            case 'lanzou':
                $dw='lanzou';
                if (!empty($_POST['url2'])) {
                    $zhi=lanzou($_POST['url']);
                    $stu='suc';
                } else {
                    $stu='can';
                }
                break;
            case '123pan':
                $dw='123pan';
                $dp = new d123pan;
                //$dp->cache_time=60;//设置缓存时间
                if (!empty($_POST['pwd'])) {
                    $re=$dp->getUrl($_POST['url'],$_POST['pwd']);
                    $stu='suc';
                    $zhi=$re['info'];
                } else {
                    $stu='suc';
                    $re=$dp->getUrl($_POST['url'],null);
                    $zhi=$re['info'];
                }
                
                break;
        }
    }else {
        $stu='login';
        $dw=$_POST['type'];
    }
}
?>
<html>
    <head>
        <title>
            <?
            require('./var/site.php');
            echo 'PanDownload - '.$name;
            ?>
        </title>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="description" content="<?
        require_once('./var/site.php');
        echo 'PanDownload - '.$name
        ?>">
        <meta name="keywords" content="pan,PanDownload,网盘,网盘下载,网盘直链,网盘提速">
        <!--import yaou ui css-->
        <link rel="stylesheet" href="https://yaou.pro/css/index.css"
        <!--import mdui css-->
        <link rel="stylesheet" href="https://unpkg.com/mdui@1.0.2/dist/css/mdui.min.css"/>
        <!--import myui-->
        <link rel="stylesheet" href="/css/my.css"/>
    </head>
    <body class=".mdui-theme-primary-cyan .mdui-theme-accent-cyan">
        <!--head-->
        <div class="mdui-appbar mdui-color-transparent">
            <div class="mdui-toolbar">
                <!--title-->
                <a href="javascript:;" class="mdui-typo-headline font-mc">PanDownload - <?
                require_once('./var/site.php');
                echo($name);
                ?></a>
                <div class="mdui-toolbar-spacer"></div>
                <!--refresh btn-->
                <a href="#" class="mdui-btn mdui-btn-icon">
                    <i class="mdui-icon material-icons">refresh</i>
                </a>
                <a href="javascript:;" class="mdui-btn mdui-btn-icon" mdui-menu="{target: '#example-1'}">
                    <i class="mdui-icon material-icons">more_vert</i>
                </a>
                <!--menu-->
                <ul class="mdui-menu" id="example-1">
                    <li class="mdui-menu-item">
                        <a href="/user/login/" class="mdui-ripple">登录</a>
                    </li>
                    <li class="mdui-menu-item">
                        <a href="/index.php?stu=exitlogin" class="mdui-ripple">注销</a>
                    </li>
                </ul>
            </div>
        </div>
        <div style="width:100%;height:100%;text-align: center;">
            <div style="text-align: center;width: 100%;">
                <h1 class="font-mc" style="text-align:center">HOME</h1>
                <!--war-->
                <?
                if (!empty($war)) {
                    echo '<div class="mdui-card" style="margin:5px;background-color: rgba(255,255,255,0.5);">
                        <div class="mdui-card-content">
                            <h2 style="text-align: center">警告</h2>
                            <div class="mdui-divider"></div>
                            <div class="mdui-typo">
                                <p>';
                    switch ($war) {
                        case 'login':
                            echo('请先<a href="/user/login/">登录</a>');
                            break;
                        case 'loginerror':
                            require_once('./var/site.php');
                            echo('Cookie被篡改，请注销后重新登录，如重登后依旧发生此情况，请<a href="mailto:'.$adminemail.'">联系客服</a>');
                            break;
                        default:
                            echo('发生未知错误');
                            break;
                    }
                    echo '</p>
                            </div>
                        </div>
                    </div>';
                }
                ?>
                <!--GONGGAO-->
                <div class="mdui-card" style="margin:5px;background-color: rgba(255,255,255,0.5);">
                    <div class="mdui-card-content">
                        <h2 style="text-align: center">公告</h2>
                        <div class="mdui-divider"></div>
                        <div class="mdui-typo">
                            <p><a href="http://yaou.pro">YAOU Studio</a>制作开源</p>
                        </div>
                    </div>
                </div>
                <!--LANZOU-->
                <div class="mdui-card" style="margin:5px;background-color: rgba(255,255,255,0.5);">
                    <div class="mdui-card-content">
                        <h2 style="text-align: center">蓝奏云解析</h2>
                        <div class="mdui-divider"></div>
                        <form action="/index.php" method="post" style="text-align:left">
                            <!--url-->
                            <div class="mdui-textfield mdui-textfield-floating-label">
                                <label class="mdui-textfield-label">分享地址</label>
                                <input class="mdui-textfield-input" type="url" name="url" required/>
                                <div class="mdui-textfield-error">请输入正确地址</div>
                            </div>
                            <!--url2-->
                            <h3>请选择前缀</h3>
                            <select class="mdui-select" name="url2" mdui-select="{position: 'top'}">
                                <option value="https://www.lanzous.com">https://www.lanzous.com</option>
                                <option value="https://www.lanzoux.com">https://www.lanzoux.com</option>
                            </select>
                            <!--jiao-->
                            <div class="mdui-textfield mdui-textfield-floating-label">
                                <label class="mdui-textfield-label">校准(别管我！)</label>
                                <input class="mdui-textfield-input" type="text" name="type" value="lanzou" required/>
                            </div>
                            <div style="text-align:center;">
                                <input type="submit" class="mdui-btn mdui-btn-raised mdui-ripple" style="background-color: rgba(255,255,255,0.7)" value="获取"/>
                            </div>
                        </form>
                        <div class="mdui-typo">
                        <p><?
                        if (!empty($dw)) {
                            if ($dw=='lanzou') {
                                switch ($stu) {
                                    case 'login':
                                        echo('请先<a href="/user/login/">登录</a>');
                                        break;
                                    case 'can':
                                        echo('参数不全');
                                        break;
                                    case 'suc':
                                        echo('直链解析成功，结果：'.$zhi);
                                        break;
                                    default:
                                        echo('发生未知错误');
                                        break;
                                }
                            }
                        }
                        ?></p>
                        </div>
                    </div>
                </div>
                <!--123pan-->
                <div class="mdui-card" style="margin:5px;background-color: rgba(255,255,255,0.5);">
                    <div class="mdui-card-content">
                        <h2 style="text-align: center">123云盘解析</h2>
                        <div class="mdui-typo">
                            <p>声明：此功能使用了<a href="https://gitee.com/web/123pan/">123盘直链带密码解析</a>开源项目，因开源协议要求，在此处标注源项目地址，并承诺此功能永久免费，侵权联系<a href="mailto:azusa@yaou.work">azusa@yaou.work</a>。</p>
                        </div>
                        <div class="mdui-divider"></div>
                        <form action="/index.php" method="post" style="text-align:left">
                            <!--url-->
                            <div class="mdui-textfield mdui-textfield-floating-label">
                                <label class="mdui-textfield-label">分享地址</label>
                                <input class="mdui-textfield-input" type="url" name="url" required/>
                                <div class="mdui-textfield-error">请输入正确地址</div>
                            </div>
                            <!--pwd-->
                            <div class="mdui-textfield mdui-textfield-floating-label">
                                <label class="mdui-textfield-label">分享密码</label>
                                <input class="mdui-textfield-input" type="password" name="pwd"/>
                            </div>
                            <!--jiao-->
                            <div class="mdui-textfield mdui-textfield-floating-label">
                                <label class="mdui-textfield-label">校准(别管我！)</label>
                                <input class="mdui-textfield-input" type="text" name="type" value="123pan" required/>
                            </div>
                            <div style="text-align:center;">
                                <input type="submit" class="mdui-btn mdui-btn-raised mdui-ripple" style="background-color: rgba(255,255,255,0.7)" value="获取"/>
                            </div>
                        </form>
                        <div class="mdui-typo">
                        <p><?
                        if (!empty($dw)) {
                            if ($dw=='123pan') {
                                switch ($stu) {
                                    case 'login':
                                        echo('请先<a href="/user/login/">登录</a>');
                                        break;
                                    case 'can':
                                        echo('参数不全');
                                        break;
                                    case 'suc':
                                        echo('直链解析成功，结果：'.$zhi);
                                        break;
                                    default:
                                        echo('发生未知错误');
                                        break;
                                }
                            }
                        }
                        ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--import mdui js-->
        <script src="https://unpkg.com/mdui@1.0.2/dist/js/mdui.min.js"></script>
    </body>
</html>