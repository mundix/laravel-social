<?php

namespace App\Http\Livewire\Commons\Admin\Causes;

use App\Models\CategoryCause;
use App\Services\CauseService;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;

class CommonAdminCausesCategoryComponent extends Component
{
    use SupportUiNotification, ValidatorErrorManagementTrait;

    public $newCategories = false;
    public $categoryName;

    protected $listeners = [
        'refreshCommonAdminCausesCategoryComponent' => '$refresh',
        'renderCommonAdminCausesCategoryComponent' => 'render',
        'deleteCauseCategory' => 'delete',
        'doDeleteCategoryCause' => 'doDelete'
    ];

    public function render()
    {
        return view('livewire.commons.admin.causes.common-admin-causes-category-component', [
            'categories' => CauseService::getCategories()
        ]);
    }

    public function addNewCategory()
    {
        dd('clicked');
        $this->newCategories = true;
    }

    public function createCategory()
    {
        $validator = \Validator::make(['name' => $this->categoryName], ['name' => 'required']);
        if ($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }
        CategoryCause::create(['name' => $this->categoryName]);
        $this->newCategories = false;
        $this->reset(['categoryName']);
        $this->alert()->success(['title' => 'Your category was created successfully']);
        $this->emit('refreshCommonAdminCausesCategoryComponent');
        $this->emit('renderCommonAdminCausesCategoryComponent');

        $this->emit('adminCausesComponent');
        $this->emit('renderAdminCausesComponent');
    }

    public function delete($id)
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Are you sure you want to delete this? ',
            'confirmButtonText' => 'Yes',
            'method' => 'doDeleteCategoryCause',
            'params' => $id
        ]);
    }

    public function doDelete($categoryId = null)
    {
        if (!is_null($categoryId)) {
            $category = CategoryCause::find($categoryId);
            if ($category) {
                $category->delete();
                $this->alert()->success(['title' => 'Your category was deleted.']);
                $this->emit('refreshCommonAdminCausesCategoryComponent');
                $this->emit('adminCausesComponent');
                $this->emit('renderAdminCausesComponent');
            } else {
                $this->alert()->error(['title' => 'Something goes wrong.' . $categoryId]);
            }
        }
    }
}
