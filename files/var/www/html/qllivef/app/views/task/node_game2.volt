<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
  <style>
    .kuang{text-align: center;margin-top:200px;}
    #mess{text-align: center}
  </style>
</head>
<body>
<div id="mess"></div>

<script>
    var mess = document.getElementById("mess");
    if(window.WebSocket){
        var ws = new WebSocket('ws://111.230.2.244:30001');

        ws.onopen = function(e){
            console.log("连接服务器成功");
            ws.send("game2");
        }
        ws.onclose = function(e){
            console.log("服务器关闭");
        }
        ws.onerror = function(){
            console.log("连接出错");
        }

        ws.onmessage = function(e){
            var time = new Date();
            mess.innerHTML+=time+"的消息："+e.data+"<br>"
        }
    }
</script>
</body>
</html>