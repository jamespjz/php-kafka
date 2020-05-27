<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/22
 * Time: 10:45
 */

namespace Jamespi\KafKa\Producer\ProducerServerApi;

use Jamespi\KafKa\Common\Common;
use Jamespi\KafKa\Common\Basic;
use Jamespi\KafKa\Common\ConfigurationTrait;
class ProducerServerApi extends Basic
{
    use ConfigurationTrait;

    /**
     * kafka 配置参数
     * @var \Kafka\ProducerConfig|ConsumerConfig
     */
    protected $config;

    public function init()
    {
        $this->config = \Kafka\ProducerConfig::getInstance();
    }

    /**
     * 发送消息
     */
    public function addMessage()
    {
        $this->init();
        $this->config->setMetadataRefreshIntervalMs($this->paramConfig['refresh_interval_time']);
        $this->config->setMetadataBrokerList($this->paramConfig['Basic']['host'], $this->paramConfig['Basic']['port']);
        $this->config->setBrokerVersion($this->paramConfig['broker_version']);
        $this->config->setRequiredAck($this->paramConfig['required_ack']);
        $this->config->setIsAsyn($this->paramConfig['is_async']);
        $this->config->setProduceInterval($this->paramConfig['produce_interval_time']);

        if ($this->paramConfig['is_async']){
            //异步
            $producer = new \Kafka\Producer(function (){
                return [
                    [
                        'topic' => $this->paramConfig['topic'],
                        'value' => $this->paramConfig['msg'],
                        'key' => $this->paramConfig['key'],
                    ],
                ];
            });
            $producer->success(function($result) {
                var_dump($result);
            });
            $producer->error(function($errorCode) {
                var_dump($errorCode);
            });
            $producer->send(true);
        }else{
            //同步
            $producer = new \Kafka\Producer();
            if ($this->paramConfig['is_batch']){
                for($i = 0; $i < $this->paramConfig['batch_number']; $i++) {
                    $result = $producer->send(array(
                        array(
                            'topic' => $this->paramConfig['topic'],
                            'value' => $this->paramConfig['msg'],
                            'key' => $this->paramConfig['key'],
                        ),
                    ));
                }
            }
        }
    }

}