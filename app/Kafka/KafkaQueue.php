<?php

namespace App\Kafka;

use Illuminate\Contracts\Queue\Queue as QueueContract;
use Illuminate\Queue\Queue;
use RdKafka\KafkaConsumer;
use RdKafka\Producer;

class KafkaQueue extends Queue implements QueueContract
{
    private const CONSUME_TIMEOUT_SEC = 130;
    private const PRODUCE_TIMEOUT_SEC = 5;

    public function __construct(private readonly KafkaConsumer $consumer, private readonly Producer $producer)
    {

    }

    public function size($queue = null)
    {
        // TODO: Implement size() method.
    }

    public function push($job, $data = '', $queue = null)
    {
        $topic = $this->producer->newTopic($queue);
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, serialize($job));
        $this->producer->flush(self::PRODUCE_TIMEOUT_SEC * 1000);
    }

    public function pushRaw($payload, $queue = null, array $options = [])
    {
        // TODO: Implement pushRaw() method.
    }

    public function later($delay, $job, $data = '', $queue = null)
    {
        // TODO: Implement later() method.
    }

    public function pop($queue = null)
    {
        $this->consumer->subscribe([$queue]);
        try {
            $message = $this->consumer->consume(self::CONSUME_TIMEOUT_SEC * 1000);
            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    info('Kafka Message Received: ' . $message->payload);
                    $job = unserialize($message->payload);
                    $job->handle();
                    break;
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    logger()->error('Kafka Response Timeout Error');
                    break;
                default:
                    throw new \Exception($message->errstr(), $message->err);
            }
        } catch (\Exception $e) {
            logger()->error('Kafka Consumer Error: ' . $e->getMessage());
        }
    }
}
