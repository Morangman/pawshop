<?php

declare(strict_types = 1);

namespace App\Jobs;

use App\Callback;
use App\EmailFile;
use App\Http\Controllers\Traits\TelegramTrait;
use App\Message;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use ImapReader;

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

            # set the mark as read flag (true by default). If you don't want emails to be marked as read/seen, set this to false.
            $mark_as_read = true;

            # You can ommit this to use UTF-8 by default.
            $encoding = 'UTF-8';
            
            $mailhost = '{rapid-recycle.com:993/imap/ssl/novalidate-cert}';

            # create a new Reader object
            $imap = new ImapReader($mailhost, $mailuser, $mailpass, public_path() . '/imap', $mark_as_read, $encoding);

            # You can then loop through $imap->emails() for each email.
            foreach ($imap->unseen()->get() as $email) {

                $fromEmail = $email->fromEmail();

                $fromName = $email->fromName();

                // $excludeEmailArray = [
                //     'MAILER-DAEMON@mx-out-15.default-host.net',
                //     'wordpress@partner.rapidrefurb.net'
                // ];
    
                // if (in_array($fromEmail, $excludeEmailArray)) {
                //     continue;
                // }
    
                if (str_contains($fromEmail, 'Mailer-Daemon')) {
                    continue;
                }
    
                $time = Carbon::parse($email->date);

                $message = $email->html();

                $simpleMessage = mb_substr(strip_tags($email->plain()), 0, 50);

                if (Callback::query()->where('email', '=', $fromEmail)->exists()) {
                    $callback = Callback::query()->where('email', '=', $fromEmail)->first();

                    if ($callback->is_blocked) {
                        continue;
                    }
    
                    $callback->touch();
                } else {
                    $user = User::query()->where('email', '=', $fromEmail)->first();
        
                    $callback = Callback::query()->create(
                        [
                            'name' => $user ? $user->getAttribute('name') : $fromName,
                            'email' => $fromEmail,
                            'text' => $simpleMessage,
                            'sender' => Callback::SENDER_FROM,
                        ]
                    );
                }

                $messageModel = Message::query()->create(
                    [
                        'name' => $fromName,
                        'email' => $fromEmail,
                        'text' => $message,
                        'simple_text' => $simpleMessage,
                        'sender' => Callback::SENDER_FROM,
                        'chat_id' => $callback->getKey(),
                        'time' => $time,
                    ]
                );

                if ($email->hasAttachments()) {
                    foreach ($email->attachments() as $file) {
                        EmailFile::query()->create([
                            'chat_id' => $callback->getKey(),
                            'message_id' => $messageModel->id,
                            'url' => '/imap/' . $file->name,
                            'mime' => $file->mime,
                            'type' => $file->type,
                        ]);
                    }
                }

                try {
                    $this->sendMessage(
                        $simpleMessage ? $simpleMessage : 'New message',
                        route('admin.callback.edit', ['callback' => $callback->getKey()])
                    );
                } catch (\Exception $e) {}
            }
        }
    }
}
