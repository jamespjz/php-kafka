<?php
/**+----------------------------------------------------------------------
 * JamesPi Redis [php-redis]
 * +----------------------------------------------------------------------
 * KafKa Basic Service Business logic file
 * +----------------------------------------------------------------------
 * Copyright (c) 2020-2030 http://www.pijianzhong.com All rights reserved.
 * +----------------------------------------------------------------------
 * Author：PiJianZhong <jianzhongpi@163.com>
 * +----------------------------------------------------------------------
 */

namespace Jamespi\KafKa\Common;

abstract class Basic
{
    /**
     * 配置参数
     * @var
     */
    protected $paramConfig;

    public function __construct(array $config)
    {
        $this->paramConfig = $config;
        date_default_timezone_set('PRC');
    }

}