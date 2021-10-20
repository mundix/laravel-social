<?php

namespace App\Mail;

use App\Models\CompanyInvite;
use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompanyInviteMail extends Mailable
{
    use Queueable, SerializesModels;
    protected  $employee, $company, $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(CompanyInvite $companyInvite)
    {
        $this->employee = $companyInvite->employee;
        $this->company = $companyInvite->company;
        $this->token = $companyInvite->employee->user->user_token->token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Bondeed - '.$this->company->name.' Introduction')
            ->from(config('mail.from.address'))
            ->markdown('emails.companies.invite')->with(['employee'=>$this->employee,'token' => $this->token, 'company' => $this->company]);
    }
}
