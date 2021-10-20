<?php

namespace App\Http\Livewire\Companies\Onboarding;

use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyOnboardingPrimaryColorComponent extends Component
{
    use SupportUiNotification;

    public $company;
    public $currentColor;
    public $colors;

    public function render()
    {
        return view('livewire.companies.onboarding.company-onboarding-primary-color-component');
    }

    public function mount()
    {
        $this->currentColor = $this->company->primary_color;
        for($i=1; $i <= 14 ; $i++){
            $this->colors['color' . $i] = 'primary-color-' . $i;
        }
    }

    public function setColor($color)
    {
        $this->currentColor = $color;
        $this->company->update(['primary_color' => $color]);

        $this->alert()->success([
            'title' => 'Primary Color updated'
        ]);

        $this->emit('updateThemePrimaryColor', $color);
    }
}
