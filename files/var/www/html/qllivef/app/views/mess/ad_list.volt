<!DOCTYPE html>
<html>

<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  
  <title>广告列表</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  
  
  <link rel="shortcut icon" href="favicon.ico">
  <link href="css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
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
  <script src="js/layer/layer.js"></script>
  
  
  
  <!--[if lt IE 9]>
  <meta http-equiv="refresh" content="0;ie.html"/>
  <![endif]-->

</head>

<body class="gray-bg">


<div class="wrapper wrapper-content animated fadeInUp">
  
  
  <div class="col-sm-12">
    
    <div class="ibox">
      <div class="ibox-title">
        <h5>广告列表</h5>
        <div class="ibox-tools">
          <a onclick="add_ad();" class="btn btn-primary btn-xs">创建新广告</a>
        </div>
      
      </div>
      <div class="ibox-content">
        
        
        <div class="project-list">
          
          <table class="table table-hover">
            <tbody id="content">
            
            
            </tbody>
          </table>
        </div>
      
      </div>
    </div>
  </div>
  
  
  <div id="page">
  </div>
</div>


<!-- 全局js -->

<script id="tpl" type="text/html">
  
  
  <ul>
    {@each list as it,index}
    
    
    <tr>
      <td class="project-title">
        
        
        <a> 链接:${it.link}
        </a> &nbsp;&nbsp;
        
        <a> 类型:${it.type}
        </a> &nbsp;&nbsp;
        
        <a> 排序号:${it.sort}
        </a> &nbsp;&nbsp;
        
        <br/>
        <small>id: ${it.id}</small>
      </td>
      
      <td class="project-people">
        <a><img alt="image" src="${it.img}"></a>
      </td>
      
      <td class="project-actions">
        <a onclick="edit_ad(${it.id});" class="btn btn-white btn-sm"><i class="fa fa-folder"></i> 修改 </a>
        <a onclick="del_ad(${it.id});" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i> 删除 </a>
      </td>
    
    
    </tr>
    
    
    {@/each}
  
  </ul>


</script>


<script>

    function add_ad() {
        var show_vod_url = './index.php?_url=/mgr/mess/add_ad';

        //alert(show_vod_url);

        layer.ready(function () {
            layer.open({
                type: 2,
                title: '增加广告',
                maxmin: true,
                area: ['500px', '500px'],
                content: show_vod_url,
                end: function () {
                    load();
                }
            });
        });
    }

    function del_ad(id) {


        post("/mgr/api/del_ad",
            {id: id}, function (data) {

                load();


            });


    }


    function edit_ad(id) {
        var show_vod_url = './index.php?_url=/mgr/mess/edit_ad&id=' + id;

        layer.ready(function () {
            layer.open({
                type: 2,
                title: '修改广告',
                maxmin: true,
                area: ['500px', '500px'],
                content: show_vod_url,
                end: function () {
                    load();
                }
            });
        });

    }


    $(function () {
        load();
    });


    function query() {
        cur_list_page = 1;
        load();
    }


    var tpl = document.getElementById('tpl').innerHTML;

    var cur_list_page = 1;

    function load() {

        var cur_page = cur_list_page;


        post("/mgr/api/get_ad_list",
            {
                page: {size: 10, number: cur_page}
            }, function (data) {


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
                    onPageClicked: function (event, originalEvent, type, page) {
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
