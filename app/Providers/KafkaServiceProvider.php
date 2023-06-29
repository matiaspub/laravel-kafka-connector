<?php

namespace App\Providers;

use App\Kafka\KafkaConnector;
use Illuminate\Support\ServiceProvider;

class KafkaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $manager = $this->app['queue'];
        $manager->addConnector('kafka', fn() => new KafkaConnector());
    }
}
