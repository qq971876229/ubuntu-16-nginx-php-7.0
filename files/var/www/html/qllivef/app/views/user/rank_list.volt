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
    
     <script src="js/plugins/layer/laydate/laydate.js"></script>

</head>

<body class="gray-bg">


                                    
                                    
                                    <div class="form-group">
                                    
                                    	  <select class="form-control m-b" id="pid" onchange="change()">
                                        <option value=hot>活跃</option>
                                        <option value=goddess>女神</option>
                                        <option value=new>新人</option>
                                        <option value=appstore>苹果审核</option>
                                    </select>
                                        <label class="col-sm-2 control-label">顶置时间：</label>
                                        <div class="col-sm-10">
                                            <input  id="date" class="form-control layer-date" placeholder="YYYY-MM-DD hh:mm:ss" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
                                            <button onclick="check();">确定</button>
                                        </div>
                                        
                                          
                                        
                                    </div>
                                    
                                  
                                    

        
    
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




  
  function check()
  {
	  
	  
	  var objS = document.getElementById("pid");
      var type = objS.options[objS.selectedIndex].value;
	  
	  var date = $("#date").val();
	  
	  if(date.length < 3)
		{
			alert("请选择日期");
			return ;
		 }
	  
	  post("/mgr/api/rank_list",{uid:{{uid}},type:type,date:date},function(data)
		    	 {
		    		 alert("ok");
		    	 });
	  
  }
  
  


</script>
    
    

</body>

</html>
