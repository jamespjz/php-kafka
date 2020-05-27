<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/22
 * Time: 10:45
 */

namespace Jamespi\KafKa\Consumer\ConsumerServerApi;

use Jamespi\KafKa\Common\Common;
use Jamespi\KafKa\Common\Basic;
use Jamespi\KafKa\Common\ConfigurationTrait;
class ConsumerServerApi extends Basic
{
    use ConfigurationTrait;

    /**
     * kafka 配置参数
     * @var \Kafka\ProducerConfig|ConsumerConfig
     */
    protected $config;

    public function init()
    {
        $this->config = \Kafka\ConsumerConfig::getInstance();
    }

    /**
     * 消费消息
     */
    public function consumerMessage(){
        $this->init();
        $this->config->setMetadataRefreshIntervalMs($this->paramConfig['refresh_interval_time']);
        $this->config->setMetadataBrokerList($this->paramConfig['Basic']['host'], $this->paramConfig['Basic']['port']);
        $this->config->setGroupId($this->paramConfig['group_id']);
        $this->config->setBrokerVersion($this->paramConfig['broker_version']);
        $this->config->setTopics($this->paramConfig['topics']);

        $consumer = new \Kafka\Consumer();
        $consumer->start(function($topic, $part, $message) {
            var_dump($message);
        });
    }
}