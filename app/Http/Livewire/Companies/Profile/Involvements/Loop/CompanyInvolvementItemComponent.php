<?php

namespace App\Http\Livewire\Companies\Profile\Involvements\Loop;

use Livewire\Component;

class CompanyInvolvementItemComponent extends Component
{

    public $user, $company, $involvement;
    public $title, $number, $picture, $picture_preview, $icon, $icon_preview;

    protected $listeners = [
        'refreshCompanyInvolvementComponent' => 'render',
        'removeInvolvement' => 'deleteInvolvement'
    ];

    public function render()
    {
        return view('livewire.companies.profile.involvements.loop.company-involvement-item-component');
    }

    public function mount($involvement)
    {
        $this->involvement = $involvement;
        $this->title = $involvement->title;
        $this->number = $involvement->number;
        $this->picture_preview = $involvement->picture->url;
        $this->icon_preview = $involvement->icon->url;
    }

    public function updatedPicture()
    {
        $this->validate([
            'picture' => 'image',
        ]);
        if ($this->picture) {
            $file_name = 'involvement_picture_' . $this->involvement->id . ' . ' . $this->picture->getClientOriginalExtension();
            $this->involvement->addMedia($this->picture->getRealPath())->usingName($file_name)->toMediaCollection('picture');
        }
    }

    public function updatedIcon()
    {
        $this->validate([
            'picture' => 'image',
        ]);
        if ($this->picture) {
            $file_name = 'involvement_icon_' . $this->involvement->id . ' . ' . $this->icon->getClientOriginalExtension();
            $this->involvement->addMedia($this->icon->getRealPath())->usingName($file_name)->toMediaCollection('icon');
        }
    }
}
