
var countdown = 60;
function setTime(val)
{
	 	
    if(countdown == 0)
    {
    	
        val.removeAttribute('disabled');
        val.value="获取验证码";
        countdown = 60;
        val.setAttribute('class',"code-btn");
    }
    else
    {
    	
        val.setAttribute('disabled',true);
        val.value=countdown;
        countdown--;
        val.setAttribute('class',"code-btn code-btn-click");
        setTimeout(function(){
            setTime(val);
        },1000)
     }
    
}

function getRegister(){
    var oWrap = document.getElementById('wrap');
    oWrap.style.display="block";
}

function close(){
    alert("kkk");
    var oWrap = document.getElementById('wrap');
    oWrap.style.display = 'none';
}


function getQueryString(name) 
{ 
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
	var r = window.location.search.substr(1).match(reg); 
	
	r = encodeURI(encodeURI(unescape(r[2]))); 
	
	return r;
	
	
	if (r != null) return unescape(r[2]); return null; 
} 


