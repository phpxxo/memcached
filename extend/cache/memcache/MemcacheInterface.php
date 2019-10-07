<?php
/**
 * php_memcache, php_memcached interface
 * Author:phpxxo
 * Email:244013508@qq.com
 * Create Date:2019-10-4
 */
namespace extend\cache\memcache;
interface MemcacheInterface{

    /**
     * 初始化, 连接memcached
     */
    function __construct(Array $options=[]);

    /**
     * 关闭memcached连接
     */
    function __destruct();

    /**
     * 设置缓存
     * @param    string  $name 缓存变量名
     * @param    string  $value 缓存值
     * @param    int  $expire 有效期 秒
     * @param    boolean  $hasPrefix 是否有前缀
     * @return   boolean
     */
     function set($name,$value,$expire=0,$hasPrefix=true);

    /**
     * 获取缓存值
     * @param    string  $name 缓存变量名
     * @param    string  $default 默认值
     * @param    boolean  $hasPrefix 是否有前缀
     */
     function get($name,$default='',$hasPrefix=true);

    /**
     * 自增缓存(针对数值)
     * @param    string  $name 缓存变量名
     * @param    int  $step 步长
     * @param    boolean  $hasPrefix 是否有前缀
     */
     function incr($name,$step=1,$hasPrefix=true);

    /**
     * 自减缓存(针对数值)
     * @param    string  $name 缓存变量名
     * @param    int  $step 步长
     * @param    boolean  $hasPrefix 是否有前缀
     */
     function decr($name,$step=1,$hasPrefix=true);

    /**
     * 删除缓存
     * @param    string  $name 缓存变量名
     * @param    boolean  $clearMemory 是否释放内存空间
     * @param    boolean  $clearMemory 是否有前缀
     */
     function del($name,$clearMemory=false,$hasPrefix=true);

    /**
     * 刷新所有缓存过期
     * @param boolean $clearMemory 是否释放内存空间
     * @return boolean
     */
     function flush($clearMemory=false);

    /**
     * 获取带前缀的缓存存储key
     * @param    string  $name 缓存变量名
     * @return string
     */
     function getCacheKey($name);

    /**
     * 获取所有key
     * @return array
     */
     function getAllkeys();

}