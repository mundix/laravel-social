<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Testimonials;

use Livewire\Component;

class CompanyProfileEditApprovalTestimonialsComponent extends Component
{

    public $company;
    public $pageName = 'TestimonialsApprove';

    public function render()
    {
        $testimonials = $this->company->testimonials()
            ->where('status', 'pending')
            ->where('referral_id', '<>', null)
            ->orderBy('id', 'DESC')
            ->paginate(config('bondeed.frontend.dashboards.limit'), ['*'], $this->pageName);
        return view('livewire.companies.profile.edit.testimonials.company-profile-edit-approval-testimonials-component',
            [
                'testimonials' => $testimonials
            ]);
    }


    public function mount($company)
    {
        $this->company = $company;
    }

    public function paginationView()
    {
        return 'vendor.livewire.custom-pagination-foundation';
    }
}
