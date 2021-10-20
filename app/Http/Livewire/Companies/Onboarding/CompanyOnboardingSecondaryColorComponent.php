<?php

namespace App\Http\Livewire\Companies\Onboarding;

use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyOnboardingSecondaryColorComponent extends Component
{

    use SupportUiNotification;

    public $company;
    public $currentColor;
    public $colors;

    public function render()
    {
        return view('livewire.companies.onboarding.company-onboarding-secondary-color-component');
    }

    public function mount()
    {
        $this->currentColor = $this->company->secondary_color;
        for($i=1; $i <= 14 ; $i++){
            $this->colors['color' . $i] = 'secondary-color-' . $i;
        }

    }

    public function setColor($color)
    {
        $this->currentColor = $color;
        $this->company->update(['secondary_color' => $color]);

        $this->alert()->success([
            'title' => 'Secondary Color updated'
        ]);

        $this->emit('updateThemeSecondaryColor', $color);
    }
}
