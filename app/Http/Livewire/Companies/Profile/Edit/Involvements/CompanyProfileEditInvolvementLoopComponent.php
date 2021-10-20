<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Involvements;

use App\Models\Involvement;
use App\Traits\SupportUiNotification;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyProfileEditInvolvementLoopComponent extends Component
{
    use WithFileUploads;
    use SupportUiNotification;

    public $involvement;
    protected $listeners = [
        'CompanyProfileEditInvolvementLoopComponent' => '$refresh',
        'deleteInvolvement' => 'doDelete'
    ];

    public function render()
    {
        return view('livewire.companies.profile.edit.involvements.company-profile-edit-involvement-loop-component');
    }

    public function delete()
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Are you sure you want to delete this?' ,
            'confirmButtonText' => 'Yes',
            'method' => 'deleteInvolvement',
            'params' => $this->involvement->id
        ]);
    }

    public function doDelete($id)
    {
        $obj = Involvement::find($id);
        $obj->delete();
        session()->flash('notification_title' ,'Involvement was deleted');
        return redirect()->route('company.admin.index');
    }

}
