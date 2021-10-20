<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Testimonials;

use App\Models\Testimonial;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyProfileEditNewTestimonialModalComponent extends Component
{
    use WithFileUploads;
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $picture = null;
    public $company;
    public $employee;
    public $name;
    public $content;
    public $job_title;

    public $rules = [
        'picture' => 'image',
        'name' => ['required', 'string', 'max:255'],
        'content' => ['required'],
        'job_title' => ['required', 'string', 'max:255'],
    ];

    public function render()
    {
        return view('livewire.companies.profile.edit.testimonials.company-profile-edit-new-testimonial-modal-component');
    }

    public function save()
    {
        $data = [
            'name' => $this->name,
            'picture' => $this->picture,
            'content' => $this->content,
            'job_title' => $this->job_title,
        ];
        $validator = \Validator::make($data, $this->rules);

        if ($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }

        $data['status'] = 'approved';

        $testimonial = Testimonial::create($data);
        $this->company->user->testimonials()->save($testimonial);

        if ($this->picture) {
            $file_name = 'picture_' . $testimonial->id . '.' . $this->picture->getClientOriginalExtension();
            $testimonial->addMedia($this->picture->getRealPath())->usingName($file_name)->toMediaCollection('picture');
        }

        $this->alert()->success(['title' => 'Your testimonial is already submitted']);
        $this->emit('openSuccessTestimonial');
        $this->emit('companyProfileEditCommunityComponent');
        $this->resetInputs();
        $this->emit('closeModals');

    }

    public function resetInputs()
    {
        $this->reset(['name', 'content', 'job_title']);
        $this->picture = null;
    }

    public function mount($company = null, $employee = null)
    {
        $this->employee = $employee;
        $this->company = $company;
    }
}
