<?php

namespace Jamespi\KafKa\Common;

trait ConfigurationTrait{

    abstract public function init();

    /**
     * 主题元数据刷新间隔（以毫秒为单位）
     * 错误时元数据会自动刷新并连接。使用-1禁用间隔刷新。
     * @param int $refreshIntervalTime
     */
    protected function setMetadataRefreshIntervalMs(int $refreshIntervalTime){
        $this->config->setMetadataRefreshIntervalMs($refreshIntervalTime);
    }

    /**
     * Kafka Broker服务器列表
     * @param string $host
     * @param string $port
     */
    protected function setMetadataBrokerList(string $host, string $port){
        $url = $host.":".$port;
        $this->config->setMetadataBrokerList($url);
    }

    /**
     * 用户提供的节点版本
     * @param string $brokerVersion
     */
    protected function setBrokerVersion(string $brokerVersion){
        $this->config->setBrokerVersion($brokerVersion);
    }

    /**
     * 消息确认模式
     * 0 =经纪人不向客户端发送任何响应/确认
     * 1 =只有领导经纪人需要确认消息
     * -1或全部=代理将阻塞，直到所有同步副本（ISR）或代理的in.sync.replicas设置提交了消息，然后再发送响应
     * @param int $requiredAck
     */
    protected function setRequiredAck(int $requiredAck){
        $this->config->setRequiredAck($requiredAck);
    }

    /**
     * 是否使用异步生产消息
     * @param bool $isSync
     */
    protected function setIsAsyn(bool $isSync){
        $this->config->setIsAsyn($isSync);
    }

    /**
     * 异步生成消息时执行生产消息请求的时间间隔
     * @param int $produceIntervalTime
     */
    protected function setProduceInterval(int $produceIntervalTime){
        $this->config->setProduceInterval($produceIntervalTime);
    }

    /**
     * 客户端组ID字符串。共享相同group.id的所有客户端都属于同一组
     * @param string $groupId
     */
    protected function setGroupId(string $groupId){
        $this->config->setGroupId($groupId);
    }

    /**
     * 想要消费者主题
     * @param array $topics
     */
    protected function setTopics(array $topics){
        $this->config->setTopics($topics);
    }
}