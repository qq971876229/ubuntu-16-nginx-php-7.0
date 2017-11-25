<!DOCTYPE HTML>
<html>
<head>
  <meta charset="utf-8">
</head>

<body>
  
  <script type="text/javascript">


      WebSocketTest();

      function WebSocketTest() {
          if ("WebSocket" in window) {
//              alert("您的浏览器支持 WebSocket!");

              // 打开一个 web socket
              var ws = new WebSocket("ws://139.199.216.137:30001");

              ws.onopen = function () {
                  // Web Socket 已连接上，使用 send() 方法发送数据
                  alert("连接数据");
                  ws.send("发送数据,我是用户6666");
                  alert("数据发送中...");
              };

              ws.onmessage = function (evt) {
                  var received_msg = evt.data;
                  alert("数据已接收...");
              };

              ws.onclose = function () {
                  // 关闭 websocket
                  alert("连接已关闭...");
              };
          }

          else {
              // 浏览器不支持 WebSocket
              alert("您的浏览器不支持 WebSocket!");
          }
      }
  </script>


</body>
</html>