<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Testimonials;

use App\Models\Testimonial;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyProfileEditTestimonialsLoopComponent extends Component
{
    use SupportUiNotification;
    public $testimonial;
    public $isEmployee = false;

    protected $listeners = [
        'deleteTestimonial' => 'doDelete',
        'refreshCompanyProfileEditTestimonialsLoopComponent' => '$refresh',
        'renderCompanyProfileEditTestimonialsLoopComponent' => 'render'
    ];

    public function render()
    {
        return view('livewire.companies.profile.edit.testimonials.company-profile-edit-testimonials-loop-component');
    }

    public function mount()
    {
        if(auth()->check() && auth()->user()->type === 'employee') {
            $this->isEmployee = true;
        }

    }

    public function delete()
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Are you sure you want to delete this?' ,
            'confirmButtonText' => 'Yes',
            'method' => 'deleteTestimonial',
            'params' => $this->testimonial->id
        ]);
    }

    public function doDelete($id)
    {
        $obj = Testimonial::find($id);
        if($obj) {
            $obj->delete();
            $this->alert()->success(['title' => 'Testimonial deleted']);
        }
        $this->emit('companyProfileEditCommunityComponent');
        $this->emit('renderCompanyProfileEditCommunityComponent');
    }
}
