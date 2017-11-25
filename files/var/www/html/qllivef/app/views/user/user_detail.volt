<!DOCTYPE html>
<html>

<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  
  <title>用户详情</title>
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
          <h5>用户详情</h5>
        </div>
        <div class="ibox-content">
          
          
          <div class="project-list">
            
            <table class="table table-hover">
              <tbody>
              
              <tr>
                <th width="50%">注册时间</th>
                <th width="50%">{{ user_info.created_at }}</th>
              </tr>
              
              <tr>
                <th width="50%">UID</th>
                <th width="50%">{{ uid }}</th>
              </tr>

              <tr>
                <th width="50%">手机号码</th>
                <th width="50%">{{ user_info.login_moblie }}</th>
              </tr>
              
              <tr>
                <th width="50%">身份</th>
                <th width="50%">{{ user.auth }}</th>
              </tr>
              
              {% if user.is_live==1 %}
                
                <tr>
                  <th width="50%">价格</th>
                  <th width="50%">
                    <input type="text" name="price" value="{{ user.price }}">
                    <a href="javascript:;" uid="{{ uid }}" id="edit_price">保存</a>
                  </th>
                </tr>
              
              {% endif %}
              
              <tr>
                <th width="50%">性别</th>
                <th width="50%">
  
                  <select name="sex" id="sex">
                    <option value="">请选择</option>
                    <option value="Gender_Type_Male"  {% if user.sex=="Gender_Type_Male" %} selected {% endif %}>男</option>
                    <option value="Gender_Type_Female"  {% if user.sex=="Gender_Type_Female" %} selected {% endif %}>女</option>
                  </select>
  
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  <a href="javascript:;" uid="{{ uid }}" id="edit_sex">保存</a>

                </th>
              </tr>
              
              <tr>
                <th width="50%">生日</th>
                <th width="50%">
                  <input type="text" name="birthday" value="{{ date("Y-m-d",user.birthday) }}">
                  <a href="javascript:;" uid="{{ uid }}" id="edit_birthday">保存</a>
                </th>
              </tr>
              
              <tr>
                <th width="50%">昵称</th>
                <th width="50%">
                  <input type="text" name="nickname" value="{{ user.nickname }}">
                  <a href="javascript:;" uid="{{ uid }}" id="edit_nickname">保存</a>
                </th>
              </tr>

              <tr>
                <th width="50%">个性签名</th>
                <th width="50%">
                  <input type="text" name="signature" value="{{ signature }}">
                  <a href="javascript:;" uid="{{ uid }}" id="edit_signature">保存</a>
                </th>
              </tr>
              
              <tr>
                <th width="50%">头像</th>
                <th width="50%">
                  <a href="./index.php?_url=/mgr/user/photo_list&uid={{ uid }}">
                    <img src="{{ user.img }}" alt="" style="width: 100px;">
                  </a>
                  <a href="javascript:;" uid="{{ uid }}" id="delete_portrait" style="margin-left: 100px;">
                    删除头像
                  </a>
                </th>
              </tr>
              
              <tr>
                <th width="50%">累计充值</th>
                <th width="50%">{{ total_recharge }}</th>
              </tr>
              
              <tr>
                <th width="50%">累计提现金额</th>
                <th width="50%">{{ total_withdrawal }}</th>
              </tr>
              
              <tr>
                <th width="50%">余额</th>
                <th width="50%">{{ balance }}</th>
              </tr>
              
              <tr>
                <th width="50%">邀请奖励收入</th>
                <th width="50%">{{ recommend_money }}</th>
              </tr>
              
              <tr>
                <th width="50%">视频通话收入</th>
                <th width="50%">{{ talk_money }}</th>
              </tr>
              
              <tr>
                <th width="50%">礼物收入</th>
                <th width="50%">{{ gift_money }}</th>
              </tr>
              
              
              <tr>
                <th width="50%">邀请人数</th>
                <th width="50%">
                  {% if recommend_num>0 %}
                    <a href="./index.php?_url=/mgr/user/recommend_list&id={{ uid }}">
                      {{ recommend_num }}
                    </a>
                  {% else %}
                    {{ recommend_num }}
                  {% endif %}
                </th>
              </tr>
              
              
              <tr>
                <th width="50%">推荐人</th>
                <th width="50%">
                  <input type="text" name="reference_id" value="{{ user_info.reference }}">
                  <a href="javascript:;" uid="{{ uid }}" id="edit_reference_id">保存</a>
                  
                  {#&nbsp;&nbsp;&nbsp;&nbsp;#}
                  {#推荐奖励有效期: {{ date("Y-m-d H:i:s",user_info.reference_time) }}#}
  {##}
                  {#&nbsp;&nbsp;&nbsp;&nbsp;#}
                  {#<a href="javascript:;" uid="{{ uid }}" id="edit_reference_time">清除有效期</a>#}
                  
                </th>
              </tr>
              
              
              {#<tr>#}
              {#<th width="50%">好友数量</th>#}
              {#<th width="50%">{{ user_info.created_at }}xxxx</th>#}
              {#</tr>#}
              
              
              <tr>
                <th width="50%">历史通话时长</th>
                <th width="50%">{{ talk_long }} 分钟</th>
              </tr>
              
              <tr>
                <th width="50%">历史通话次数</th>
                <th width="50%">{{ talk_times }}</th>
              </tr>
              
              <tr>
                <th width="50%">接通次数</th>
                <th width="50%">{{ success_talk_times }}</th>
              </tr>
              
              <tr>
                <th width="50%">未接通次数</th>
                <th width="50%">{{ fail_talk_times }}</th>
              </tr>
              
              <tr>
                <th width="50%">接通率</th>
                <th width="50%">{{ answer_rate }}</th>
              </tr>
              
              <tr>
                <th width="50%">通话状态</th>
                <th width="50%">
                  {{ talk_status }}
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <a href="javascript:;" uid="{{ uid }}" id="close_vod_session">结束通话 </a>
                </th>
              
              </tr>
              
              
              <tr>
                <th width="50%">当日在线时长</th>
                <th width="50%">{{ online_time_long }}秒</th>
              </tr>
              
              
              {#<tr>#}
              {#<th width="50%">历史在线时长</th>#}
              {#<th width="50%">{{ user_info }}</th>#}
              {#</tr>#}
              
              {#<tr>#}
              {#<th width="50%">语音/文字发送数量</th>#}
              {#<th width="50%">{{ user_info }}</th>#}
              {#</tr>#}
              
              {#<tr>#}
              {#<th width="50%">账户名细</th>#}
              {#<th width="50%">{{ user_info }}</th>#}
              {#</tr>#}
              
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

    $("#edit_birthday").click(function () {

        var url = "./index.php?_url=/mgr/api/edit_record";
        var uid = $(this).attr("uid");
        var birthday = $("input[name=birthday]").val();
        birthday = Date.parse(birthday);
        birthday = birthday / 1000;

        $.post(url, {
            "id": uid,
            "uid": uid,
            "table": "users",
            "field": "birthday='" + birthday + "'",
            "birthday": birthday
        }, function (result) {

            window.location.reload();

        });
    });

    $("#edit_reference_time").click(function () {

        var url = "./index.php?_url=/mgr/api/edit_record";
        var uid = $(this).attr("uid");

        $.post(url, {
            "id": uid,
            "uid": uid,
            "table": "users",
            "field": "reference_time=" + 0,
        }, function (result) {

            window.location.reload();

        });
    });


    $("#edit_sex").click(function () {

        var url = "./index.php?_url=/mgr/api/edit_record";
        var uid = $(this).attr("uid");
        var sex = $("#sex").val();

        $.post(url, {
            "id": uid,
            "uid": uid,
            "table": "users",
            "field": "sex='" + sex + "'",
            "sex": sex
        }, function (result) {

            window.location.reload();

        });
    });

    

    $("#edit_price").click(function () {

        var url = "./index.php?_url=/mgr/api/edit_record";
        var uid = $(this).attr("uid");
        var price = $("input[name=price]").val();

        $.post(url, {
            "id": uid,
            "uid": uid,
            "table": "users",
            "field": "price=" + price,
            "price": price
        }, function (result) {

            window.location.reload();

        });
    });


    $("#edit_nickname").click(function () {

        var url = "./index.php?_url=/mgr/api/edit_record";
        var uid = $(this).attr("uid");
        var nickname = $("input[name=nickname]").val();

        $.post(url, {
            "id": uid,
            "uid": uid,
            "table": "users",
            "field": "nickname='" + nickname + "'",
            "nickname": nickname
        }, function (result) {

            window.location.reload();

        });
    });

    $("#edit_signature").click(function () {

        var url = "./index.php?_url=/mgr/api/edit_record";
        var uid = $(this).attr("uid");
        var signature = $("input[name=signature]").val();

        $.post(url, {
            "id": uid,
            "uid": uid,
            "table": "users",
            "field": "signature='" + signature + "'",
            "signature": signature
        }, function (result) {

//            window.location.reload();

        });
    });


    $("#delete_portrait").click(function () {

        var uid = $(this).attr("uid");
        var url = "./index.php?_url=/mgr/api/delete_portrait";

        $.post(url, {"uid": uid});


        console.log(uid);

    });


    $("#edit_reference_id").click(function () {

        var url = "./index.php?_url=/mgr/user/edit_reference_id";
        var uid = $(this).attr("uid");
        var reference = $("input[name=reference_id]").val();

        $.post(url, {"uid": uid, "reference": reference}, function (result) {

            window.location.reload();

        });
    });

   

    $("#close_vod_session").click(function () {

        if (!confirm("你确定关闭通话吗?")) {
            return false;
        }

        var url = "./index.php?_url=/vod/end_api";
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
