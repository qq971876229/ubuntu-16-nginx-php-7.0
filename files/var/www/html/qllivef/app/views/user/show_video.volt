<!DOCTYPE html>
<html>

<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  
  <title>个人秀展示</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  
  
  <link rel="shortcut icon" href="favicon.ico">
  <link href="css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
  <link href="css/font-awesome.css?v=4.4.0" rel="stylesheet">
  <link href="css/plugins/iCheck/custom.css" rel="stylesheet">
  <link href="css/animate.css" rel="stylesheet">
  {#<link href="css/style.css?v=4.1.0" rel="stylesheet">#}
  
  
  <script src="http://cdn.bootcss.com/blueimp-md5/1.1.0/js/md5.js"></script>
  <script src="http://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
  
  <link href="js/bootstrap-paginator/css/bootstrap.css" rel="stylesheet">
  <script type="text/javascript" src="js/bootstrap-paginator/bootstrap-paginator.js"></script>
  
  
  <script src="js/base.js?v=1.0.0"></script>
  <script src="js/juicer.js"></script>
  
  <script src="js/layer/layer.js"></script>
  
  <!--[if lt IE 9]>
  <meta http-equiv="refresh" content="0;ie.html"/>
  <![endif]-->
  
  <style>
    video {
      height: 500px;
    }
  </style>


</head>

<body class="gray-bg">

<video src="{{ video_url }}" controls="controls" autoplay="autoplay">
  您的浏览器不支持 video 标签。
</video>


<h1>
  <a href="javascript:;" class="delete_video" item_id="{{ item_id }}" uid="{{ uid }}">删除视频</a>
</h1>


</body>
<script type="text/javascript">

  $(".delete_video").click(function () {

      var url = "./index.php?_url=/mgr/api/delete_record";
      var id = $(this).attr("item_id");
      var uid = $(this).attr("uid");

      $.post(url, {"id": id, "table": "user_video"}, function (result) {
          window.location.href="./index.php?_url=/mgr/user/photo_list&uid="+uid;
      });
  });
</script>

</html>
