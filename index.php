<?php
require_once ('conn.php');  
session_start();//初始化session
if (isset($_SESSION['user'])){
}else{
header("Location: login.html"); 
 exit();
}
$user=$_SESSION['user'];
$query = mysql_query("select * from user where username='$user'");  
$row = mysql_fetch_array($query);
$_SESSION['userlevel'] = $row['userlevel']; 
$_SESSION['realname'] = $row['realname'];
$_SESSION['succnum']=$row['succnum'];
$_SESSION['failnum']=$row['failnum'];
$_SESSION['succtime']=$row['succtime'];
$_SESSION['autosign']=$row['autosign'];
$_SESSION['autocj']=$row['autocj'];
$_SESSION['info']=$row['info'];
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>铂涛会自动签到</title>
<!-- Le styles -->
<link href="http://lib.sinaapp.com/js/bootstrap/latest/css/bootstrap.min.css" rel="stylesheet">
<link href="css/main.css" rel="stylesheet">
<link href="css/font-style.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/sweet-alert.css">
<link href="css/passstyle.css" rel='stylesheet' type='text/css' />

<!-- <script type="text/javascript" src="http://lib.sinaapp.com/js/bootstrap/latest/js/bootstrap.min.js"></script>  -->
<!-- <link href="css/flexslider.css" rel="stylesheet"> -->
<link href="css/bootstrap-switch.css" rel="stylesheet">
<script src="http://lib.sinaapp.com/js/jquery/2.0.3/jquery-2.0.3.min.js"></script>
<script src="js/bootstrap-switch.js"></script>
<script src="js/sweet-alert.js"></script>
<style type="text/css">
body {
	padding-top: 60px;
}
</style>

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

<!-- Le fav and touch icons -->

<!-- Google Fonts call. Font Used Open Sans & Raleway -->
<!-- <link href="http://fonts.useso.com/css?family=Raleway:400,300" rel="stylesheet" type="text/css"> -->
<!-- <link href="http://fonts.useso.com/css?family=Open+Sans" rel="stylesheet" type="text/css"> -->

</head>
<body>

<!-- NAVIGATION MENU -->
<div class="navbar-nav navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="navbar-brand" href="index.html"><img src="img/logo30.png" alt=""> 铂涛会自动签到</a> </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li class="active"><a href="index.html"><i class="icon-home icon-white"></i>管理</a></li>
        <li><a href="loginout.php"><i class="icon-folder-open icon-white"></i> 退出</a></li>
      </ul>
    </div>
    <!--/.nav-collapse --> 
  </div>
</div>
<div class="container" >
<!-- ///////////////// --> 

<!-- FIRST ROW OF BLOCKS -->
<div class="row">
<!-- ///////////////////// -->
<div id="pass">
  <div class="form" id="form" >
    <div class="close" id="close"> </div>
    <div class="head-info">
      <label class="lbl-1"> </label>
      <label class="lbl-2"> </label>
      <label class="lbl-3"> </label>
    </div>
    <div class="clear"> </div>
    <div class="avtar"> <img src="images/avtar.png" /> </div>
    <div class="upPass" id="login">
      <div class="key">
        <input type="password" id="password">
      </div>
      <div class="signin">
        <input type="submit" value="修改" id="passbt">
      </div>
    </div>
  </div>
</div>
<!-- USER PROFILE BLOCK -->
<div class="col-sm-3 col-lg-3">
  <div class="dash-unit">
    <dtitle>用户信息</dtitle>
    <hr>
    <div class="thumbnail"> <img src="img/face80x80.jpg" alt="Marcel Newman" class="img-circle"> </div>
    <!-- /thumbnail -->
    <h1><?php echo $_SESSION['realname']?></h1>
    <h3><?php echo $_SESSION['userlevel']?></h3>
    <br>
    <div class="info-user">
      <input type="submit" id="userpass" name="submit" value="修改密码">
    </div>
  </div>
</div>

<!-- DONUT CHART BLOCK -->
<div class="col-sm-3 col-lg-3">
  <div class="dash-unit">
    <dtitle>签到成功率</dtitle>
    <hr>
    <div id="load"></div>
    <h2>
      <?php if($_SESSION['failnum']==0) {
        	echo "100%";
        	echo '<script> var succjs=100</script>';
        	echo '<script> var failjs=0</script>';
        	}else{	$all=$_SESSION['failnum']+$_SESSION['succnum'];
        		$pie=round(100*$_SESSION['succnum']/$all);
        		$pie2=100-$pie;
        		echo $pie."%";
        		echo '<script> var succjs='.$pie.'</script>';
        		echo '<script> var failjs='.$pie2.'</script>';
        		}?>
    </h2>
  </div>
</div>

<!-- DONUT CHART BLOCK -->
<div class="col-sm-3 col-lg-3">
  <div class="half-unit">
    <dtitle>自动签到</dtitle>
    <hr>
    <div class="switch">
      <input  type="checkbox" name="autosign" <?php if($_SESSION['autosign']==1){echo 'checked';}?> data-size="normal">
    </div>
  </div>
  <!--    </div>
        <div class="col-sm-3 col-lg-3">-->
  <div class="half-unit">
    <dtitle>自动抽奖</dtitle>
    <hr>
    <div class="switch">
      <input type="checkbox" name="autocj" <?php if($_SESSION['autocj']==1){echo 'checked';}?> data-size="normal">
    </div>
  </div>
</div>
<div class="col-sm-3 col-lg-3">

<!-- LOCAL TIME BLOCK -->
<div class="half-unit">
  <dtitle>上次签到时间</dtitle>
  <hr>
  <div class="clockcenter">
    <digiclock>
      <?php if($_SESSION['succtime']==0){
	echo "还未进行过签到";
}else{
	echo date("Y-m-d G:i:s",$_SESSION['succtime']);
} ?>
    </digiclock>
  </div>
</div>
<!-- SERVER UPTIME -->
<div class="half-unit">
<dtitle>上次签到状态</dtitle>
<hr>
<div class="cont">
  <p><?php 
          if($_SESSION['info']=='succ'){
          	echo '<img src="img/up.png" alt="">

            <bold>成功</bold>';
          }else{
          	echo '<img src="img/down.png" alt="">

            <bold>失败</bold>';
          }
          ?></p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /container -->
<div id="footerwrap">
  <footer class="clearfix"></footer>
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-lg-12">
        <p><img src="img/logo.png" alt=""></p>
        <p>Blocks Dashboard Theme - Crafted With Love - Copyright 2013</p>
      </div>
    </div>
    <!-- /row --> 
  </div>
  <!-- /container --> 
</div>
<!-- /footerwrap --> 

<!-- Le javascript
    ================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 
<!-- <script type="text/javascript" src="http://lib.sinaapp.com/js/bootstrap/latest/js/bootstrap.min.js"></script>  --> 
<script type="text/javascript" src="js/dash-charts.js"></script> 
<!-- <script type="text/javascript" src="js/gauge.js"></script>  --> 

<!-- NOTY JAVASCRIPT --> 
<!-- <script type="text/javascript" src="js/noty/jquery.noty.js"></script> 
<script type="text/javascript" src="js/noty/layouts/top.js"></script> 
<script type="text/javascript" src="js/noty/layouts/topLeft.js"></script> 
<script type="text/javascript" src="js/noty/layouts/topRight.js"></script> 
<script type="text/javascript" src="js/noty/layouts/topCenter.js"></script>  --> 

<!-- You can add more layouts if you want --> 
<!-- <script type="text/javascript" src="js/noty/themes/default.js"></script>  --> 
<!-- <script type="text/javascript" src="js/dash-noty.js"></script> This is a Noty bubble when you init the theme--> 
<script type="text/javascript" src="http://lib.sinaapp.com/js/highcharts/4.0.1/highcharts.js"></script> 

<!-- <script src="js/jquery.flexslider.js" type="text/javascript"></script>  --> 
<!-- <script type="text/javascript" src="js/admin.js"></script> --> 
<script>
$("#close").on('click',function(){
	$("#pass").fadeOut(200);
});
$("#passbt").on('click',function(){
	var pass=$("#password").val();  
        $.ajax({
        type: "POST",  
        url: "config.php?action=pass",  
        dataType: "json",  
        data: {"pass":pass},  
        success: function(json){  
            if(json.success==1){  
            	sweetAlert({
                title: "修改成功",
                 text: "点击确定关闭窗口",
                  type: "success",
                  showCancelButton: false,
  confirmButtonColor: "#DD6B55",
  confirmButtonText: "确定",
  closeOnConfirm: true,
}, function(){
	$("#pass").fadeOut(200);
});
            }else{  sweetAlert({
                title: "修改失败",
                 text: "请重试",
                  type: "error",
                  showCancelButton: false,
  confirmButtonColor: "#DD6B55",
  confirmButtonText: "重试",
  closeOnConfirm: true,
}, function(){
});
            }  
        }  ,
        error:function(){sweetAlert({
                title: "修改失败",
                 text: "请重试",
                  type: "error",
                  showCancelButton: false,
  confirmButtonColor: "#DD6B55",
  confirmButtonText: "重试",
  closeOnConfirm: true,
}, function(){
});}
    }); 
});
$("#userpass").on('click',function(){
	$("#pass").fadeIn(200);
});
$('input[name="autosign"]').on('switchChange.bootstrapSwitch', function(event, state) {
          $.ajax({
        type: "POST",  
        url: "config.php?action=sign",  
        dataType: "json",  
        data: {"autosign":state},  
        success: function(json){  
            if(json.success==1){  
            }else{  sweetAlert({
                title: "提交失败",
                 text: "请重试",
                  type: "error",
                  showCancelButton: false,
  confirmButtonColor: "#DD6B55",
  confirmButtonText: "重试",
  closeOnConfirm: true,
}, function(){
	$('input[name="autosign"]').bootstrapSwitch('state', !state, true);
});
            }  
        }  ,
        error:function(){sweetAlert({
                title: "提交失败",
                 text: "请重试",
                  type: "error",
                  showCancelButton: false,
  confirmButtonColor: "#DD6B55",
  confirmButtonText: "重试",
  closeOnConfirm: true,
}, function(){
	$('input[name="autosign"]').bootstrapSwitch('state', !state, true);
});}
    }); 
});

//
$('input[name="autocj"]').on('switchChange.bootstrapSwitch', function(event, state) {
          $.ajax({
        type: "POST",  
        url: "config.php?action=cj",  
        dataType: "json",  
        data: {"autocj":state},  
        success: function(json){  
            if(json.success==1){  
            }else{  sweetAlert({
                title: "提交失败",
                 text: "请重试",
                  type: "error",
                  showCancelButton: false,
  confirmButtonColor: "#DD6B55",
  confirmButtonText: "重试",
  closeOnConfirm: true,
}, function(){
	$('input[name="autocj"]').bootstrapSwitch('state', !state, true);
});
            }  
        }  ,
        error:function(){sweetAlert({
                title: "提交失败",
                 text: "请重试",
                  type: "error",
                  showCancelButton: false,
  confirmButtonColor: "#DD6B55",
  confirmButtonText: "重试",
  closeOnConfirm: true,
}, function(){
	$('input[name="autocj"]').bootstrapSwitch('state', !state, true);
});}
    }); 
});
<!-- ///// -->


</script>
</body>
</html>
