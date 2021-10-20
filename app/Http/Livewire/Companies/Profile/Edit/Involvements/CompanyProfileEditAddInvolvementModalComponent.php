<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Involvements;

use App\Models\Involvement;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyProfileEditAddInvolvementModalComponent extends Component
{

    use WithFileUploads;
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $picture;
    public $icon_name;
    public $title;
    public $number;
    public $company;

    public $rules = [

    ];

    public function render()
    {
        return view('livewire.companies.profile.edit.involvements.company-profile-edit-add-involvement-modal-component');
    }

    public function save()
    {
        $data = [
            'title' => $this->title,
            'number' => $this->number,
            'icon_name' => $this->icon_name,
        ];

        $validator = \Validator::make(
            [
                'title' => $this->title,
                'number' => $this->number,
                'icon_name' => $this->icon_name,
                'picture' => $this->picture
            ], [
                'title' => 'required',
                'number' => 'required',
                'icon_name' => 'required',
                'picture' => 'image|max:'. config('bondeed.uploads.limits.size')
            ]
        );
        if($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }

        $involvement = Involvement::create($data);
        $this->company->involvements()->save($involvement);

        if ($this->picture) {
            $file_name = 'picture_' . $involvement->id . '.' . $this->picture->getClientOriginalExtension();
            $involvement->addMedia($this->picture->getRealPath())->usingName($file_name)->toMediaCollection('picture');
            $this->picture = null;
        }
        session()->flash('notification_title' ,'Involvement Created');
        return redirect()->route('company.admin.index');
    }

    public function resetInputs()
    {
        $this->title = '';
        $this->number = '';
        $this->picture = null;
    }

    public function setIconName($value)
    {
        $this->icon_name = $value;
    }
}
