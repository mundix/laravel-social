<?php

namespace App\Http\Livewire\Employees\Profile\Edit;

use App\Services\EmployeeService;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class EmployeeProfileEditTagsComponent extends Component
{

    use SupportUiNotification;

    public $tags = '';
    public $user;
    public $enabled;
    public $selected;

    protected $listeners = ['EmployeeProfileEditTagsComponent' => '$refresh'];

    public $rules = [
        'tags' => 'required'
    ];

    public function render()
    {
        return view('livewire.employees.profile.edit.employee-profile-edit-tags-component');
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
        $this->alert()->success(['title' => 'Employee Tags have been updated']);
        $this->emit('EmployeeProfileEditTagsComponent');
    }
}
