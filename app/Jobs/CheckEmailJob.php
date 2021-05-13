<?php

declare(strict_types = 1);

namespace App\Jobs;

use App\Callback;
use App\Http\Controllers\Traits\TelegramTrait;
use App\Message;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

/**
 * Class CheckEmailJob
 *
 * @package App\Jobs
 */
class CheckEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, TelegramTrait;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $config = Config::get('mail.imap');

        $mailuser= Arr::get($config, 'user');
        $mailpass = Arr::get($config, 'password');
        
        $mailhost='{rapid-recycle.com:993/imap/ssl/novalidate-cert}INBOX';
        
        $mailbox = imap_open($mailhost,$mailuser,$mailpass) or die("<br />\nFAILLED! ".imap_last_error());

        $emails = imap_search($mailbox,'UNSEEN');

        if ($emails) {
            foreach ($emails as $id) {
                $header = imap_headerinfo($mailbox, $id);
                
                $fromEmail = $header->from[0]->mailbox . "@" . $header->from[0]->host;
    
                $fromName = $header->from[0]->personal;
    
                $message = imap_fetchbody($mailbox, $id, "1.2");
    
                if ($message === "") {
                    $message = imap_fetchbody($mailbox, $id, "1.1");
                }
    
                if ($message === "") {
                    $message = imap_fetchbody($mailbox, $id, "2");
                }
    
                $time = Carbon::parse($header->date);
    
                if (Callback::query()->where('email', '=', $fromEmail)->exists()) {
                    $callback = Callback::query()->where('email', '=', $fromEmail)->first();
        
                    Message::query()->create(
                        [
                            'name' => $fromName,
                            'email' => $fromEmail,
                            'text' => $message,
                            'sender' => Callback::SENDER_FROM,
                            'chat_id' => $callback->getKey(),
                            'time' => $time,
                        ]
                    );

                    $this->sendMessage($message ? $message : 'New message', route('admin.callback.edit', ['callback' => $callback->getKey()]));
                } else {
                    $user = User::query()->where('email', '=', $fromEmail)->first();
        
                    $callback = Callback::query()->create(
                        [
                            'name' => $user ? $user->getAttribute('name') : $fromName,
                            'email' => $fromEmail,
                            'text' => $message,
                            'sender' => Callback::SENDER_FROM,
                        ]
                    );
        
                    Message::query()->create(
                        [
                            'name' => $fromName,
                            'email' => $fromEmail,
                            'text' => $message,
                            'sender' => Callback::SENDER_FROM,
                            'chat_id' => $callback->getKey(),
                            'time' => $time,
                        ]
                    );

                    $this->sendMessage($message ? $message : 'New message', route('admin.callback.edit', ['callback' => $callback->getKey()]));
                }
            }
        }

        imap_close($mailbox);
    }
}
