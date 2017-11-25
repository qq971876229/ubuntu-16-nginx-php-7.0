<!DOCTYPE html>
<html>

<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  
  <title>设置列表</title>
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
          <h5>系统设置</h5>
        </div>
        <div class="ibox-content">
          
          
          <div class="project-list">
            
            <table class="table table-hover">
              <tbody>
              <tr>
                <th width="20%">设置名称</th>
                <th width="10%">选择值</th>
                <th width="10%">输入值</th>
                <th width="10%">排序方式</th>
                <th width="25%">开始时间</th>
                <th width="25%">结束时间</th>
                <th width="35%">操作</th>
              </tr>
              
              {% for item in list %}
                
                <tr>
                  
                  <td>  {{ item.name }}  </td>
                  
                  <td>  {{ item.selected_name }}  </td>
                  
                  <td>  {{ item.input_value }} </td>
                  
                  <td>
                    {% if item.order_type=='rand' %}
                      随机
                    {% elseif item.order_type=='recharge' %}
                      最新充值
                    {% else %}
                      默认
                    {% endif %}
                  </td>
                  
                  <td>  {{ date("Y-m-d H:i:s",item.start_time) }}  </td>
                  
                  <td>  {{ date("Y-m-d H:i:s",item.end_time) }}  </td>
                  
                  <td class="project-actions">
                    
                    <a href="./index.php?_url=/mgr/setting/edit&id={{ item.id }}" class="btn btn-white btn-sm">
                      <i class="fa fa-pencil"></i> 编辑 </a>
                   
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
