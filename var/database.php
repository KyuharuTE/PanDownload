<?
/*
* 请修改下方以及13行的数据库信息
*/
$servername='127.0.0.1';#数据库地址
$username='';#用户名
$password='';#密码
$dbname=''; #数据库

function check(){
    if (isloginz()===1) {
        if (strjia($_COOKIE['user'])==$_COOKIE['usersign'] and strjia($_COOKIE['pass'])==$_COOKIE['passsign']) {
            $con=mysqli_connect('数据库地址',"用户名",'密码','数据库');# 这里也要改
            $sql="SELECT * FROM user WHERE user='".$_COOKIE['user']."'";
            if ($con->query($sql)->num_rows===1) {
                $cloud=$con->query($sql)->fetch_array();
                if ($cloud('pass')==$_COOKIE['pass']) {
                    return 1;
                }
            }else{
                return 1;
            }
        }
    }else {
        return 1;
    }
}
?>