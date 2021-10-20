<?php

namespace App\Http\Livewire\Employees\Profile\Edit\Testimonials;

use App\Models\Cause;
use App\Models\Testimonial;
use App\Services\CauseService;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmployeeProfileEditNewTestimonialModalComponent extends Component
{
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $company;
    public $employee;
    public $name;
    public $content;
    public $causes;
    public $cause_id;

    public $rules = [
        'cause_id' => ['numeric','required'],
        'content' => ['required']
    ];

    public function render()
    {
        return view('livewire.employees.profile.edit.testimonials.employee-profile-edit-new-testimonial-modal-component');
    }

    public function save()
    {
        $user = auth()->user();

        $data = [
            'name' => \Str::random(0),
            'content' => $this->content,
            'cause_id' => $this->cause_id,
        ];
        $validator = \Validator::make($data, $this->rules);

        if ($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }

        $data['status'] = 'approved';

        $testimonial = Testimonial::create($data);
        $user->testimonials()->save($testimonial);

        $this->alert()->success(['title' => 'Your testimonial was successfully created. ']);

        $this->resetInputs();
        $this->emit('closeModals');
        $this->emit('companyProfileEditCommunityComponent');
        $this->emit('renderCompanyProfileEditCommunityComponent');
    }

    public function resetInputs()
    {
        $this->reset(['cause_id', 'content']);
    }

    public function mount($company = null, $employee = null)
    {
        $this->employee = $employee;
        $this->company = $company;
        $this->causes = CauseService::getCompanyCauses($company, null);
    }
}
