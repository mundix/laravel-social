<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivityLogEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $fileAttach;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        $this->fileAttach  = $file;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Bondeed - Admin Acitivy Log')
            ->from(config('mail.from.address'))
            ->attach($this->fileAttach)
            ->markdown('emails.admin.activities');
    }
}
