<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Original Author <author@example.com>                        |
// |          Your Name <you@example.com>                                 |
// +----------------------------------------------------------------------+
//
// $Id:$
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////初始化数据//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


///////////SAE数据库环境
require_once ('conn.php');  
///////////本地数据库环境
//$link=mysql_connect('localhost','root','root');
//mysql_select_db('cookie',$link);


//////////初始化变量
$header = '';
/////////#BEGIN
$query=mysql_query("select username,password from user where autosign=1");
while($row=mysql_fetch_row($query)){ 
$user=$row[0];
$pwd=$row[1];
// echo "<br><br><br>``````````````````````````````````````````";
post($user,$pwd,0);
set_time_limit(50);
}



function curl_https($url, $data, $timeout = 30, $head=array(),$re=true) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    // curl_setopt($ch, CURLOPT_PROXY, '115.159.127.208:3128');
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

function getSubstr($str, $leftStr, $rightStr) {
    $left = strpos($str, $leftStr);
    //echo '<br>左边:'.$left;
    $right = strpos($str, $rightStr, $left);
    //echo '<br>右边:'.$right;
    if ($left < 0 or $right < $left) return '';
    return substr($str, $left + strlen($leftStr) , $right - $left - strlen($leftStr));
}

function Post($user,$pwd,$r) {
    $q = "select * from user where username='$user'";
    $rs = mysql_query($q);
    $row = mysql_fetch_array($rs);
    $cookie = $row['cookie'];
    //echo "cookie:".$cookie;
    $header = 'Cookie:' . $cookie;
    $url = 'http://appsale.plateno.com/lottery/signIn';
    $data = ' ';
    $response = curl_https($url, $data, 30, array($header));
    // echo $response;
    if (strpos($response, "您已经签到过了") > 0 or strpos($response, "100") > 0) {
        $succtime = mktime();
        $succnum=$row[9]+1;
        mysql_query("update user set succnum='$succnum',succtime='$succtime' ,info='succ' where username='$user'");
        mysql_free_result($rs);
        echo "succ";
    } else{
        $failnum=$row[10]+1;
        // echo "<br>r=".$r;
        if($r==0){
            // echo "<br>cookie无效 开始登陆<br>";
            GetNewCookie($user,$pwd);  //重新登陆并获取Cookie
        }
    }

} 
    // 




function GetNewCookie($user,$pwd) {
        if(CheckCookie(Login($user,$pwd))){
            post($user,$pwd,1);
        }else{
            mysql_query("update user set info='fail',where username='$user'");
        }
    }
function CheckCookie($reult){
     //检查cookie的有效性
        //echo '<br>'.$response;
        $data = 'token=' . $reult['token'];
        $user=$reult['user'];
        // echo $data;
        $url = 'http://appsale.plateno.com/viewRedirect/signin_new.html?v=3&ver=5.1.6&source=android_a';
        //开始重试验证Cookie

            $response = curl_https($url, $data, 20);
            //sae_debug('获取活动页面的数据：'.$response);
            // echo '<br>' . $response;
            preg_match('/Set-Cookie:(.*);/iU', $response, $str); //正则匹配
            $cookie = $str[1]; //获得COOKIE（SESSIONID）
           // echo "<br>".$cookie;
            $header = 'Cookie:' . $cookie;          //'Cookie:' . $cookie;
            $url = 'http://appsale.plateno.com/check_login';
            $data = ' ';
            $response = curl_https($url, $data, 30, array($header),FALSE);
            //$res=substr($response,strpos($response,'{'));
            //echo $res;
            // echo $response;
            // echo 'header为:'.$header;
            $temp1=json_decode($response,true);
            if ($temp1['msgCode']==100) {
                //Cookie有效  // echo '<br>位置为'.strpos($response, 'msgCode":100');
                $res=mysql_query("update user set cookie='$cookie' where username='$user'");
                return true;
            } else{
                mysql_query("update user set info='fail' ,failnum=failnum+1 where username='$user'");
                return FALSE;
            }
        
        //重试失败
        

}

function Login($user,$pwd){
    $url = 'https://app5.plateno.com:9951/authority/login';
    $data = '{"sign":"0d721cf76c3b9615e652e9f6122d60d5","username":"' . $user . '","clientInfo":{"appVersion":"5.0.4","channelId":"262810","deviceId":"00000000-650a-e532-0d38-091f4bb985a8","hardwareModel":"m1 note","os":"android","systemVersion":"4.4.4","versionCode":"201520601"},"password":"' . $pwd . '"}';

        $response = curl_https($url, $data, 20,null,FALSE);
        // echo $response;
        $temp1=json_decode($response,true);
        if($temp1['msgCode']==100){
            // echo "<br>aaaaaaaaaaaaaaaaaa<br>";
            $id = $temp1['result']['member']['memberId'];
            $realname = $temp1['result']['member']['memberName'];
            $token=$temp1['result']['token'];
            $userlevel=$temp1['result']['member']['memberTypeName'];
            mysql_query("update user set id='$id' , realname='$realname' , userlevel='$userlevel' where username='$user'");
            // echo "<br>登陆成功";
            // echo "<br>".$token;
            $reult['token']=$token;
            $reult['user']=$user;
            return $reult;
        }else{
            // echo "<br>123123123123123123132<br>";
            mysql_query("update user set info='fail' ,failnum=failnum+1 where username='$user'");
        }
}


?>
