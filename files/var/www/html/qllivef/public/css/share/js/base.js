


var base_url = "../../public/index.php?_url=";
      






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






	
