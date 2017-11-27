<!DOCTYPE html>
<html>

<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>用户相册</title>
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

<div class="wrapper wrapper-content animated fadeInUp">
  <div class="row">
    <div class="col-sm-12">
      
      <div class="ibox">
        <div class="ibox-title">
          <h5>用户相册</h5>
        </div>
        <div class="ibox-content">
          
          
          <div class="project-list">
            
            <table class="table table-hover">
              
              <tbody>
              
              {% for item in list %}
                
                <tr>
                  <th width="50%"><img src="{{ item['img'] }}" alt="" style="width: 150px;"></th>
                  {% if item['video'] %}
                    <th width="20%"><a href="./index.php?_url=/mgr/user/show_video&video_url={{ item['video'] }}&item_id={{ item['id'] }}&uid={{ item['uid'] }}" item_id="{{ item['id'] }}">视频</a></th>
                  {% else %}
                    <th width="30%"><a href="javascript:;" item_id="{{ item['id'] }}" class="delete_img">删除图片</a></th>
                  {% endif %}
                </tr>
              
              {% endfor %}
              
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- 全局js -->
<script src="js/jquery.min.js?v=2.1.4"></script>
<script src="js/bootstrap.min.js?v=3.3.6"></script>


<script>

    $(".delete_img").click(function () {

        var url = "./index.php?_url=/mgr/api/delete_record";
        var id = $(this).attr("item_id");
        var reference = $("input[name=reference_id]").val();

        $.post(url, {"id": id, "table": "user_img"}, function (result) {

            window.location.reload();

        });
    });

    $("#close_vod_session").click(function () {

        if (!confirm("你确定关闭通话吗?")) {
            return false;
        }

        var url = "./index.php?_url=/vod/endApi";
        var uid = $(this).attr("uid");

        $.post(url, {"uid": uid}, function (result) {

            window.location.reload();

        });
    });

    function del(id) {

        if (window.confirm('你确定要删除么？')) {
            //alert("确定");
            window.location.href = ".。/index.php?_url=/mgr/user/mgr_user_del&id=" + id;
            return true;
        } else {
            //alert("取消");
            return false;
        }


    }

</script>


</body>

</html>
