<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
  <style>
    .kuang{text-align: center;margin-top:200px;}
    #mess{text-align: center}
    .value{width: 200px;height:200px;border:1px solid;text-align: center;line-height: 200px;display: inline-block;}
  </style>
</head>
<body>
<div id="mess">正在连接...</div>
<div class="kuang">
  <div class="value" id="value1">小明小明</div>
  <div class="value" id="value2">大胸大胸</div>
  <div class="value" id="value3">小张小张</div>
</div>

<script>
    var mess = document.getElementById("mess");
    if(window.WebSocket){
        var ws = new WebSocket('ws://111.230.2.244:30001');

        ws.onopen = function(e){
            console.log("连接服务器成功");
            ws.send("game1");
        }
        ws.onclose = function(e){
            console.log("服务器关闭");
        }
        ws.onerror = function(){
            console.log("连接出错");
        }

        ws.onmessage = function(e){
            mess.innerHTML = "连接成功"
            document.querySelector(".kuang").onclick = function(e){
                var time = new Date();
                ws.send(time + "  game1点击了“" + e.target.innerHTML+"”");
            }
        }
    }
</script>
</body>
</html>