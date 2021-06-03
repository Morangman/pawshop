<?php

declare(strict_types = 1);

namespace App\Jobs;

use App\Mail\OrderRestoredMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

/**
 * Class OrderRestoredMailJob
 *
 * @package App\Jobs
 */
class OrderRestoredMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    protected $data;

    /**
     * Create a new job instance.
     * 
     * @param array $data
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            Mail::to($this->data['order']['user_email'])
                ->send(new OrderRestoredMail(
                    array_merge(
                        $this->data['order'],
                        [
                            'user_name' => $this->data['user_name'],
                        ]
                    )
                ));
        } catch (\Exception $e) {}
    }
}
