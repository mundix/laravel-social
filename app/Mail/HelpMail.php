<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HelpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailFrom;
    public $topic;
    public $question;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($emailFrom, $topic, $question)
    {
        $this->emailFrom = $emailFrom;
        $this->topic = $topic;
        $this->question = $question;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Help Question: ' . $this->topic)
            ->from($this->emailFrom)
            ->markdown('emails.companies.admin.company-admin-help-mail', [
                'topic' => $this->topic,
                'question' => $this->question,
            ]);
    }
}
