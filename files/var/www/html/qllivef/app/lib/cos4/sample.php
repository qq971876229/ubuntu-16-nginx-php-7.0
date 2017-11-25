<?php

require('./include.php');

use qcloudcos\Cosapi;

$bucket = 'liveimg';
$src = 'd:/1.jpg';
$dst = '/2';
$folder = '/';

Cosapi::setTimeout(180);

// 设置COS所在的区域，对应关系如下：
//     华南  -> gz
//     华中  -> sh
//     华北  -> tj
Cosapi::setRegion('sh');

// Create folder in bucket.
//$ret = Cosapi::createFolder($bucket, $folder);
//var_dump($ret);
//die();

// Upload file into bucket.
//$ret = Cosapi::upload($bucket, $src, $dst);
//var_dump($ret);
//die();

// List folder.
$ret = Cosapi::listFolder($bucket, $folder);
var_dump($ret);
die();

// Update folder information.
$bizAttr = "";
$ret = Cosapi::updateFolder($bucket, $folder, $bizAttr);
var_dump($ret);

// Update file information.
$bizAttr = '';
$authority = 'eWPrivateRPublic';
$customerHeaders = array(
    'Cache-Control' => 'no',
    'Content-Type' => 'application/pdf',
    'Content-Language' => 'ch',
);
$ret = Cosapi::update($bucket, $dst, $bizAttr,$authority, $customerHeaders);
var_dump($ret);

// Stat folder.
$ret = Cosapi::statFolder($bucket, $folder);
var_dump($ret);

// Stat file.
$ret = Cosapi::stat($bucket, $dst);
var_dump($ret);

// Delete file.
$ret = Cosapi::delFile($bucket, $dst);
var_dump($ret);

// Delete folder.
$ret = Cosapi::delFolder($bucket, $folder);
var_dump($ret);

// Copy file.
$ret = Cosapi::copyFile($bucket, '/111.txt', $dst);
var_dump($ret);