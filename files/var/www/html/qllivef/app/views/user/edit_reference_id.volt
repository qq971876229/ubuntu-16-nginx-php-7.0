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
                <th width="50%">身份</th>
                <th width="50%">{{ user.auth }}</th>
              </tr>
              
              <tr>
                <th width="50%">性别</th>
                <th width="50%">
                  {% if user.sex=="Gender_Type_Male" %}
                    男
                  {% else %}
                    女
                  {% endif %}
                </th>
              </tr>
              
              <tr>
                <th width="50%">昵称</th>
                <th width="50%">{{ user.nickname }}</th>
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
                <th width="50%">{{ talk_money }}</th>
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
                  <a href="./index.php?_url=/mgr/user/edit_reference_id&id={{ uid }}">{{ user_info.reference }}</a>
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
