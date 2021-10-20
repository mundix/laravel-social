<?php

namespace App\Mail;

use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteAdminEmail extends Mailable
{
    use Queueable, SerializesModels;
    protected  $admin;
    protected  $password;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Admin  $admin, $password)
    {
        $this->admin = $admin;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'))->subject(config('app.name'))->markdown('emails.admin.invites.admins', [
            'user' => $this->admin->user,
            'password' => $this->password
        ]);
    }
}
