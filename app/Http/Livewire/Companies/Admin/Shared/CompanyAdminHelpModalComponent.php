<?php

namespace App\Http\Livewire\Companies\Admin\Shared;

use App\Mail\HelpMail;
use App\Models\User;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CompanyAdminHelpModalComponent extends Component
{
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $topic;
    public $question;

    protected $rules = [
        'question' => 'required',
        'topic' => 'required',
    ];

    public function render()
    {
        return view('livewire.companies.admin.shared.company-admin-help-modal-component');
    }

    public function sent()
    {
        $validator = \Validator::make([
            'question' => $this->question,
            'topic' => $this->topic,
        ], $this->rules);

        if ($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }
        $adminsEmail = User::wherein('type', ['admin', 'super'])->pluck('email')->toArray();
        try {
            \Mail::to($adminsEmail)->send(new HelpMail(auth()->user()->email, $this->topic, $this->question));
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        $this->resetInputs();
        $this->alert()->success(['title' => 'Your message has been sent']);
        $this->emit('closeModals');
    }

    public function resetInputs()
    {
        $this->reset([
            'question',
            'topic',
        ]);
    }
}
