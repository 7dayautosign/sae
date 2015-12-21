<?php
session_start();  
require_once ('conn.php');  
$action = $_GET['action'];  
if ($action == 'sign') {  //登录  
    $stat = stripslashes(trim($_POST['autosign']));  
    if($stat=='true'){
        $stat=1;
    }else{
        $stat=0;
    }
    $user=$_SESSION['user'];
    $rs = mysql_query("update user set autosign='$stat' where username='$user'");  
    if($rs){
        $arr['success'] = 1;  

    }else{
        $arr['success'] = 0;  
    }
    echo json_encode($arr); //输出json数据  
}elseif ($action == 'cj') {
    $stat = stripslashes(trim($_POST['autocj']));  
    if($stat=='true'){
        $stat=1;
    }else{
        $stat=0;
    }
    $user=$_SESSION['user'];
    $rs = mysql_query("update user set autocj='$stat' where username='$user'");  
    if($rs){
        $arr['success'] = 1;  

    }else{
        $arr['success'] = 0;  
    }
    echo json_encode($arr); //输出json数据  
}elseif($action=='pass'){
    $pass=stripslashes(trim($_POST['pass']));  
    $user=$_SESSION['user'];
    $rs = mysql_query("update user set password='$pass' where username='$user'");  
        if($rs){
        $arr['success'] = 1;  

    }else{
        $arr['success'] = 0;  
    }
    echo json_encode($arr);
}

?>