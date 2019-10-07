<?php
/**
 * php_memcache扩展操作memcached
 * Author:phpxxo
 * Email:244013508@qq.com
 * Create Date:2019-10-4
 */
namespace extend\cache\memcache;
class MemcacheTool implements MemcacheInterface{
    protected $options = [
        'host'=>'127.0.0.1',//集群配置多个主机逗号分开
        'port'=>'11211',//多个主机, 如果端口号不一致, 请一一对应配置多个,逗号分开
        'expire'=>0,
        'timeout'=>0,
        'prefix'=>'',
        'username'=>'',
        'password'=>'',
        'options'=>[]
    ];
    protected $instance;//memcache实例

    /**
     * 初始化, 连接memcached
     */
    public function __construct(Array $options=[]){
        // 验证有没有加载php_memcache扩展
        if(!extension_loaded('memcache')) {
            throw new \BadFunctionCallException('php_memcache extension not exists');
        }
        $config =  include 'config.php';
        if($config['useConfig']){
            foreach($config as $key=>$conf){
                $this->options[$key] = $conf;
            }
        }

        if(!empty($options)) {
            $this->options = array_merge($options,$this->options);
        }
        // 支持集群
        $hostList = explode(',',$this->options['host']);
        $hostLen = count($hostList);
        if($hostLen===1) {
            $this->instance = new \Memcache;
            $this->instance->connect($this->options['host'],$this->options['port']);
        }elseif($hostLen>1){
            $this->instance = new \Memcache;
            $portList = explode(',',$this->options['port']);
            $portLen = count($portList);
            if($portLen === 1){
                $portList = array_fill(0,$hostLen,$this->options['port']);
            }
            for($i=0;$i<$hostLen;$i++){
                $this->instance->addServer($hostList[$i],$portList[$i]);
            }

        }else{
            throw new \BadFunctionCallException('No Memcache parameters are configured');
        }

    }
    /**
     * 关闭memcached连接
     */
    public function __destruct(){
        if($this->instance instanceof \Memcache)
        {
            $this->instance->close();
        }
    }

    /**
     * 设置缓存
     * @param    string  $name 缓存变量名
     * @param    string  $value 缓存值
     * @param    int  $expire 有效期 秒
     * @param    boolean  $hasPrefix 是否有前缀
     * @return   boolean
     */
    public function set($name,$value,$expire=0,$hasPrefix=true){
        if($hasPrefix){
            $name = $this->getCacheKey($name);
        }
        return $this->instance->set($name,$value,0,$expire);
    }

    /**
     * 获取缓存值
     * @param    string  $name 缓存变量名
     * @param    string  $default 默认值
     * @param    boolean  $hasPrefix 是否有前缀
     */
    public function get($name,$default='',$hasPrefix=true){
        if($hasPrefix){
            $name = $this->getCacheKey($name);
        }
        $result =  $this->instance->get($name);
        return $result === false ? $default : $result;
    }

    /**
     * 自增缓存(针对数值)
     * @param    string  $name 缓存变量名
     * @param    int  $step 步长
     * @param    boolean  $hasPrefix 是否有前缀
     */
    public function incr($name='',$step=1,$hasPrefix=true){
        if($hasPrefix){
            $name = $this->getCacheKey($name);
        }
        if($this->instance->get($name) === false) {
            $this->instance->set($name,0,0,0);
        }
        return $this->instance->increment($name,$step);
    }

    /**
     * 自减缓存(针对数值)
     * @param    string  $name 缓存变量名
     * @param    int  $step 步长
     * @param    boolean  $hasPrefix 是否有前缀
     */
    public function decr($name='',$step=1,$hasPrefix=true){
        if($hasPrefix){
            $name = $this->getCacheKey($name);
        }
        if($this->instance->get($name) === false) {
            $this->instance->set($name,0,0,0);
        }
        return $this->instance->decrement($name,$step);
    }

    /**
     * 删除缓存
     * @param    string  $name 缓存变量名
     * @param    boolean  $clearMemory 是否释放内存空间
     * @param    boolean  $clearMemory 是否有前缀
     */
    public function del($name='',$clearMemory=false,$hasPrefix=true){
        if($hasPrefix) {
            $name = $this->getCacheKey($name);
        }
        $this->instance->delete($name);
        if($clearMemory){
            $this->instance->get($name);
        }
    }
    /**
     * 刷新所有缓存过期
     * @param boolean $clearMemory 是否释放内存空间
     * @return boolean
     */
    public function flush($clearMemory=false){
        $res =  $this->instance->flush();
        if($clearMemory == false) return $res;
        $cacheList = $this->getKeys();
        $this->instance->get($cacheList);
        return $res;
    }

    /**
     * 获取带前缀的缓存存储key
     * @param    string  $name 缓存变量名
     * @return string
     */
    public function getCacheKey($name){
        return $this->options['prefix'].$name;
    }

    /**
     * 获取所有key
     * @return array
     */
    public function getAllKeys()
    {
        $list = array();
        $allSlabs = $this->instance->getExtendedStats('slabs', 100);
        $slabIds = [];
        foreach ($allSlabs as $slabs) {
            foreach ($slabs as $slabId => $slabMeta) {
                if (!is_int($slabId)) continue;
                $slabIds[] = $slabId;
            }
        }
        $slabIds = array_unique($slabIds);
        foreach ($slabIds as $slabId) {
            $cdump = $this->instance->getExtendedStats('cachedump', $slabId, 100000);
            foreach ($cdump as $serv => $data) {
                if (!is_array($data)) continue;
                foreach ($data as $key => $val) {
                    $list[] = $key;
                }

            }
        }
        return $list;
    }

}