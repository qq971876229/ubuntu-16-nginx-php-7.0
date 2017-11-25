<!DOCTYPE html>
<html>

<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!--360浏览器优先以webkit内核解析-->
  
  <title> - 主页示例</title>
  
  <link href="css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
  <link href="css/style.css?v=4.1.0" rel="stylesheet">

</head>

<body class="gray-bg">
<div class="wrapper wrapper-content">
  <div class="row">
    <div class="col-sm-10">
      <div class="row">
        <div class="col-sm-4">
          
          <div class="row row-sm text-center">
            
            <div class="col-xs-6">
              <div class="panel padder-v item">
                {#<div class="h1 text-info font-thin h1">{{ data.view_online_num }}</div>#}
                <div class="h1 text-info font-thin h1">{{ calc.login_view }}</div>
                <span class="text-muted text-xs">今日用户在线数</span>
                <div class="top text-right w-full">
                  <i class="fa fa-caret-down text-warning m-r-sm"></i>
                </div>
              </div>
            </div>
            
            <div class="col-xs-6">
              <div class="panel padder-v item bg-info">
                {#<div class="h1 text-fff font-thin h1">{{ data.live_online_num }}</div>#}
                <div class="h1 text-fff font-thin h1">{{ calc.login_live }}</div>
                <span class="text-muted text-xs">今日主播在线数</span>
                <div class="top text-right w-full">
                  <i class="fa fa-caret-down text-warning m-r-sm"></i>
                </div>
              </div>
            </div>
            
            {#<div class="col-xs-6">#}
              {#<div class="panel padder-v item bg-info">#}
                {#<div class="h1 text-fff font-thin h1">{{ data.view_online_time }}秒</div>#}
                {#<span class="text-muted text-xs">今日平均用户在线时长</span>#}
                {#<div class="top text-right w-full">#}
                  {#<i class="fa fa-caret-down text-warning m-r-sm"></i>#}
                {#</div>#}
              {#</div>#}
            {#</div>#}
            
            {#<div class="col-xs-6">#}
              {#<div class="panel padder-v item bg-info">#}
                {#<div class="h1 text-fff font-thin h1">{{ data.live_online_time }}秒</div>#}
                {#<span class="text-muted text-xs">   今日平均主播在线时长</span>#}
                {#<div class="top text-right w-full">#}
                  {#<i class="fa fa-caret-down text-warning m-r-sm"></i>#}
                {#</div>#}
              {#</div>#}
            {#</div>#}
            
            {#<div class="col-xs-6">#}
              {#<div class="panel padder-v item bg-primary">#}
                {#<div class="h1 text-fff font-thin h1">{{ data.view_register_num }}</div>#}
                {#<span class="text-muted text-xs">今日用户注册数量</span>#}
                {#<div class="top text-right w-full">#}
                  {#<i class="fa fa-caret-down text-warning m-r-sm"></i>#}
                {#</div>#}
              {#</div>#}
            {#</div>#}
            
            {#<div class="col-xs-6">#}
              {#<div class="panel padder-v item">#}
                {#<div class="font-thin h1">{{ data.live_register_num }}</div>#}
                {#<span class="text-muted text-xs">今日主播注册数量</span>#}
                {#<div class="bottom text-left">#}
                  {#<i class="fa fa-caret-up text-warning m-l-sm"></i>#}
                {#</div>#}
              {#</div>#}
            {#</div>#}
            
            {#<div class="col-xs-6">#}
              {#<div class="panel padder-v item">#}
                {#<div class="font-thin h1">{{ data.live_money }}元</div>#}
                {#<span class="text-muted text-xs">今日主播收入</span>#}
                {#<div class="bottom text-left">#}
                  {#<i class="fa fa-caret-up text-warning m-l-sm"></i>#}
                {#</div>#}
              {#</div>#}
            {#</div>#}
            
            {#<div class="col-xs-6">#}
              {#<div class="panel padder-v item">#}
                {#<div class="font-thin h1">{{ today_first_recharge_num }} 人</div>#}
                {#<span class="text-muted text-xs">今日首充人数</span>#}
                {#<div class="bottom text-left">#}
                  {#<i class="fa fa-caret-up text-warning m-l-sm"></i>#}
                {#</div>#}
              {#</div>#}
            {#</div>#}
            
            <div class="col-xs-6">
              <div class="panel padder-v item">
                <div class="font-thin h1">{{ today_total_recharge_num }} 元</div>
                <span class="text-muted text-xs">今日充值总额</span>
                <div class="bottom text-left">
                  <i class="fa fa-caret-up text-warning m-l-sm"></i>
                </div>
              </div>
            </div>
          
          
          </div>
        </div>
      
      </div>
    
    
    </div>
  
  </div>

</div>
</div>
<!-- 全局js -->

</body>

</html>
