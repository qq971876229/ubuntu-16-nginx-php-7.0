


var base_url = "./index.php?_url=";
      


/*
function go_url(url)
{
	window.location.href = "http://api.zy5777.com/live2_mgr/"+url;
}



function get_img(img)
{
	
	return "http://liveimg-10065661.image.myqcloud.com/"+img;
	
}*/




function post(url,content,fun)
{	
	url = base_url+url;
	
	//alert(url);

		
	data = JSON.stringify(content);  
	
	$.ajax(
		  { 
		  type: 'POST',
		  url: url, 
		   data:data,
		  success: function(data)
		  {			  			
			
			  //alert(data);
			  
			  var json = JSON.parse(data);
				if(json.status.succeed == 0)
				{
					
					if(json.status.error_code ==100)
						 logout();
										
					alert(json.status.error_desc);
					return false;
				}
				else
				{
					fun(json.data);
				}
			  
			  
			  
			 
			},
			'error' : function(i, data)
			{				
				alert(JSON.stringify(i));
			}
			}
			);
	
}






	
