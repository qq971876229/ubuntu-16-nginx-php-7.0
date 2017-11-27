<!DOCTYPE html>
<html>

<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>聊天列表</title>
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
          <h5>聊天列表</h5>
        </div>
        <div class="ibox-content">
          
          
          <div class="project-list">
            
            <table class="table table-hover">
              <tbody>
              <tr>
                <th width="10%">id</th>
                <th width="10%">主播id</th>
                <th width="10%">用户id</th>
                <th width="10%">主播最近时间</th>
                <th width="10%">用户最近时间</th>
                <th width="10%">开始时间</th>
                <th width="10%">结束时间</th>
                <th width="10%">直播中是否充值</th>
                <th width="10%">视频费用</th>
                <th width="10%">实扣</th>
                <th width="10%">礼物</th>
                <th width="10%">状态</th>
              </tr>
              
              {% for item in list %}
                
                <tr>
  
                  <td><a href="./index.php?_url=/mgr/money/vod&session_id={{ item.id }}">{{ item.id }}</a></td>
                  <td>  {{ item.live_id }}  </td>
                  <td><a href="./index.php?_url=/mgr/user/user_detail&id={{ item.view_id }}"> {{ item.view_id }} </a></td>
                  <td>  {{ date('Y-m-d H:i:s',item.live_ttl-30) }}  </td>
                  <td>  {{ date('Y-m-d H:i:s',item.view_ttl-30) }}  </td>
                  <td>  {{ date('Y-m-d H:i:s',item.begin_time) }}  </td>
                  <td>  {{ date('Y-m-d H:i:s',item.end_time) }}  </td>
                  <td>  {{ item.is_recharge }} </td>
                  <td><a href="./index.php?_url=/mgr/money/vod&session_id={{ item.id }}">  {{ item.money }} </a> </td>
                  <td><a href="./index.php?_url=/mgr/money/vod&session_id={{ item.id }}">  {{ item.value }} </a> </td>
                  <td>  {{ item.gift_money }}  </td>
                  <td>
                    {% if item.state == 0 %}
                      发起请求
                      {% elseif item.state == 1 %}
                      通话中
                      {% elseif item.state == 2 %}
                      已完成
                      {% elseif item.state == 3 %}
                      未接通
                    {% endif %}
                  </td>
                
                
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


</body>

</html>
