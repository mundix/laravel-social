<?php

namespace App\Http\Livewire\Admin\Causes;

use App\Models\CategoryCause;
use App\Models\Cause;
use App\Services\CauseService;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminCauseCreateModalComponent extends Component
{
    use WithFileUploads;
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $picture;
    public $name;
    public $company;
    public $phone;
    public $email;
    public $website;
    public $description;
    public $location;
    public $locationType;
    public $category;
    public $matchable;

    protected $rules = [
        'name' => 'required',
        'email' => 'required|email:rfc,dns',
        'website' => 'required|url',
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
        return view('livewire.admin.causes.admin-cause-create-modal-component', [
            'categories' => CauseService::getCategories()
        ]);
    }

    public function mount()
    {
        $this->category = CategoryCause::first()->id;
        $this->locationType = 'local';

    }

    public function save()
    {
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'website' => $this->website,
            'category' => $this->category,
            'location' => $this->location,
            'location_type' => $this->locationType,
            'category_id' => $this->category,
            'phone' => $this->phone,
            'description' => $this->description,
            'user_id' => ($this->company) ? $this->company->user->id : auth()->user()->id,
            'status' => 'approved',
            'matchable' => $this->matchable ? true : false
        ];

        $validator = \Validator::make([
            'name' => $this->name,
            'location' => $this->location,
            'locationType' => $this->locationType,
            'category' => $this->category,
            'email' => $this->email,
            'website' => $this->website,
            'phone' => $this->phone,
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

        $this->alert()->success(['title' => 'This Cause has been created.']);

        $this->category = CategoryCause::first()->id;

        $this->resetInputs();
        $this->emit('adminCausesComponent');
        $this->emit('refreshCompanyAdminCausesComponent');
        $this->emit('updateDOM');
        $this->emit('closeModals');

        if(auth()->user()->type === 'company' || auth()->user()->type === 'company-admin') {
            session()->flash('notification_title' ,'This Cause has been created.');
            return redirect()->route('company.admin.causes');
        }
    }

    public function resetInputs()
    {
        $this->reset([
            'name',
            'phone',
            'website',
            'description',
            'matchable',
        ]);
    }

    public function updatedLocationType($value)
    {
        $this->locationType = $value;
    }

}
