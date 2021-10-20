<?php

namespace App\Http\Livewire\Employees\Profile;

use App\Models\Cause;
use Livewire\Component;

class EmployeeProfileFavoriteCausesComponent extends Component
{
    public $causes;
    public $company;
    public $employee;

    public function render()
    {
        return view('livewire.employees.profile.employee-profile-favorite-causes-component', [
            'causes' => $this->causes
        ]);
    }

    public function mount($employee = null, $company = null)
    {
        $this->causes = collect([]);
        if (auth()->check() && $employee->user_id == auth()->user()->id) {
            $this->causes = auth()->user()->getFavoriteItems(Cause::class)->get();
        }else {
            if (!is_null($company)) {
                $this->causes = $company->user->getFavoriteItems(Cause::class)->get();
            } elseif (!is_null($employee)) {
                $this->causes = $employee->user->getFavoriteItems(Cause::class)->get();
            }
        }
    }

}
