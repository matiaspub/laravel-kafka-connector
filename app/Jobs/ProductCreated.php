<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProductCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, WithFaker;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private readonly array $data)
    {
        $this->queue = 'default';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        User::create([
            'name' => "{$this->data['product_name']} - {$this->data['product_stock']}",
            'email' => $this->faker->email
        ]);
    }
}
