<?php

namespace App\Kafka;

use Illuminate\Queue\Connectors\ConnectorInterface;
use RdKafka\Conf;
use RdKafka\KafkaConsumer;
use RdKafka\Producer;

class KafkaConnector implements ConnectorInterface
{

    public function connect(array $config)
    {
        $conf = new Conf();

        $conf->set('metadata.broker.list', $config['brokers']);
        $conf->set('compression.type', 'snappy');
        $conf->set('enable.auto.commit', 'false');

        if ($config['debug']) {
            $conf->set('log_level', LOG_DEBUG);
            $conf->set('debug', 'all');
        }

        $producer = new Producer($conf);

        $conf->set('group.id', $config['group_id']);
        $conf->set('auto.offset.reset', 'earliest'); // earliest,latest,none

        $consumer = new KafkaConsumer($conf);

        return new KafkaQueue($consumer, $producer);
    }
}
