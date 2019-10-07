<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
use extend\cache\memcache\MemcacheTool;
use extend\cache\memcache\MemcachedTool;

// 添加
include 'extend/cache/memcache/MemcacheInterface.php';
include 'extend/cache/memcache/Memcache.php';
include 'extend/cache/memcache/Memcached.php';
$memcache = new MemcachedTool();

$memcache->set('name','phpxxo');
$name = $memcache->get('name');
echo "set,get name:{$name}<br>";

$countMoney = $memcache->incr('countMoney',100);
echo "countMoney: {$countMoney}<br>";
$countMoney = $memcache->decr('countMoney',2);
echo "countMoney: {$countMoney}<br>";
$memcache->del('countMoney');


//for($i=0;$i<100;$i++){
//    $memcache->set(random(6),$i);
//}
$memcache->set('namee','phpxxo');
$keys = $memcache->getAllKeys();
var_dump($keys);
echo $memcache->get('namee');
//$memcache->flush(true);
//$res = $memcache->getKeys();
//var_dump($res);

/**
 * 方法一：获取随机字符串
 * @param number $length 长度
 * @param string $type 类型
 * @param number $convert 转换大小写
 * @return string 随机字符串
 */
function random($length = 6, $type = 'string', $convert = 0)
{
    $config = array(
        'number' => '1234567890',
        'letter' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'string' => 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789',
        'all' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    );

    if (!isset($config[$type]))
        $type = 'string';
    $string = $config[$type];

    $code = '';
    $strlen = strlen($string) - 1;
    for ($i = 0; $i < $length; $i++) {
        $code .= $string{mt_rand(0, $strlen)};
    }
    if (!empty($convert)) {
        $code = ($convert > 0) ? strtoupper($code) : strtolower($code);
    }
    return $code;
}



