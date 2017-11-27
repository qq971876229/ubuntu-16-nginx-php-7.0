<!DOCTYPE html>
<html>

<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title> 观众主播列表</title>
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
        <h5>用户</h5>
      </div>
      <div class="ibox-content">
        
        
        <div class="form-inline">
          <button class="btn btn-white" onclick="query();">搜索</button>
          <div class="form-group">
            <label for="exampleInputEmail2" class="sr-only">id</label>
            <input placeholder="请输入用户id" id="find_key" class="form-control">
          </div>
          <div class="checkbox m-l m-r-xs">
            <label class="i-checks">
              <input type="checkbox" checked=true id="check_live"><i></i>主播
            </label>
            
            <label class="i-checks">
              <input type="checkbox" checked=true id="check_view"><i></i>观众
            </label>
            
            <label class="i-checks">
              <input type="checkbox" id="online"><i></i>在线
            </label>
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
  
  
  <div id="page">
    <div>
    </div>
    
    
    <!-- 全局js -->
    
    <script id="tpl" type="text/html">
      
      <ul>
        
        {@each list as it,index}
        
        <tr>
          <td class="project-title">
            <a href="./index.php?_url=/mgr/user/user_detail&id=${it.id}"> 昵称:${it.nickname} </a> &nbsp; &nbsp;
            <a> 身份:${it.carded} </a> &nbsp;&nbsp;
            <a> 累积充值:${it.recharge} </a> &nbsp;&nbsp;
            
            <br/>
            <small>id: ${it.id}</small>
            <small>注册时间: ${it.created_at}</small>
          </td>
          
          <td class="project-people">
            <a href="projects.html"><img alt="image" class="img-circle" src="${it.img}"></a>
          </td>
          
          <td class="project-actions">
            <a onclick="set_black(${it.id})" class="btn btn-white btn-sm">
              <i class="fa fa-pencil"></i>${it.black_name}</a>
          </td>
          
          <td class="project-actions">
            {@if it.is_live=='1'}
            <a onclick="cancel_host(${it.id})" class="btn btn-white btn-sm">
              <i class="fa fa-pencil"></i>${it.auth_action}</a>
            {@/if}
          </td>
          
          <td class="project-actions">
            {#<a onclick="set_host_type(${it.id})" class="btn btn-white btn-sm">#}
            {#<i class="fa fa-pencil"></i>设置为女神</a>#}
            <select name="set_host_type" id="set_host_type-${it.id}" onchange="set_host_type(${it.id})">
              
              <option value="0">设置显示模块</option>
              <option value="hot_door" {@if it.host_type=='hot_door'}selected{@/if}>热门</option>
              <option value="hot" {@if it.host_type=='hot'}selected{@/if}>活跃</option>
              <option value="goddess" {@if it.host_type=='goddess'}selected{@/if}>女神</option>
              <option value="new" {@if it.host_type=='new'}selected{@/if}>新人</option>
            </select>
          </td>
          
          <td class="project-actions">
            <a onclick="delete_user(${it.id})" class="btn btn-white btn-sm">
              <i class="fa fa-pencil"></i>删除</a>
          </td>
          
          {@if it.is_live=='1'}
          
          <td class="project-actions">
            <a onclick="set_stick(${it.id})" class="btn btn-white btn-sm">
              <i class="fa fa-pencil"></i>
              {@if it.stick=='1'} 取消置顶 {@else} 置顶 {@/if}
            </a>
          </td>
          
          {@/if}
          
          {#<td class="project-actions">#}
          {#<a onclick="rank_list(${it.id})" class="btn btn-white btn-sm">#}
          {#<i class="fa fa-pencil"></i>首页排序</a>#}
          {#</td>#}
        
        
        </tr>
        
        
        {@/each}
      
      </ul>
    
    
    </script>
    
    
    <script>

        function set_stick(uid) {

            post("/mgr/api/set_stick", {uid: uid}, function (data) {

                load(cur_list_page);
            });


        }


        function rank_list(uid) {
            var show_vod_url = './index.php?_url=/mgr/user/rank_list&uid=' + uid;

            //alert(show_vod_url);

            layer.ready(function () {
                layer.open({
                    type: 2,
                    title: '首页',
                    maxmin: true,
                    area: ['500px', '500px'],
                    content: show_vod_url,
                    end: function () {
                        load();
                    }
                });
            });

        }


        function set_black(uid) {

            post("/mgr/api/set_black", {uid: uid}, function (data) {

                load(cur_list_page);
            });


        }

        function delete_user(uid) {

            post("/mgr/api/delete_user", {uid: uid}, function (data) {

                load(cur_list_page);
            });


        }


        function cancel_host(uid) {

            post("/mgr/api/cancel_host", {uid: uid}, function (data) {
                load(cur_list_page);
            });
        }

        function set_host_type(uid) {

            var host_type = $("#set_host_type-" + uid).val();

            post("/mgr/api/set_host_type", {uid: uid, host_type: host_type}, function (data) {
                load(cur_list_page);
            })
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


            var key = $('#find_key').val();


            var is_live = false;
            var is_view = false;


            if ($('#check_live').is(':checked')) {
                is_live = true;
            }


            if ($('#check_view').is(':checked')) {
                is_view = true;
            }

            if ($('#online').is(':checked')) {
                online = 1;
            }else{
                online = 0;
            }


            post("/mgr/api/get_user_list",
                {
                    key: key,
                    is_live: is_live,
                    is_view: is_view,
                    online:online,
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
