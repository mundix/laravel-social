<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Stories;

use App\Models\Story;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyProfileEditNewStoryComponent extends Component
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
        'CompanyProfileEditNewStoryComponentChange' => 'updateSponsors'
    ];

    public $rules = [
        'picture' => 'image',
        'title' => 'required',
        'content' => 'required'
    ];

    public function mount($company)
    {
        $this->employee = null;
        if (auth()->check()) {
            $this->user = auth()->user();

            if ($this->user->type === 'employee') {

                $this->employee = $this->user->employee;

            }else {

                $this->canManageSponsors = true;
            }
        }
        $this->company = $company;
        $this->sponsors = collect([]);
    }

    public function render()
    {
        return view('livewire.companies.profile.edit.stories.company-profile-edit-new-story-component');
    }

    public function updateSponsors($sponsors)
    {
        $this->sponsors = $sponsors;
        $this->emit('CompanyProfileEditStoriesSponsorsComponent', $sponsors);
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

        if (auth()->user()->type === 'employee') {
            return redirect()->route('company.slug', $this->company->slug);
        } else {
            return redirect()->route('company.admin.index');
        }
    }

    public function resetInputs()
    {
        $this->reset(['title', 'content', 'picture']);
    }
}
