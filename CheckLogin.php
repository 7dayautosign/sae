<?php
session_start();  
require_once ('conn.php');  
$action = $_GET['action'];  
if ($action == 'login') {  //登录  
    $user = stripslashes(trim($_POST['user']));  
    $pass = stripslashes(trim($_POST['pass']));  

    if (empty ($user)) {  
        echo "  ";
        exit;  
    }  
    if (empty ($pass)) {  
        echo "  ";
        exit;  
    }  
    // $md5pass = md5($pass); //密码使用md5加密  
    $query = mysql_query("select * from user where username='$user'");  
  
    $us = is_array($row = mysql_fetch_array($query));  
  
    $ps = $us ? $pass == $row['password'] : FALSE; 
    if ($ps) {  
        $counts = $row['login_counts'] + 1;  
        $_SESSION['user'] = $row['username'];  
        $_SESSION['login_time'] = $row['login_time'];  
        $_SESSION['login_counts'] = $counts;  
        $ip = get_client_ip(); //获取登录IP  
        $logintime = mktime();  
        $rs = mysql_query("update user set login_time='$logintime',login_ip='$ip', login_counts='$counts' where username='$user'");  
        if ($rs) {  
            $arr['success'] = 1;  
            $arr['msg']="succ";
        } else {  
            $arr['success'] = 0;  
            $arr['msg']="fail";
        }  
    } else {  
        $arr['success'] = 0;  
        $arr['msg']="fail";
    }  
    // var_dump($arr);
    echo json_encode($arr); //输出json数据  
}  
elseif ($action == 'reg') {
    $reguser = stripslashes(trim($_POST['reguser']));  
    $regpass = stripslashes(trim($_POST['regpass']));  

    if (empty ($reguser)) {  
    echo '用户名不能为空';  
        exit;  
    }  
    if (empty ($regpass)) {  
        echo '密码不能为空';  
        exit;  
    }  

    // $md5pass = md5($regpass); //密码使用md5加密  
    if(Login($reguser,$regpass)){
        $arr['success'] = 1;   

    }    else{
            $arr['success'] = 0;  
    }
    echo json_encode($arr); 
}

  

function get_client_ip() {
    $ip = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
        foreach ($matches[0] AS $xip) {
            if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                $ip = $xip;
                break;
            }
        }
    }
    return $ip;
}


///////////本地数据库环境
//$link=mysql_connect('localhost','root','root');
//mysql_select_db('cookie',$link);


//////////初始化变量
$header = '';

/////////#BEGIN
function curl_https($url, $data, $timeout = 30, $head=array(),$re=true) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_PROXY, '115.159.127.208:3128');
    // curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:8888');
    curl_setopt($ch, CURLOPT_HEADER, 1);
    if (empty($head)==FALSE) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
        // echo '<br>'.$head[0];
    }
    $response = curl_exec($ch);
    if ($error = curl_error($ch)) {
        die($error);
    }

        if (!$re) {
        $laji=curl_getinfo($ch,CURLINFO_HEADER_SIZE);
        $response=substr($response, $laji);
    }
    curl_close($ch); //关闭curl
    // echo $response;
    return $response;
}



function Login($user,$pwd){

    $url = 'https://app5.plateno.com:9951/authority/login';
    $data = '{"sign":"0d721cf76c3b9615e652e9f6122d60d5","username":"' . $user . '","clientInfo":{"appVersion":"5.0.4","channelId":"262810","deviceId":"00000000-650a-e532-0d38-091f4bb985a8","hardwareModel":"m1 note","os":"android","systemVersion":"4.4.4","versionCode":"201520601"},"password":"' . $pwd. '"}';

        $response = curl_https($url, $data, 20,null,FALSE);
        // echo $response;
        $temp1=json_decode($response,true);
        $id = $temp1['result']['member']['memberId'];
        $realname = $temp1['result']['member']['memberName'];
        $userlevel=$temp1['result']['member']['memberTypeName'];

        // echo $temp1['msgCode'];
        if($temp1['msgCode']==100){
            
            mysql_query("insert into user(username,password) values('$user','$pwd')");
            mysql_query("update user set id='$id' , realname='$realname' ,succnum='0' , failnum='0' , autocj='0' , autosign='0', userlevel='$userlevel' , succtime='0' where username='$user'");

            // echo "<br>登陆成功";
            // echo "<br>".$token;
            return true;
        }else{
            return FALSE;
        }

    
}

?>