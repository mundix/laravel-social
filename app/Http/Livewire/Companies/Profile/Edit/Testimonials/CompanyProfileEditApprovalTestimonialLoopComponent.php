<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Testimonials;

use App\Models\Testimonial;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyProfileEditApprovalTestimonialLoopComponent extends Component
{
    use SupportUiNotification;

    public $testimonial;
    public $isRemoved = false;
    protected $listeners = ['removeTestimonial' => 'doRemove'];

    public function render()
    {
        return view('livewire.companies.profile.edit.testimonials.company-profile-edit-approval-testimonial-loop-component');
    }

    public function approve($id)
    {
        $testimonial = Testimonial::find($id);
        $testimonial->update(['status' => 'approved']);
        $this->alert()->success(['title' => 'Your Testimonial was approved']);
        $this->emit('companyProfileEditCommunityComponent');
        $this->emit('updateDOM');
        $this->isRemoved = true;

    }

    public function decline($id)
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Do you want to remove ' . $this->testimonial->name . '?' ,
            'confirmButtonText' => 'Yes',
            'method' => 'removeTestimonial',
            'params' => $id
        ]);
    }

    public function doRemove($id)
    {
        $testimonial = Testimonial::find($id);
        $testimonial->delete();
        $this->alert()->success(['title' => 'Your Testimonial was declined']);
        $this->emit('companyProfileEditCommunityComponent');
        $this->emit('updateDOM');
        $this->isRemoved = true;

    }
}
