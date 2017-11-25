<!DOCTYPE html>
<html>

<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  
  <title>提现记录</title>
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
        <h5>提现列表</h5>
      </div>
      <div class="ibox-content">
        
        
        <select class="form-control m-b" id="pid" onchange="change()">
          <option value=0>申请中</option>
          <option value=1>已通过</option>
          <option value=2>已驳回</option>
        </select>
        
        
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
            
            </a> &nbsp; &nbsp;<a> 状态:${it.state_name}
            </a> &nbsp;&nbsp;
            </a> &nbsp; &nbsp;<a> 用户id:${it.uid}
            </a> &nbsp;&nbsp;
            </a> &nbsp; &nbsp;<a> 昵称:${it.nickname}
            </a> &nbsp;&nbsp;
  
            </a> &nbsp; &nbsp;<a> 余额:${it.balance}
            </a> &nbsp;&nbsp;
            
            </a> &nbsp; &nbsp;<a> 提现账号:${it.cash_info.cash_account}
            </a> &nbsp;&nbsp;
            
            </a> &nbsp; &nbsp;<a> 提现姓名:${it.cash_info.cash_name}
            </a> &nbsp;&nbsp;
            
            </a> &nbsp; &nbsp;<a> 金额:${it.money_value}元
            </a> &nbsp;&nbsp;
            
            <br/>
            <small>id: ${it.id}</small>
            <small>申请时间: ${it.add_time}</small>
          
          </td>
          
          <td class="project-actions">
            <a onclick="reject('${it.id}');" class="btn btn-white btn-sm">
              <i class="fa fa-pencil"></i>驳回</a>
          </td>
          
          <td class="project-actions">
            <a onclick="adopt('${it.id}');" class="btn btn-white btn-sm">
              <i class="fa fa-pencil"></i>通过</a>
          </td>
        
        
        </tr>
        
        
        {@/each}
      
      </ul>
    
    
    </script>
    
    
    <script>


        function reject(id) {


            layer.confirm('确定驳回么？', {
                btn: ['确定', '取消'] //按钮
            }, function () {

                check(id, 2);

            }, function () {


            });


        }

        function adopt(id) {
            layer.confirm('确定通过么？', {
                btn: ['确定', '取消'] //按钮
            }, function () {

                check(id, 1);

            }, function () {


            });
        }


        function check(id, state) {
            post("/mgr/api/cash_check", {id: id, state: state}, function (data) {

                layer.msg('成功', {icon: 1, time: 500});
                load();
            });

        }

  
        function change() {
            load();
            cur_list_page = 1;
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

            var objS = document.getElementById("pid");
            var type = objS.options[objS.selectedIndex].value;


            post("/mgr/api/cash_list",
                {
                    page: {size: 10, number: cur_page}, type: type
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
