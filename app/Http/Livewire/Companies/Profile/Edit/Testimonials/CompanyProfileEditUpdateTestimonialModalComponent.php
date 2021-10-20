<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Testimonials;

use App\Models\Testimonial;
use App\Traits\SupportUiNotification;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyProfileEditUpdateTestimonialModalComponent extends Component
{
    use WithFileUploads;
    use SupportUiNotification;

    public $testimonial;
    public $picture;
    public $currentPicture;
    public $company;
    public $name;
    public $content;
    public $job_title;

    public $rules = [
        'picture' => 'image',
        'name' => 'required',
        'content' => 'required'
    ];


    protected $listeners = [
        'setCompanyProfileEditUpdateTestimonialModalComponent' => 'setEdit'
    ];

    public function render()
    {
        return view('livewire.companies.profile.edit.testimonials.company-profile-edit-update-testimonial-modal-component');
    }

    public function setEdit($id)
    {
        $testimonial = Testimonial::find($id);
        $this->testimonial = $testimonial;
        $this->name = $testimonial->name;
        $this->content = $testimonial->content;
        $this->job_title = $testimonial->job_title;
        $this->currentPicture = $this->testimonial->picture->url ?? null;
        $this->emit('openEditTestimonialModal');
    }

    /**
     * Livewire Hooks, when a property updated by any interaction, in this case when choose an image.
     */
    public function updatedPicture()
    {
        $this->validate([
            'picture' => 'image',
        ]);

        $file_name = 'testimonial_picture_' . $this->testimonial->id . '.' . $this->picture->getClientOriginalExtension();

        $this->testimonial->addMedia($this->picture->getRealPath())->usingName($file_name)->toMediaCollection('picture');
        $this->currentPicture = $this->testimonial->picture->url ?? null;
        $this->alert()->success(['title' => 'Testimonial  Picture Updated']);
    }

    public function updatedName($value)
    {
        $this->validate([
            'name' => 'required'
        ]);
        $this->testimonial->update(['name' => $value]);
        $this->alert()->success(['title' => 'Testimonial title updated']);
    }

    public function updatedJobTitle($value)
    {
        $this->validate([
            'job_title' => 'required'
        ]);
        $this->testimonial->update(['job_title' => $value]);
        $this->alert()->success(['title' => 'Testimonial title updated']);
    }

    public function updatedContent($value)
    {
        $this->validate([
            'content' => 'required'
        ]);
        $this->testimonial->update(['content' => $value]);
        $this->alert()->success(['title' => 'Testimonial Content updated']);
    }
}
