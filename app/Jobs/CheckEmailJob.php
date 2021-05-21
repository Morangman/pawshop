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
use Client;

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
        $imapConfig = Config::get('mail.imap');

        foreach($imapConfig as $config) {
            $mailuser = $config['user'];
            $mailpass = $config['password'];

            $mailhost = '{rapid-recycle.com:993/imap/ssl/novalidate-cert}INBOX';

            $mailbox = imap_open($mailhost,$mailuser,$mailpass) or die("<br />\nFAILLED! ".imap_last_error());

            $emails = imap_search($mailbox,'UNSEEN');

            if ($emails) {
                $this->getMassages($mailbox, $emails);
            }
    
            imap_close($mailbox);
        }
    }

    /**
     * @param $mailbox
     * @param $emails
     *
     * @return void
     */
    public function getMassages($mailbox, $emails)
    {
        foreach ($emails as $id) {
            $header = imap_headerinfo($mailbox, $id);

            $structure = imap_fetchstructure($mailbox, $id);
    
            if (isset($structure->parts) && is_array($structure->parts) && isset($structure->parts[1])) {
                $part = $structure->parts[1];

                $message = imap_fetchbody($mailbox, $id, '2');

                $message = $this->encodeMessage($part->encoding, $message);
            } else {
                $message = imap_fetchbody($mailbox, $id, "1.2");

                if ($message === "") {
                    $message = imap_fetchbody($mailbox, $id, "1.1");
                }
    
                if ($message === "") {
                    $message = imap_fetchbody($mailbox, $id, "1");
                }
    
                if ($message === "") {
                    $message = imap_fetchbody($mailbox, $id, "2");
                }
            }

            $supportIsset = strpos($message, 'support@rapid-recycle.com');

            $infoIsset = strpos($message, 'info@rapid-recycle.com');
            
            if ($supportIsset) {
                $message = substr($message, 0, strpos($message, 'support@rapid-recycle.com'));
            }
            
            if ($infoIsset) {
                $message = substr($message, 0, strpos($message, 'info@rapid-recycle.com'));
            }
            
            $fromEmail = $header->from[0]->mailbox . "@" . $header->from[0]->host;

            $fromName = imap_utf8($header->from[0]->personal);

            $time = Carbon::parse($header->date);

            if (Callback::query()->where('email', '=', $fromEmail)->exists()) {
                $callback = Callback::query()->where('email', '=', $fromEmail)->first();

                $callback->touch();
    
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

    public function encodeMessage($encoding, $message)
    {
        switch ($encoding) {
            # 7BIT
            case 0:
                return $message;
            # 8BIT
            case 1:
                return quoted_printable_decode(imap_8bit($message));
            # BINARY
            case 2:
                return imap_binary($message);
            # BASE64
            case 3:
                return imap_base64($message);
            # QUOTED-PRINTABLE
            case 4:
                return quoted_printable_decode($message);
            # OTHER
            case 5:
                return $message;
            # UNKNOWN
            default:
                return $message;
        }
    }
}
