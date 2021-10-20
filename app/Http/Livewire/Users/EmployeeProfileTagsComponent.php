<?php

namespace App\Http\Livewire\Users;

use App\Services\EmployeeService;
use Livewire\Component;

class EmployeeProfileTagsComponent extends Component
{
    public $tags = '';
    public $user;
    public $enabled;
    public $selected;

    protected $listeners = ['employeeProfileTagsComponent' => '$refresh'];

    public $rules = [
        'tags' => 'required'
    ];

    public function render()
    {
        return view('livewire.users.profile.tags');
    }

    public function mount()
    {
        $this->user = EmployeeService::getUser();
        $this->tags = $this->user->tags;
    }

    public function hydrate()
    {
        $this->emit('updateDOM');
    }

    public function updatedTags($values)
    {
        $this->tags = $values;
        foreach ($this->user->tags ?? [] as $tag) {
            $this->user->detachTag($tag);
        }
        $this->user->attachTags($values);
        $this->emit('employeeProfileTagsComponent');
    }
}
