<?php

//header("Location: ../public/index.php?_url=/vod/check"); 


$key = "jjfdf3343#DD!!@jljclj#D45kjlcDD9991()k1G";

$input = file_get_contents('php://input');

if($key != $input)
{
    echo "key 错误";
    return ;
}



$url =  $_SERVER['HTTP_HOST'];


//初始化
$curl = curl_init();
//设置抓取的url
curl_setopt($curl, CURLOPT_URL, $url.'/qllivef/public/index.php?_url=/vod/check');

//curl_setopt($curl, CURLOPT_URL, 'http:\\www.biadu.com');

//curl_setopt($curl, CURLOPT_URL, 'http://www.baidu.com');

//设置头文件的信息作为数据流输出
curl_setopt($curl, CURLOPT_HEADER, 1);
//设置获取的信息以文件流的形式返回，而不是直接输出。
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//执行命令
$data = curl_exec($curl);
//关闭URL请求
curl_close($curl);

//$err_code = curl_errno($curl);

//echo $err_code;

//显示获得的数据
print_r($data);






?>