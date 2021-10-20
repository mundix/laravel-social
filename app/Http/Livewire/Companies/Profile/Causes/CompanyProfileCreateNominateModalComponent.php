<?php

namespace App\Http\Livewire\Companies\Profile\Causes;

use App\Models\CategoryCause;
use App\Models\Cause;
use App\Models\Nominate;
use App\Services\CauseService;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyProfileCreateNominateModalComponent extends Component
{

    use WithFileUploads;
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $picture;
    public $name;
    public $phone;
    public $email;
    public $website;
    public $description;
    public $location;
    public $locationType;
    public $promoted;
    public $category;
    public $matchable;
    public $company;

    protected $rules = [
        'name' => 'required',
        'email' => 'required|email:rfc,dns',
        'website' => 'required',
        'location' => 'required',
        'locationType' => 'required',
        'phone' => 'numeric|min:9|digits_between:9,11',
    ];

    protected $messages = [
        'name.required' => 'The Name cannot be empty.',
        'location.required' => 'The Location cannot be empty.',
        'locationType.required' => 'The Location type cannot be empty.',
        'email.required' => 'The Email Address cannot be empty.',
        'email.email' => 'The Email Address format is not valid.',
        'website.required' => 'The Website Address cannot be empty.',
        'phone' => 'The phone min number is 10.',
    ];

    public function render()
    {
        return view('livewire.companies.profile.causes.company-profile-create-nominate-modal-component', [
            'categories' => CauseService::getCategories()
        ]);
    }

    public function mount()
    {
        $this->email = $this->company->user->email;
        $this->category = CategoryCause::first()->id;
        $this->locationType = 'local';
    }

    public function save()
    {
        $data = [
            'name' => $this->name,
            'email' => auth()->user()->email,
            'is_promoted' => $this->promoted ? true : false,
            'is_nominated' => true,
            'referral_id' => auth()->user()->id,
            'website' => $this->website,
            'category' => $this->category,
            'location' => $this->location,
            'location_type' => $this->locationType,
            'category_id' => $this->category,
            'phone' => $this->phone,
            'description' => $this->description,
            'user_id' => $this->company->user->id,
            'status' => 'nominate',
            'matchable' => $this->matchable ? true : false
        ];

        $validator = \Validator::make([
            'name' => $this->name,
            'location' => $this->location,
            'locationType' => $this->locationType,
            'category' => $this->category,
            'email' => $this->email,
            'website' => $this->website,
        ], $this->rules);

        if ($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);

            $this->alert()->error(['title' => $message]);

            $validator->validate();
        }

        $cause = Cause::create($data);

        if ($this->picture) {
            $file_name = 'picture_' . $cause->id . '.' . $this->picture->getClientOriginalExtension();

            $cause->addMedia($this->picture->getRealPath())->usingName($file_name)->toMediaCollection('picture');

            $this->picture = null;
        }

        $this->alert()->success(['title' => 'This cause has been created']);

        $this->category = CategoryCause::first()->id;

        $this->resetInputs();
        $this->emit('adminCausesComponent');
        $this->emit('refreshCompanyAdminCausesComponent');
        $this->emit('updateDOM');
        $this->emit('closeModals');

        if(auth()->user()->type === 'company') {
            session()->flash('notification_title' ,'Your cause has been created.');
            return redirect()->route('company.profile');
        }
    }

    public function resetInputs()
    {
        $this->reset([
            'name',
            'phone',
            'website',
            'location',
            'promoted',
            'description',
            'matchable',
        ]);
        $this->locationType = 'local';
    }

    public function updatedLocationType($value)
    {
        $this->locationType = $value;
    }
}
