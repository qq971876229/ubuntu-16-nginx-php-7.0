<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - 登录</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    
   

    <link rel="shortcut icon" href="favicon.ico"> <link href="css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="css/font-awesome.css?v=4.4.0" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css?v=4.1.0" rel="stylesheet">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    
</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen  animated fadeInDown">
        <div>
            <div>

           

            </div>
       

            <form class="m-t" role="form" action="index.php?_url=/mgr/user/mgr_user_add" method="post">
                <div class="form-group">
                    <input  class="form-control" placeholder="用户名"  name="user_name" required="">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="新密码" name="new_user_pass1" required="">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="重复密码" name="new_user_pass2"  required="">
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">确定</button>
                <small>{{mess}}</small>


               
                </p>

            </form>
        </div>
    </div>

    <!-- 全局js -->
    <script src="js/jquery.min.js?v=2.1.4"></script>
    <script src="js/bootstrap.min.js?v=3.3.6"></script>

    
    

</body>

</html>
