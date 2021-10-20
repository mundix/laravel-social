<?php

namespace App\Http\Livewire\Companies\Profile\Involvements;

use App\Models\Involvement;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;


class CompanyInvolvementComponent extends Component
{
    use WithFileUploads;

    public $user, $company, $involvements;
    public $title, $number, $picture, $picture_preview, $icon, $icon_preview;

    protected $listeners = [
        'refreshCompanyInvolvementComponent' => '$refresh',
        'removeInvolvement' => 'deleteInvolvement'
    ];

    public function render()
    {
        return view('livewire.companies.profile.involvements.company-involvement-component');
    }

    public function mount()
    {
        $this->user = auth()->user();
        $this->company = $this->user->company;
        $this->involvements = $this->company->involvements;
    }

    public function save()
    {
        try {
            $this->validate([
                'title' => 'required',
                'number' => 'required',
                'icon' => 'image',
                'picture' => 'image',
            ]);
        } catch (ValidationException $e) {
            Log::error($e->getMessage());
        }

        $involvement = new Involvement();
        $data = [
            'title' => $this->title,
            'number' => $this->number,
        ];

        $involvement->fill($data);
        $involvement->save();

        if ($this->picture) {
            $file_name = 'involvement_picture_' . $involvement->id . ' . ' . $this->picture->getClientOriginalExtension();
            $involvement->addMedia($this->picture->getRealPath())->usingName($file_name)->toMediaCollection('picture');
        }

        if ($this->icon) {
            $file_name = 'involvement_icon_' . $involvement->id . ' . ' . $this->icon->getClientOriginalExtension();
            $involvement->addMedia($this->icon->getRealPath())->usingName($file_name)->toMediaCollection('icon');
        }
        $this->company->involvements()->save($involvement);
        $this->clearForm();
        $this->emit('refreshCompanyInvolvementComponent');
    }

    public function updatedPicture()
    {
        $this->validate([
            'picture' => 'image',
        ]);
        $this->picture_preview = $this->picture->temporaryUrl();
    }

    public function updatedIcon()
    {
        $this->validate([
            'icon' => 'image|max:3072',
        ]);
        $this->icon_preview = $this->icon->temporaryUrl();
    }

    private function clearForm()
    {
        $this->title = '';
        $this->number = '';
        $this->picture = null;
        $this->picture_preview = null;
        $this->icon = null;
        $this->icon_preview = null;
    }

}
