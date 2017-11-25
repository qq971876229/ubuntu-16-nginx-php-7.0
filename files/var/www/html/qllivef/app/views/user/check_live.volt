<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - 个人资料</title>
    <meta name="keywords" content="">
    <meta name="description" content="">



    <link rel="shortcut icon" href="favicon.ico"> <link href="css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css?v=4.1.0" rel="stylesheet">

</head>

<body class="gray-bg">
        
    <table border="1">
		<tr>
				
		<td>
			<div>
					<img  style="width:300px;" src="{{user.img}}">
					</img>
			 </div>
		
		</td>
		
		<td>
			<div style="width:300px;margin:0 auto">
			<video   src="{{user.vod}}" controls="controls" autoplay="autoplay">
			您的浏览器不支持 video 标签。
			</video>
			</div>
		</td>
		<td>
			
			<div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-12">
									<label>id:{{user.id}}</label>
									<br>
									<label>用户名:{{user.nickname}}</label>
                                    <div class="form-group">
                                        <label></label>
                                        <input id="mess" type="" placeholder="请输入驳回理由"  class="form-control">
                                    </div>
                                    <div>
                                        <button class="btn btn-sm btn-primary pull-right m-t-n-xs" onclick="reject();"><strong>驳回</strong>
                                        </button>
                                        <label>
                                             <button class="btn btn-sm btn-primary pull-right m-t-n-xs" onclick="adopt();"><strong>通过</strong>
                                        </button>
                                            </label>
                                    </div>
                           
                            </div>
                        </div>
                    </div>
			
			
		</td>
		
		</tr>
</table>

    <!-- 全局js -->
    <script src="js/jquery.min.js?v=2.1.4"></script>
    <script src="js/bootstrap.min.js?v=3.3.6"></script>



    <!-- 自定义js -->
    <script src="js/content.js?v=1.0.0"></script>


    <!-- Peity -->
    <script src="js/plugins/peity/jquery.peity.min.js"></script>

    <!-- Peity -->
    <script src="js/demo/peity-demo.js"></script>
     <script src="js/layer/layer.js"></script>
     <script src="js/base.js?v=1.0.0"></script>
    


<script>




  function reject()
  {
	
	  
	  layer.confirm('确定驳回么？', {
		  btn: ['确定','取消'] //按钮
		  }, function(){
		   
			  check(0);
  
		  }, function()
		  {

			  
		  });


	  
	  
  }
  
  function adopt()
  {
	  layer.confirm('确定通过么？', {
		  btn: ['确定','取消'] //按钮
		  }, function(){
		   
			  check(2);
  
		  }, function()
		  {

			  
		  });
  }
  
  function check(state)
  {
	  
	  var uid = "{{user.id}}";
	  var mess = $("#mess").val();
	  
	  if(state == 0 && mess.length < 3)
		{
			alert("请输入驳回理由");
			return;
		  }
	  
	  
	  post("/mgr/api/auth_check",{uid:uid,state:state,mess:mess},function(data)
		    	 {
		    		 
		 				 layer.msg('成功', {icon: 1,time:500});
		    	 });
	  
	  
  }
  
  


</script>
    
    

</body>

</html>
