<?php
/*
 * 全局配置文件
 * Author:phpxxo
 * Email:244013508@qq.com
 * Create Date:2019-10-4
 */
return [
    'host'=>'127.0.0.1',//集群配置多个主机逗号分开
    'port'=>'11211',//多个主机, 如果端口号不一致, 请一一对应配置多个,逗号分开
    'expire'=>0,
    'timeout'=>0,
    'prefix'=>'test_',
    'username'=>'',
    'password'=>'',
    'options'=>[],
    'useConfig'=>1,// 1:使用该配置, 0:不使用该配置
];