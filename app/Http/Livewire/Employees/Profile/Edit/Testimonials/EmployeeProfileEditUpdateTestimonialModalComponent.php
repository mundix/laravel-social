<?php

namespace App\Http\Livewire\Employees\Profile\Edit\Testimonials;

use App\Models\Testimonial;
use App\Services\CauseService;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmployeeProfileEditUpdateTestimonialModalComponent extends Component
{

    use WithFileUploads;
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $testimonial;
    public $company;
    public $name;
    public $content;
    public $causes;
    public $cause_id;

    protected $listeners = [
        'setEmployeeProfileEditUpdateTestimonialModalComponent' => 'setEdit'
    ];

    public $rules = [
        'cause_id' => ['numeric', 'required'],
        'content' => 'required'
    ];

    public function render()
    {
        return view('livewire.employees.profile.edit.testimonials.employee-profile-edit-update-testimonial-modal-component');
    }

    public function setEdit($id)
    {
        $testimonial = Testimonial::find($id);
        $this->testimonial = $testimonial;

        $this->cause_id = $testimonial->cause->id;
        $this->content = $testimonial->content;
        $this->causes = CauseService::getCompanyCauses($this->company, null);
        $this->emit('openEditTestimonialModal');
    }

    public function updatedCauseId($value)
    {

        $validator = \Validator::make(['cause_id' => $value] , ['cause_id' => 'required']);
        if($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }

        $this->testimonial->update(['cause_id' => $value]);
        $this->alert()->success(['title' => 'Testimonial cause was updated']);
        $this->emit('companyProfileEditCommunityComponent');
        $this->emit('refreshCompanyProfileEditTestimonialsLoopComponent');
        $this->emit('renderCompanyProfileEditTestimonialsLoopComponent');
        $this->emit('updateDOM');
    }

    public function updatedContent($value)
    {
        $validator = \Validator::make(['content' => $value] , ['content' => 'required']);
        if($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }

        $this->testimonial->update(['content' => $value]);
        $this->alert()->success(['title' => 'Testimonial Content updated']);
        $this->emit('companyProfileEditCommunityComponent');
        $this->emit('refreshCompanyProfileEditTestimonialsLoopComponent');
        $this->emit('renderCompanyProfileEditTestimonialsLoopComponent');
        $this->emit('updateDOM');
    }


}
