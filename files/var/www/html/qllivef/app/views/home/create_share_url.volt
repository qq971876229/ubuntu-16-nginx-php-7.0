<!DOCTYPE <!DOCTYPE html>
<html>
<head>
  <title></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <link rel="stylesheet" href="css/share/css/style.css">
</head>
<body>
<div class="main">
  <div class="main-top">
  
  </div>
  <div class="content">
    <div class="invite">
    
    </div>
    <div class="register">
      <form action="./index.php?_url=/mgr/home/create_share_url_do" method="post">
        <ul class="form-info">
          <li><input type="text" id="uid" placeholder="用户Id" name="uid" class="in-tel"></li>
          <li>
            <input type="submit" value="生成分享链接">
          </li>
        </ul>
      </form>
    </div>
  </div>

</div>

{#<script src="css/share/js/xl.js"></script>#}
<script src="http://libs.baidu.com/jquery/2.1.4/jquery.min.js"></script>
{#<script src="css/share/js/base.js"></script>#}


<div class="wrap" id="wrap">
  <div class="wrap-content">
    <a class="close-btn" href="javascript:void(0)" onclick="close()">X</a>
    <span class="wrap-span">你已经注册成功，请下载APP</span>
    <div class="down-btn wrap-down">
      <a id="url" href="">APP下载</a>
    </div>
  </div>
</div>

<script>


    $(function () {

        init();
    });


    var id = getQueryString("id");

    function send_code(t) {

        var mobile = $("#mobile").val();

        if (mobile.length != 11) {
            alert("手机长度不等于11");
        }

        post("/user/send_login_sms", {moblie: mobile}, function (data) {

            setTime(t);

        });


    }


    function login() {
        var mobile = $("#mobile").val();
        var code = $("#code").val();


        if (mobile.length != 11) {
            alert("手机长度不等于11");
            return;
        }


        if (code.length < 4) {
            alert("请填验证码");
            return;
        }

        post("/user/login_sms", {moblie: mobile, code: code, reference: id}, function (data) {
            getRegister();
        });


    }


</script>


<script type="text/javascript">


    function init() {


        post("/user/get_share_info2", {uid: id}, function (data) {
            //alert("1")
            $("#nickname").html(data.nickname);
            $("#app_name_cn").html(data.app_name_cn);
            $('#url').attr('href', data.app_download_url);
            $('#url1').attr('href', data.app_download_url);


            var u = navigator.userAgent;
            var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1;
            var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);



            if (isiOS) {
                // alert("3")
                //alert(data.app_download_url_ios);
                $('#url').attr('href', data.app_download_url_ios);
                $('#url1').attr('href', data.app_download_url_ios);
            }


        });


    }


</script>

</body>
</html>