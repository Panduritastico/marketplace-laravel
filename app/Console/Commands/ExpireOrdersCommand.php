<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ExpireOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire pending orders older than 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = Order::where('status', 'pending')
            ->where(
                'created_at',
                '<=',
                Carbon::now()->subHours(24)
            )
            ->get();

        if ($orders->isEmpty()) {

            $this->info(
                'No pending orders older than 24 hours found.'
            );

            return Command::SUCCESS;
        }

        foreach ($orders as $order) {

            $order->update([
                'status' => 'expired'
            ]);

            $this->line(
                "Order #{$order->id} expired."
            );
        }

        $this->info(
            "{$orders->count()} orders updated successfully."
        );

        return Command::SUCCESS;
    }
}
