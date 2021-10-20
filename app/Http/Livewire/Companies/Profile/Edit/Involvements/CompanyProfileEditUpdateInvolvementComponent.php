<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Involvements;

use App\Models\Involvement;
use App\Traits\SupportUiNotification;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyProfileEditUpdateInvolvementComponent extends Component
{
    use WithFileUploads;
    use SupportUiNotification;

    public $involvement = null;
    public $openModal = false;
    public $icon_name;
    public $picture;
    public $currentPicture;
    public $title;
    public $number;

    protected $listeners = [
        'setCompanyProfileEditUpdateInvolvementComponent' => 'setEdit'
    ];

    public function render()
    {
        return view('livewire.companies.profile.edit.involvements.company-profile-edit-update-involvement-component');
    }

    public function setEdit($id)
    {
        $this->involvement = Involvement::find($id);

        $this->openModal = true;
        $this->title = $this->involvement->title;
        $this->number = $this->involvement->number;
        $this->icon_name = $this->involvement->icon_name;

        $this->currentPicture = $this->involvement->picture->url ?? null;

        $this->emit('openEditInvolvementModal');
        $this->emit('updateDOM');
    }

    public function hydrate()
    {
        $this->emit('updateDOM');
    }

    public function updatedTitle($value)
    {
        $this->validate([
            'title' => 'required'
        ]);
        $this->involvement->update(['title' => $value]);
        $this->emit('companyProfileEditInvolvementsComponent');
        $this->alert()->success(['title' => 'Involvement title updated']);
        $this->emit('CompanyProfileEditUpdateInvolvementComponent');
    }

    public function updatedNumber($value)
    {
        $this->validate([
            'number' => 'required'
        ]);
        $this->involvement->update(['number' => $value]);
        $this->alert()->success(['title' => 'Involvement Number Updated']);
        $this->emit('CompanyProfileEditUpdateInvolvementComponent');
    }

    /**
     * Livewire Hooks, when a property updated by any interaction, in this case when choose an image.
     */
    public function updatedPicture()
    {
        $this->validate([
            'picture' => 'image',
        ]);

        $file_name = 'involvement_picture_' . $this->involvement->id . '.' . $this->picture->getClientOriginalExtension();

        $this->involvement->addMedia($this->picture->getRealPath())->usingName($file_name)->toMediaCollection('picture');
        $this->currentPicture = $this->involvement->picture->url;
        $this->alert()->success(['title' => 'Involvement Picture Updated', 'position' => 'top-end']);
        $this->emit('CompanyProfileEditUpdateInvolvementComponent');
    }

    public function setIconName($value)
    {
        $this->involvement->update(['icon_name' => $value]);
        $this->icon_name = $value;
        $this->alert()->success(['title' => 'Involvement Icon updated']);
        $this->emit('CompanyProfileEditUpdateInvolvementComponent');
    }
}
