<!DOCTYPE html>
<html>

<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  
  <title>聊天计费</title>
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
          <h5>聊天计费</h5>
        </div>
        <div class="ibox-content">
          
          
          <div class="project-list">
            
            <table class="table table-hover">
              <tbody>
              <tr>
                <th width="10%">id</th>
                <th width="10%">uid</th>
                <th width="10%">视频费用</th>
                <th width="10%">备注</th>
                <th width="10%">session_id</th>
              </tr>
              
              {% for item in list %}
                
                <tr>
  
                  <td>  {{ item.id }}  </td>
                  <td>  {{ item.uid }}  </td>
                  <td>  {{ item.value }}  </td>
                  <td>  {{ item.remark }}  </td>
                  <td>  {{ item.session_id }}  </td>
                
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
