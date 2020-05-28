<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/22
 * Time: 10:45
 */

namespace Jamespi\KafKa\Producer;

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
        $this->setMetadataRefreshIntervalMs($this->paramConfig['refresh_interval_time']);
        $this->setMetadataBrokerList($this->paramConfig['Basic']['host'], $this->paramConfig['Basic']['port']);
        $this->setBrokerVersion($this->paramConfig['broker_version']);
        $this->setRequiredAck($this->paramConfig['required_ack']);
        $this->setIsAsyn($this->paramConfig['is_async']);
        $this->setProduceInterval($this->paramConfig['produce_interval_time']);

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
                return Common::resultMsg('success', '发送消息成功', $result);
            });
            $producer->error(function($errorCode) {
                return Common::resultMsg('failed', '发送消息失败，Error：'.$errorCode);
            });
            $producer->send(true);
        }else{
            //同步
            $producer = new \Kafka\Producer();
            for($i = 0; $i < $this->paramConfig['batch_number']; $i++) {
                $result = $producer->send([
                    [
                        'topic' => $this->paramConfig['topic'],
                        'value' => $this->paramConfig['msg'][$i],
                        'key' => $this->paramConfig['key'],
                    ],
                ]);
                if (!$result){
                    return Common::resultMsg('failed', '发送消息失败');
                }
            }
            return Common::resultMsg('success', '发送消息成功');
        }
    }

}