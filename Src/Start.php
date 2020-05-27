<?php
/**+----------------------------------------------------------------------
 * JamesPi Redis [php-redis]
 * +----------------------------------------------------------------------
 * KafKa Basic Service Configuration file
 * +----------------------------------------------------------------------
 * Copyright (c) 2020-2030 http://www.pijianzhong.com All rights reserved.
 * +----------------------------------------------------------------------
 * Author：PiJianZhong <jianzhongpi@163.com>
 * +----------------------------------------------------------------------
 */

namespace Jamespi\KafKa;

use ReflectionClass;
use Jamespi\KafKa\Producer\ProducerServerApi;
use Jamespi\KafKa\Consumer\ConsumerServerApi;
use Jamespi\KafKa\Producer\Server\ProducerServer;
use Jamespi\KafKa\Consumer\Server\ConsumerServer;
class Start
{
    /**
     * 服务配置项
     * @var mixed
     */
    protected $config = [];
    /**
     * 业务场景类别
     * @var mixed
     */
    protected $type = 1;
    /**
     * 服务实例化对象
     * @var object
     */
    protected $model;

    public function __construct()
    {
        $this->config = require_once __DIR__.'/Config/Config.php';
    }

    /**
     * 启动服务
     * @param int $type 服务类型
     * @param array $config 服务配置
     * @return $this
     */
    public function run(int $type, array $config)
    {
        $this->type = $type;
        if (!empty($config)) $this->config = array_merge($this->config, $config);
        switch ($type){
            case 1:
                $this->model = (new ProducerServerApi($this->config));
                break;
            case 2:
                $this->model = (new ConsumerServerApi($this->config));
                break;
        }

        return $this;
    }

    public function __call(string $name, array $arguments)
    {
        // TODO: Implement __call() method.
        if ($this->type == 1)
            $server = new ProducerServer();
        else
            $server = new ConsumerServer();
        try{
            $class = new ReflectionClass($this->model);
            $class->getMethod($name);
            $data = call_user_func_array([$this->model, $name], [$server, $arguments[0]]);
            $data = json_decode($data, true);
            if ($data['status'] == 'success')
                return json_encode(['status'=>'success', 'msg'=>'调用成功！', 'data'=>isset($data['data'])?$data['data']:[]]);
            else
                return json_encode(['status'=> 'failed', 'msg'=>'Error：'.isset($data['msg'])?$data['msg']:[], 'data'=>isset($data['data'])?$data['data']:[]]);
        }catch (\Exception $e){
            return json_encode(['status'=> 'failed', 'msg'=>'Error：'.$e->getMessage()]);
        }
    }

}