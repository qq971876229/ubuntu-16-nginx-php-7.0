<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title>在线直播界面</title>
</head>
<body>
<img id="receiver" style='width:640px;height:480px'/>
<script type="text/javascript" charset="utf-8">
    var ws = new WebSocket("ws://111.230.2.244:9501");
    var image = document.getElementById('receiver');
    ws.onopen = function(){

    }
    ws.onmessage = function(data)
    {
        image.src=data.data;
    }
</script>
</body>
</html>