<?
/*
* 此处为Cookie加密代码
* 请修改第7行和第8行的校验密钥
*/
function strjia($str){
    $str2=$str."改我";
    $encrypt = openssl_encrypt($str, 'AES-128-ECB', '改我', 0);  
    return $encrypt;
}
function islogin(){
    if (!empty($_COOKIE['user']) and !empty($_COOKIE['usersign']) and !empty($_COOKIE['pass']) and !empty($_COOKIE['passsign'])) {
        $url = $_SERVER['REMOTE_HOST']."/index.php";  
        echo "<script type='text/javascript'>";  
        echo "window.location.href='$url'";  
        echo "</script>";
        exit;
    }
}
function isloginz(){
    if (!empty($_COOKIE['user']) and !empty($_COOKIE['usersign']) and !empty($_COOKIE['pass']) and !empty($_COOKIE['passsign'])) {
        return 1;
    }else {
        return 0;
    }
}
?>