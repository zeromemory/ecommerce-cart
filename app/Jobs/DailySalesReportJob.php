<?php

namespace App\Jobs;

use App\Mail\DailySalesReport;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class DailySalesReportJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get yesterday's date
        $yesterday = Carbon::yesterday();

        // Get all orders from yesterday
        $orders = Order::with('user')
            ->whereDate('created_at', $yesterday)
            ->get();

        // Calculate total sales
        $totalSales = $orders->sum('total_amount');

        // Send report to admin
        $adminEmail = 'admin@example.com';

        Mail::to($adminEmail)->send(
            new DailySalesReport(
                $orders,
                $totalSales,
                $yesterday->format('F d, Y')
            )
        );
    }
}
