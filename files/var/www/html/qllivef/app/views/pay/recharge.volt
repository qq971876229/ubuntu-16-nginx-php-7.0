<!DOCTYPE html>
<html>

<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>充值记录</title>
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
  
  <link href="js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
  <script type="text/javascript" src="js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
  <script type="text/javascript" src="js/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"
          charset="UTF-8"></script>
  
  
  <script src="js/base.js?v=1.0.0"></script>
  <script src="js/juicer.js"></script>
  
  <!--[if lt IE 9]>
  <meta http-equiv="refresh" content="0;ie.html"/>
  <![endif]-->

</head>

<body class="gray-bg">

<div class="wrapper wrapper-content animated fadeInUp">
  
  <div class="col-sm-12">
    
    <div class="ibox">
      
      <div class="ibox-title">
        <h5>充值列表</h5><br/><br/><br/><br/>
        
        <div class="col-md-2">充值时间</div>
        <div class="col-md-3">
          
          <input type="text" name="start_time" value="{{ date("Y-m-d",time()) }}" id="start_time">
        
        </div>
        
        <div class="col-md-1">到</div>
        <div class="col-md-3">
          
          <input type="text" name="end_time" value="" id="end_time">
        
        </div>
        
        <a href="javascript:;" class="col-md-3" id="search">查询</a>
        
        <br/>
        <br/>
        <br/>
        
        <div class="col-md-2"> 充值总额:</div>
        <div class="col-md-4" id="total_recharge"></div>
      
      
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
    <div>
    </div>
  </div>

</div>


</body>

<script id="tpl" type="text/html">
  
  
  <ul>
    {@each list as it,index}
    
    
    <tr>
      
      <td class="project-title">
        
         &nbsp; &nbsp;
        <a href="./index.php?_url=/mgr/user/user_detail&id=${it.uid}">用户id:${it.uid}</a>
        &nbsp;&nbsp;
        &nbsp; &nbsp;
        <a href="./index.php?_url=/mgr/user/user_detail&id=${it.uid}"> 昵称:${it.nickname}</a>
        &nbsp;&nbsp;
        
        &nbsp; &nbsp;<a> 支付方式:${it.code_name}</a> &nbsp;&nbsp;
        
        &nbsp; &nbsp;<a> 金额:${it.money_value}元 </a> &nbsp;&nbsp;
        
        <br/>
        <small>id: ${it.id}</small>
        <small>入账时间: ${it.paid_time}</small>
      </td>
    
    
    </tr>
    
    
    {@/each}
  
  </ul>


</script>

<script>
    $("#search").click(function () {
        load();
    });

    $('#start_time').datetimepicker({
        language: 'zh-CN',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
        initialDate: 'now',

    });

    $('#end_time').datetimepicker({
        language: 'zh-CN',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });


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

        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();

        if (cur_page == 1) {
            $.post("./index.php?_url=/mgr/api/total_recharge", {
                "start_time": start_time,
                "end_time": end_time
            }, function (data) {
                $("#total_recharge").html(data + "元");
            });
        }


        post("/mgr/api/recharge_list",
            {
                page: {size: 10, number: cur_page, start_time: start_time, end_time: end_time}
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


</html>
