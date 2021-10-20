<?php

namespace App\Http\Livewire\Commons\Admin\Causes;

use App\Models\CategoryCause;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;

class CommonAdminCausesCategoryLoopComponent extends Component
{
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $category;
    public $name;
    public $deleted = false;

    public $rules = ['name' => 'required'];

    public function render()
    {

        return view('livewire.commons.admin.causes.common-admin-causes-category-loop-component');
    }

    public function mount()
    {
        $this->name = $this->category->name;
    }

    public function save()
    {
        $this->category->update(['name' => $this->name]);
        $this->alert()->success(['title' => 'Category edited successfully']);

        $this->emit('adminCausesComponent');
        $this->emit('renderAdminCausesComponent');
        $this->emit('refreshCommonAdminCausesCategoryComponent');
        $this->emit('renderCommonAdminCausesCategoryComponent');

        $this->emit('updateDOM');
    }

}
