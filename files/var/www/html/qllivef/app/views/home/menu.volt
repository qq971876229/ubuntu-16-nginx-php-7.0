<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="renderer" content="webkit">
  
  <title>{{ app_name }} -主页</title>
  
  <meta name="keywords" content="">
  <meta name="description" content="">
  
  <!--[if lt IE 9]>
  <meta http-equiv="refresh" content="0;ie.html"/>
  <![endif]-->
  
  <link rel="shortcut icon" href="favicon.ico">
  <link href="css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
  <link href="css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
  <link href="css/animate.css" rel="stylesheet">
  <link href="css/style.css?v=4.1.0" rel="stylesheet">
</head>

<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
<div id="wrapper">
  <!--左侧导航开始-->
  <nav class="navbar-default navbar-static-side" role="navigation">
    <div class="nav-close"><i class="fa fa-times-circle"></i>
    </div>
    <div class="sidebar-collapse">
      <ul class="nav" id="side-menu">
        
        <li class="nav-header">
          
          <div class="dropdown profile-element">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <span class="clear">
                    <span class="block m-t-xs" style="font-size:20px;">
                        <i class="fa fa-area-chart"></i>
                        <strong class="font-bold">{{ user_info.login_name }}</strong>
                    </span>
                </span>
            </a>
          </div>
          
          <div class="logo-element">
          </div>
        
        </li>
        
        <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
          <span class="ng-scope">分类</span>
        </li>
        <li>
          
          <a class="J_menuItem" href="./index.php?_url=/mgr/home/index&home=1">
            <i class="fa fa-home"></i>
            <span class="nav-label">主页</span>
          </a>
        
        </li>
        <li>
          
          <a href="#">
            
            <i class="fa fa fa-bar-chart-o"></i>
            <span class="nav-label">系统设置</span>
            <span class="fa arrow"></span>
          
          </a>
          
          <ul class="nav nav-second-level">
            
            <li>
              <a class="J_menuItem" href="./index.php?_url=/mgr/setting/index&id=1">设置列表</a>
            </li>
            
            <li>
              <a class="J_menuItem" href="./index.php?_url=/mgr/sms/index&id=1">短信列表</a>
            </li>
            
            {#<li>#}
            {#<a class="J_menuItem" href="./index.php?_url=/mgr/user/edit_pass">修改密码</a>#}
            {#</li>#}
            
            <li>
              <a class="J_menuItem" href="./index.php?_url=/mgr/home/logout">退出登录</a>
            </li>
          
          </ul>
        </li>
        <li class="line dk"></li>
        <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
          <span class="ng-scope">用户</span>
        </li>
        <li>
          <a href="mailbox.html">
            <i class="fa fa-envelope"></i>
            <span class="nav-label">后台用户</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a class="J_menuItem" href="./index.php?_url=/mgr/user/mgr_user_add">添加用户</a>
            </li>
            <li><a class="J_menuItem" href="./index.php?_url=/mgr/user/mgr_user_list">用户列表</a>
            </li>
          
          </ul>
        </li>
        <li>
          <a href="#"><i class="fa fa-edit"></i>
            <span class="nav-label">用户管理</span>
            <span class="fa arrow"></span>
          </a>
          
          <ul class="nav nav-second-level">
            <li><a class="J_menuItem" href="./index.php?_url=/mgr/user/user_list">用户</a>
            </li>
          </ul>
          
          <ul class="nav nav-second-level">
            <li><a class="J_menuItem" href="./index.php?_url=/mgr/user/live_apply">主播申请</a>
            </li>
          </ul>
          
          <ul class="nav nav-second-level">
            <li><a class="J_menuItem" href="./index.php?_url=/mgr/user/feedback">投诉建议</a>
            </li>
          
          </ul>
          
          <ul class="nav nav-second-level">
            <li><a class="J_menuItem" href="./index.php?_url=/mgr/user/reported">举报</a>
            </li>
          </ul>
        
        </li>
        
        <li>
          <a href="#"><i class="fa fa-picture-o"></i> <span class="nav-label">推广</span><span
                class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li><a class="J_menuItem" href="./index.php?_url=/mgr/mess/send">发布消息</a>
            </li>
            <li><a class="J_menuItem" href="./index.php?_url=/mgr/mess/list">消息记录</a>
            </li>
            <li>
              <a class="J_menuItem" href="./index.php?_url=/mgr/mess/ad_list">广告</a>
            </li>
          </ul>
        </li>
        
        <li class="line dk"></li>
        <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
          <span class="ng-scope">财务</span>
        </li>
        
        <li>
          <a href="#"><i class="fa fa-desktop"></i> <span class="nav-label">推广管理</span><span
                class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li><a class="J_menuItem" href="./index.php?_url=/mgr/share/list">分享推广</a>
            </li>
          
          
          </ul>
        </li>
        
        
        <li>
          <a href="#">
            <i class="fa fa-flask"></i>
            <span class="nav-label">数据管理</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            
            <li>
              <a class="J_menuItem" href="./index.php?_url=/mgr/pay/add_money">充值</a>
            <li>
              
              <a class="J_menuItem" href="./index.php?_url=/mgr/pay/recharge">充值记录</a>
            </li>
            
            <li>
              <a class="J_menuItem" href="./index.php?_url=/mgr/pay/cash">提现记录 </a>
            </li>
            
            <li>
              <a class="J_menuItem" href="./index.php?_url=/mgr/pay/manage">充值管理 </a>
            </li>
            
            <li>
              <a class="J_menuItem" href="./index.php?_url=/mgr/pay/balance">余额 </a>
            </li>
          
          </ul>
        </li>
      
      
      </ul>
    </div>
  </nav>
  <!--左侧导航结束-->
  <!--右侧部分开始-->
  <div id="page-wrapper" class="gray-bg dashbard-1">
    <div class="row border-bottom">
    
    </div>
    <div class="row J_mainContent" id="content-main">
      <iframe id="J_iframe" width="100%" height="100%" src="./index.php?_url=/mgr/home/index" frameborder="0"
              data-id="index_v1.html" seamless></iframe>
    </div>
  </div>
  <!--右侧部分结束-->
</div>

<!-- 全局js -->
<script src="js/jquery.min.js?v=2.1.4"></script>
<script src="js/bootstrap.min.js?v=3.3.6"></script>
<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="js/plugins/layer/layer.min.js"></script>

<!-- 自定义js -->
<script src="js/hAdmin.js?v=4.1.0"></script>
<script type="text/javascript" src="js/index.js"></script>

<!-- 第三方插件 -->
<script src="js/plugins/pace/pace.min.js"></script>

</body>

</html>
