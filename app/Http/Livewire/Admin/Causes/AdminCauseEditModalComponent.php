<?php

namespace App\Http\Livewire\Admin\Causes;

use App\Models\Cause;
use App\Services\CauseService;
use App\Traits\SupportUiNotification;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminCauseEditModalComponent extends Component
{
    use WithFileUploads;
    use SupportUiNotification;

    public $cause = null;
    public $company;
    public $openModal = false;
    public $loading = false;
    public $picture;
    public $status;
    public $currentPicture;
    public $name;
    public $phone;
    public $location;
    public $locationType;
    public $email;
    public $website;
    public $description;
    public $category;
    public $matchable;
    public $message;
    public $nominateLabel;
    public $ImOwner = false;

    protected $listeners = [
        'setAdminCauseEditModalComponent' => 'setCause',
        'AdminCauseEditModalComponent' => '$refresh'
    ];

    protected $rules = [
        'name' => 'required|min:6',
        'email' => 'required|email:rfc,dns',
        'website' => 'required|url',
        'location' => 'required',
        'phone' => 'numeric|min:9|digits_between:9,11',
    ];

    public function render()
    {
        return view('livewire.admin.causes.admin-cause-edit-modal-component', [
            'categories' => CauseService::getCategories()
        ]);
    }

    public function save()
    {
        if (!$this->ImOwner) {
            $this->alert()->error(['title' => 'You are not allowed to update this cause']);
        } else {
            $this->loading = true;

            $data = [
                'name' => $this->name,
                'description' => $this->description,
                'email' => $this->email,
                'phone' => $this->phone,
                'category' => $this->category,
                'website' => $this->website,
                'location' => $this->location,
            ];

            $validator = \Validator::make($data, $this->rules);

            if ($validator->fails()) {
                $this->loading = false;
                $validator->validate();
            }
            $data['location_type'] = $this->locationType;
            $this->cause->update($data);
            $this->alert()->success(['title' => 'Your cause has been updated successfully']);
            $this->emit('adminCausesComponent');
            $this->emit('updateDOM');

            $this->closeModal();
        }
    }


    public function setCause($causeID)
    {
        $this->cause = Cause::find($causeID);
        $this->openModal = true;
        $this->name = $this->cause->name;
        $this->matchable = $this->cause->matchable;
        $this->website = $this->cause->website;
        $this->category = $this->cause->category;
        $this->phone = $this->cause->phone;
        $this->category = $this->cause->category_id;
        $this->email = $this->cause->email;
        $this->description = $this->cause->description;
        $this->location = $this->cause->location;
        $this->locationType = $this->cause->location_type;
        $this->status = $this->cause->status;

        $this->currentPicture = $this->cause->picture->url ?? null;

        $this->nominateLabel = $this->cause->status === 'nominate' ? 'Nomination' : '';



        if (
            $this->cause->user_id === auth()->user()->id ||
            ($this->company && $this->cause->user_id === optional($this->company)->user_id) ||
            auth()->user()->type ==='admin' ||
            auth()->user()->type ==='super'
        ){
            $this->ImOwner = true;
        }

        if ($this->cause->status === 'nominate') {
            $this->emit('closeModals');
        }
        $this->emit('openEditCauseModal');
        $this->emit('updateDOM');



    }

    public function updatedMatchable($value)
    {
        $this->cause->update(['matchable' => $value]);
        $this->emit('adminCausesComponent');
        $this->alert()->success(['title' => 'Cause Matchable Updated']);
        $this->emit('updateDOM');
    }

    /**
     * Livewire Hooks, when a property updated by any interaction, in this case when choose an image.
     */
    public function updatedPicture()
    {
        $this->validate([
            'picture' => 'image',
        ]);

        $file_name = 'cause_picture_' . $this->cause->id . '.' . $this->picture->getClientOriginalExtension();

        $this->cause->addMedia($this->picture->getRealPath())->usingName($file_name)->toMediaCollection('picture');

        $this->cause = $this->cause->refresh();
        $this->currentPicture = $this->cause->picture->url;

        $this->alert()->success(['title' => 'Cause Picture Updated']);
        $this->emit('AdminCauseEditModalComponent');
        $this->emit('adminCausesNominatesModalComponent');
        $this->emit('companyAdminCauseNominatesModalComponent');

        $this->emit('renderAdminCauseLoopComponent');
        $this->emit('refreshAdminCauseLoopComponent');
        $this->emit('adminCausesComponent');
        $this->emit('renderAdminCausesComponent');
    }

    public function closeModal()
    {
        if ($this->cause->status === 'nominate') {
            $this->emit('adminCausesNominatesModalComponent');
            $this->emit('companyAdminCauseNominatesModalComponent');
            $this->emit('closeModals');
            $this->emit('openNominatesModal');

        } else {

            $this->emit('renderCompanyAdminCausesComponent');
            $this->emit('refreshCompanyAdminCausesComponent');
            $this->emit('companyAdminCauseLoopComponent');
            $this->emit('refreshCompanyAdminCauseLoopComponent');

            $this->emit('renderAdminCauseLoopComponent');
            $this->emit('refreshAdminCauseLoopComponent');
            $this->emit('adminCausesComponent');
            $this->emit('renderAdminCausesComponent');

            $this->emit('closeModals');


        }
    }

}
