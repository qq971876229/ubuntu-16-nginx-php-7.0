<!DOCTYPE html>
<html>

<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  
  <title> 发送消息</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  
  <link rel="shortcut icon" href="favicon.ico">
  <link href="css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
  <link href="css/font-awesome.css?v=4.4.0" rel="stylesheet">
  
  <link href="css/animate.css" rel="stylesheet">
  <link href="css/style.css?v=4.1.0" rel="stylesheet">
  <!--[if lt IE 9]>
  <meta http-equiv="refresh" content="0;ie.html"/>
  <![endif]-->

</head>

<body class="gray-bg">

<div class="middle-box text-center loginscreen  animated fadeInDown">
  <div>
    
    <form class="m-t" role="form" action="index.php?_url=/mgr/mess/send" method="post">
      {##}
      {#<div class="form-group">#}
        {#<select name="send_object" id="send_type">#}
          {#<option value="all_users">所有用户</option>#}
          {#<option value="all_hosts">所有主播</option>#}
          {#<option value="all_views">所有观众</option>#}
        {#</select>#}
      {#</div>#}
      
      <div class="form-group">
        <input type="text" name="content" class="form-control" placeholder="内容" name="content" required="">
      </div>
  
      <div class="form-group">
        <input type="text" name="uid" class="form-control" placeholder="对象uid" name="uid" >
      </div>
      
      <button type="submit" class="btn btn-primary block full-width m-b">确定</button>
      
      <small>{{ mess }}</small>
      
      
      </p>
    
    </form>
  </div>
</div>

<!-- 全局js -->
<script src="js/jquery.min.js?v=2.1.4"></script>
<script src="js/bootstrap.min.js?v=3.3.6"></script>


</body>

</html>
