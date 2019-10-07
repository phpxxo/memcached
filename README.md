# memcached

#### 介绍
基于interface 实现php不管是php_memcache扩展还是php_memcached扩展, 采用统一的方法来操作memcached缓存



#### 软件架构
软件架构说明


#### 安装教程
```
use extend\cache\memcache\MemcacheTool;
use extend\cache\memcache\MemcachedTool;

include 'extend/cache/memcache/MemcacheInterface.php';
include 'extend/cache/memcache/Memcache.php';
include 'extend/cache/memcache/Memcached.php';
```

#### 使用说明
```
// 实例化
// $memcache = new MemcacheTool();
$memcache = new MemcachedTool();

// 设置
$memcache->set('name','phpxxo');

// 获取
$name = $memcache->get('name');
```

#### 参与贡献

