<?php

declare(strict_types = 1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

/**
 * Class SendToMailLabelJob
 *
 * @package App\Jobs
 */
class SendToMailLabelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    protected $order;

    /**
     * @var string
     */
    protected $pdfUrl;

    /**
     * Create a new job instance.
     * 
     * @param array $order
     * @param string $pdfUrl
     *
     * @return void
     */
    public function __construct(array $order, string $pdfUrl)
    {
        $this->order = $order;
        $this->pdfUrl = $pdfUrl;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $data = $this->order;
        $pdf = $this->pdfUrl;

        try {
            Mail::send('mail.label_created',  $data, function($message) use($data, $pdf) {
                $message->to($data['user_email'])
                        ->from('info@rapid-recycle.com', 'rapid-recycle.com')
                        ->subject("Your FedEx label is created")
                        ->attach($pdf);
            });
        } catch (\Exception $e) {}
    }
}
