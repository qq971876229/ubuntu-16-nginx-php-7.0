<!DOCTYPE html>
<html>

<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  
  <title> - 个人资料</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  
  
  <link rel="shortcut icon" href="favicon.ico">
  <link href="css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
  <link href="css/font-awesome.css?v=4.4.0" rel="stylesheet">
  <link href="css/animate.css" rel="stylesheet">
  <link href="css/style.css?v=4.1.0" rel="stylesheet">
  
  <script src="js/plugins/layer/laydate/laydate.js"></script>

</head>

<body class="gray-bg">


<div style="text-align: center;">
  
  <lable>类型</lable>
  <select class="form-control m-b" id="pid" onchange="change()">
    <option value=hot>hot</option>
    <option value=goddess>goddess</option>
    <option value=new>new</option>
  </select>
  
  
  <input id="link" type="" placeholder="连接" class="form-control" value="{{ ad.link }}">
  
  <input id="sort" type="text" placeholder="排序 越大越靠前" name="sort" class="form-control" value="{{ ad.sort }}">
  
  <img id="img" src="" height="200"/>
  
  <form enctype="multipart/form-data" id="file_form">
    <input name="img" type="file"/>
  
  </form>
  <input type="button" value="上传图片" onclick="load_file();" style="float:left"/>
  <button onclick="edit_ad();">确定</button>

</div>


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


    $(function () {

        $("#pid").val("{{ ad.type }}");

        set_image();
    });


    function edit_ad() {

        var link = $("#link").val();
        var sort = $("#sort").val();


        var objS = document.getElementById("pid");
        var type = objS.options[objS.selectedIndex].value;


        post("/mgr/api/edit_ad",
            {id:{{ ad.id }}, img: img_url, link: link, type: type, sort: sort}, function (data) {

                alert("ok");

                var index = parent.layer.getFrameIndex(window.name);
                parent.location.reload();
                parent.layer.close(index);



            });

    }


    function load_file() {

        var formData = new FormData($('#file_form')[0]);

        var url = "/img/upload_other";

        post_file(url, formData, function (data) {

            handler_msg(data, function (json) {
                var url = get_img(json.data);

                $("#img_show").attr('src', url);

                $("#img").val(json.data);
            });

        });
    }


    var img_url = "{{ ad.img }}";

    function set_image() {

        var url = "{{ url }}" + img_url;


        $("#img").attr('src', url);
    }

    function post_file(url, content, handler_error) {


        url = "../public/index.php?_url=/img/upload";


        $.ajax(
            {
                type: 'POST',
                url: url,
                data: content,
                processData: false,
                contentType: false,
                success: function (data) {


                    data = JSON.parse(data);
                    img_url = data.data;
                    set_image();

                },
                'error': function (i, data) {

                    alert(data);
                }
            });


    }


</script>


</body>

</html>
