<?php

namespace App\Http\Livewire\Companies\Admin\Stories;

use App\Models\Story;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyAdminStoryCreateModalComponent extends Component
{

    use WithFileUploads;
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $picture;
    public $canManageSponsors = false;
    public $title;
    public $content;
    public $company;
    public $user;
    public $employee;
    public $sponsors;

    protected $listeners = [
        'CompanyAdminStoryCreateModalComponentChange' => 'updateSponsors'
    ];

    public $rules = [
        'picture' => 'image',
        'title' => 'required',
        'content' => 'required'
    ];

    public function render()
    {
        return view('livewire.companies.admin.stories.company-admin-story-create-modal-component');
    }

    public function mount($company)
    {
        $this->canManageSponsors = true;
        $this->company = $company;
        $this->sponsors = collect([]);
    }

    public function updateSponsors($sponsors)
    {
        $this->sponsors = $sponsors;
        $this->emit('CompanyAdminStorySponsorsComponent', $sponsors);
    }

    public function save()
    {
        $data = [
            'title' => $this->title,
            'content' => $this->content,
            'status' => 'publish'
        ];

        $validator = \Validator::make($data, [
            'picture' => 'image',
            'title' => 'required',
            'content' => 'required'
        ]);


        if ($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);

            $this->alert()->error(['title' => $message]);

            $validator->validate();
        }

        if (auth()->user()->type === 'employee') {
            $data['referral_id'] = auth()->user()->id;

            $data['status'] = 'draft';
        }

        $story = Story::create($data);

        $this->company->stories()->save($story);

        if ($this->sponsors) {
            $story->sponsors()->attach($this->sponsors);
        }

        if ($this->picture) {
            $file_name = 'picture_' . $story->id . '.' . $this->picture->getClientOriginalExtension();
            $story->addMedia($this->picture->getRealPath())->usingName($file_name)->toMediaCollection('picture');
            $this->picture = null;
        }

        $this->user = auth()->user();

        if ($this->user->type === 'employee') {
            session()->flash('notification_title' ,'Your Success Story was shared successfully');
        }else{
            session()->flash('notification_title' ,'Your story was submitted');
        }


        $this->resetInputs();

        return redirect()->route('company.admin.stories');
    }

    public function resetInputs()
    {
        $this->reset(['title', 'content', 'picture']);
    }

}
