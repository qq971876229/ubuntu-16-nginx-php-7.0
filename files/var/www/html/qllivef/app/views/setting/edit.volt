<!DOCTYPE html>
<html>

<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>系统设置</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  
  <link rel="shortcut icon" href="favicon.ico">
  <link href="css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
  <link href="css/font-awesome.css?v=4.4.0" rel="stylesheet">
  
  <link href="css/animate.css" rel="stylesheet">
  <link href="css/style.css?v=4.1.0" rel="stylesheet">
  
  <link href="js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
  
  <!--[if lt IE 9]>
  <meta http-equiv="refresh" content="0;ie.html"/>
  <![endif]-->

</head>

<body class="gray-bg">

<div class="middle-box text-center loginscreen  animated fadeInDown">
  
  <div>
    
    <form class="m-t" role="form" action="./index.php?_url=/mgr/setting/do_save" method="post">
      
      <div class="form-group">
        
        <div class="col-md-4">名称：</div>
        <div class="col-md-8"><input type="text" name="name" value="{{ setting.name }}"></div>
        
        <br/>
        <br/>
        
        <div class="col-md-4">选择值：</div>
        <div class="col-md-8">
          
          <select name="value" id="value">
            {% for key,item in options %}
              <option value="{{ item }}|{{ options_name[key] }}" {% if setting.value==item %}selected{% endif %} >
                {{ options_name[key] }}
              </option>
            {% endfor %}
          </select>
        
        </div>
        
        <br/>
        <br/>
        
        <div class="col-md-4">排序方式：</div>
        <div class="col-md-8">
          
          <select name="order_type" id="value">
            <option value="default" {% if setting.order_type=='default' %}selected{% endif %} >默认</option>
            <option value="rand"  {% if setting.order_type=='rand' %}selected{% endif %} >随机</option>
            <option value="recharge"  {% if setting.order_type=='recharge' %}selected{% endif %} >最新充值</option>
          </select>
        
        </div>
        
        <br/>
        <br/>
        <div class="col-md-4">输入值：</div>
        <div class="col-md-8">
          
          <input type="text" name="input_value" value="{{ setting.input_value }}">
        
        </div>
        
        <br/>
        <br/>
        <div class="col-md-4">开始时间：</div>
        <div class="col-md-8">
          
          <input type="text" name="start_time" value="{{ start_time }}" id="start_time">
        
        </div>
        
        <br/>
        <br/>
        <div class="col-md-4">结束时间：</div>
        <div class="col-md-8">
          
          <input type="text" name="end_time" value="{{ end_time }}" id="end_time">
        
        </div>
        
        <br/>
        <br/>
        
        <div class="col-md-4">备注：</div>
        <div class="col-md-8"><input type="text" name="remark" value="{{ setting.remark }}" readonly></div>
        
        <input type="hidden" name="id" value="{{ setting.id }}">
      
      </div>
      
      <button type="submit" class="btn btn-primary block full-width m-b">确定</button>
    
    </form>
  
  </div>

</div>

<!-- 全局js -->
<script type="text/javascript" src="js/jquery.min.js?v=2.1.4"></script>
<script type="text/javascript" src="js/bootstrap.min.js?v=3.3.6"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"
        charset="UTF-8"></script>


<script type="text/javascript">

    $('#start_time').datetimepicker({
        language: 'zh-CN',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });

    $('#end_time').datetimepicker({
        language: 'zh-CN',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });

</script>


</body>

</html>
