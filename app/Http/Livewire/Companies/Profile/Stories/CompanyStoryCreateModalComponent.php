<?php

namespace App\Http\Livewire\Companies\Profile\Stories;

use App\Models\Story;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyStoryCreateModalComponent extends Component
{
    use WithFileUploads;

    public $picture = null;
    public $title;
    public $content;
    public $company;


    public $rules = [
         'picture' => 'image',
         'title' => 'required',
         'content' => 'required'
    ];

    public function render()
    {
        return view('livewire.companies.profile.stories.company-story-create-modal-component');
    }

    public function save()
    {
        $this->validate();
        $data = [
            'title' => $this->title,
            'content' => $this->content,
        ];
        $story = Story::create($data);
        $this->company->stories()->save($story);

        if($this->picture){
            $file_name  = 'picture_' . $story->id . '.' .$this->picture->getClientOriginalExtension();
            $story->addMedia($this->picture->getRealPath())->usingName($file_name)->toMediaCollection('picture');
        }

        $this->resetInputs();
        $this->emit('closeModals');
        $this->emit('openSuccessStory');
        $this->emit('companyStoriesComponent');
        $this->emit('updateDOM');
    }

    public function resetInputs()
    {
        $this->title = '';
        $this->content = '';
        $this->picture = null;
    }
}
