<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - 登录</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    
   



    
    
    
    
     <link rel="shortcut icon" href="favicon.ico"> <link href="css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css?v=4.1.0" rel="stylesheet">
    
    
    
    
    
    
    
  
    
    	 <script src="http://cdn.bootcss.com/blueimp-md5/1.1.0/js/md5.js"></script>
	  <script src="http://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
    
     <link href="js/bootstrap-paginator/css/bootstrap.css" rel="stylesheet">
	<script type="text/javascript" src="js/bootstrap-paginator/bootstrap-paginator.js"></script>
	
	
	  <script src="js/base.js?v=1.0.0"></script>
	  <script src="js/juicer.js"></script>
    
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    
</head>

<body class="gray-bg">

	
		
	
	 <div class="wrapper wrapper-content animated fadeInUp">
	 
     
            <div class="col-sm-12">

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>用户</h5>
                    </div>
                    <div class="ibox-content">
                    
                       <div  class="form-inline" >
                   		     <button class="btn btn-white"  onclick="query();">搜索</button>
                            <div class="form-group">
                                <label for="exampleInputEmail2" class="sr-only">id</label>
                                <input  placeholder="请输入用户id" id="find_key" class="form-control">
                            </div>                           
                        </div>
                    
                    
                
                   
                
                        <div class="project-list">

                            <table class="table table-hover">
                                <tbody id="content">
                                
                                
                                
                                    
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                </div>
         
            
            <div id="page"><div>
        </div>
    

	


    
    <!-- 全局js -->
    
 <script id="tpl" type="text/html">

	
 	<ul>
        {@each list as it,index}


			    			<tr>
                                        <td class="project-title">
                                            <a>昵称:${it.nickname}  
											</a>  &nbsp; &nbsp;
											<a> 人民币:${it.value}  
											</a>  &nbsp;&nbsp;  

											<a> 备注:${it.remark}  
											</a>  &nbsp;&nbsp;







                                            <br/>
											<small>id: ${it.id}</small>
                                            <small>uid: ${it.uid}</small>
											<small>时间: ${it.add_time}</small>
                                        </td>
										<td class="project-people">
                                            <a href="projects.html"><img alt="image" class="img-circle" src="${it.img}"></a>
                                        </td>
                 
										
                                        
                                        
                                
                                    </tr>



        {@/each}

    </ul>


</script>
    

    
    
    
  
    
    
    
     <script >
     
    

     
     
     
     $(function()
    {  
    	 load();
    }); 
     
     
     function query()
     {
    	 cur_list_page = 1;
    	 load();
     }
     
     

     var tpl = document.getElementById('tpl').innerHTML;
     
     var cur_list_page = 1;
     
     
     function load()
     {
    	 
    	 var cur_page = cur_list_page;
    	 
    	 
    	 var key = $('#find_key').val();
    	 
    	 
    	 if(key.length < 4)
    	 {
    		key = 0;	 
    	 }
    	 

    	 
    
    
    	 post("/mgr/api/get_share_money_list",
    		{uid:key,
    		 page:{size:10,number:cur_page}},function(data)
         {
    		    

    		 
        	 var html = juicer(tpl, data);
    		  

    			$('#content').html(html);
   
    		   var options = {
    	                 bootstrapMajorVersion: 2, //版本
    	                 cur_page: cur_page, //当前页数
    	                 totalPages: data.page.total, //总页数
    	                 itemTexts: function (type, page, current) {
    	                     switch (type) {
    	                         case "first":
    	                             return "首页";
    	                         case "prev":
    	                             return "上一页";
    	                         case "next":
    	                             return "下一页";
    	                         case "last":
    	                             return "末页";
    	                         case "page":
    	                             return page;
    	                     }
    	                 },//点击事件，用于通过Ajax来刷新整个list列表
    	                 onPageClicked: function (event, originalEvent, type, page) 
    	    			 {
    	                	 cur_list_page = page;
    	                	 load();
    	                 }
    	             };
    	    			
    	    	$('#page').bootstrapPaginator(options);
    		   
    		   
    		   
    		    		    	 
    	 });
    	 
    	 
    
    	 
     }
     
     
     

     
     
     </script>

    
   
</body>

</html>
