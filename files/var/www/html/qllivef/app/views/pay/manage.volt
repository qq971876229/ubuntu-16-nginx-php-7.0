<!DOCTYPE html>
<html>

<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>充值管理</title>
  <meta name="keywords" content="">
  <meta name="description" content="">
  
  <link rel="shortcut icon" href="favicon.ico">
  <link href="css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
  <link href="css/font-awesome.css?v=4.4.0" rel="stylesheet">
  
  <link href="css/animate.css" rel="stylesheet">
  <link href="css/style.css?v=4.1.0" rel="stylesheet">
  <!--[if lt IE 9]>
  <meta http-equiv="refresh" content="0;ie.html"/>
  <![endif]-->

</head>

<body class="gray-bg">

<div class="wrapper wrapper-content animated fadeInUp">
  <div class="row">
    <div class="col-sm-12">
      
      <div class="ibox">
        <div class="ibox-title">
          <h5>充值管理</h5>
        </div>
        <div class="ibox-content">
          
          
          <div class="project-list">
            
            <table class="table table-hover">
              <tbody>
              <tr>
                <th width="10%">日期</th>
                <th width="8%">注册人数</th>
                <th width="8%">注册人数ios</th>
                <th width="8%">注册人数android</th>
                <th width="8%">登录用户</th>
                <th width="8%">登录主播</th>
                <th width="8%">充值人数</th>
                <th width="8%">首充人数</th>
                <th width="8%">当日充值ios</th>
                <th width="8%">当日充值android</th>
                <th width="8%">总充值金额</th>
                <th width="8%">邀请人充值金额</th>
                <th width="8%">人均充值金额</th>
                <th width="8%">总定单数</th>
                <th width="8%">10元定单数</th>
                <th width="8%">30元定单数</th>
                <th width="8%">50元定单数</th>
                <th width="8%">100元定单数</th>
                <th width="8%">200元定单数</th>
                <th width="8%">500元定单数</th>
                <th width="8%">1000元定单数</th>
                <th width="8%">2000元定单数</th>
              </tr>

              <?php foreach ($page->items as $item) { ?>
              
              <tr>
                <td><?php echo $item->date; ?></td>
                <td><?php echo $item->register; ?></td>
                <td><?php echo $item->register_ios; ?></td>
                <td><?php echo $item->register_android; ?></td>
                <td><?php echo $item->login_view; ?></td>
                <td><?php echo $item->login_live; ?></td>
                <td><?php echo $item->num; ?></td>
                <td><?php echo $item->first; ?></td>
                <td><?php echo $item->ios; ?></td>
                <td><?php echo $item->android; ?></td>
                <td><?php echo $item->total_money; ?></td>
                
                <td>
                  <?php echo $item->reference_money; ?> /
                  <?php if($item->total_money!=0){ echo round($item->reference_money/$item->total_money,4)*100; }else{ echo 0; } ?>  %
                </td>
                
                <td><?php echo $item->average_money; ?></td>
                <td><?php echo $item->total_order; ?></td>
                <td><?php echo $item->ten; ?></td>
                <td><?php echo $item->thirty; ?></td>
                <td><?php echo $item->fifty; ?></td>
                <td><?php echo $item->one_hundred; ?></td>
                <td><?php echo $item->two_hundred; ?></td>
                <td><?php echo $item->five_hundred; ?></td>
                <td><?php echo $item->one_thousand; ?></td>
                <td><?php echo $item->two_thousand; ?></td>
              </tr>
              
              <?php } ?>
              
              <tr>
                <td colspan="17">
  
                  <a href="./index.php?_url=/mgr/pay/manage">首页</a>
                  <a href="./index.php?_url=/mgr/pay/manage&page=<?= $page->before; ?>">上一页</a>
                  <a href="./index.php?_url=/mgr/pay/manage&page=<?= $page->next; ?>">下一页</a>
                  <a href="./index.php?_url=/mgr/pay/manage&page=<?= $page->last; ?>">末页</a>
  
                  <?php echo "当前页 ", $page->current, " of ", $page->total_pages; ?>
  
                </td>
              </tr>
              
              {% for item in page %}
                
                {#<tr>#}
                  {##}
                  {#<td>  {{ item.date }}  </td>#}
                  {##}
                  {#<td>  {{ item.num }}  </td>#}
                  {##}
                  {#<td>  {{ item.first }}  </td>#}
  {##}
                  {#<td>  {{ item.ios }} </td>#}
                  {##}
                  {#<td>  {{ item.android }} </td>#}
                  {##}
                  {#<td>  {{ item.total_money }} </td>#}
                  {##}
                  {#<td>  {{ item.reference_money }} </td>#}
                  {##}
                  {#<td>  {{ item.average_money }} </td>#}
                  {##}
                  {#<td>  {{ item.total_order }} </td>#}
                  {##}
                  {#<td>  {{ item.ten }} </td>#}
                  {##}
                  {#<td>  {{ item.thirty }} </td>#}
                  {##}
                  {#<td>  {{ item.fifty }} </td>#}
                  {##}
                  {#<td>  {{ item.one_hundred }} </td>#}
                  {##}
                  {#<td>  {{ item.two_hundred }} </td>#}
                  {##}
                  {#<td>  {{ item.five_hundred }} </td>#}
                  {##}
                  {#<td>  {{ item.one_thousand }} </td>#}
                  {##}
                  {#<td>  {{ item.two_thousand }} </td>#}
                  {##}
                {##}
                {#</tr>#}
              
              {% endfor %}
              
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- 全局js -->
<script src="js/jquery.min.js?v=2.1.4"></script>
<script src="js/bootstrap.min.js?v=3.3.6"></script>


<script>

    function del(id) {

        if (window.confirm('你确定要删除么？')) {
            //alert("确定");
            window.location.href = ".。/index.php?_url=/mgr/user/mgr_user_del&id=" + id;
            return true;
        } else {
            //alert("取消");
            return false;
        }


    }

</script>


</body>

</html>
