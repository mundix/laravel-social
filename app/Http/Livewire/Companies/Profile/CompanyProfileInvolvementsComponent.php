<?php

namespace App\Http\Livewire\Companies\Profile;

use App\Models\Involvement;
use App\Models\User;
use Livewire\Component;

class CompanyProfileInvolvementsComponent extends Component
{

    public $user;
    public $hours = 0;
    public $donations = 0;
    public $matches = 0;
    public $contributions = 0;

    public function render()
    {
        return view('livewire.companies.profile.company-profile-involvements-component');
    }

    public function mount()
    {
        $this->setValues();
    }

    private function getUserType(User $user)
    {
        if($user->employee){
            return $this->user->employee;
        }elseif($user->company){
            return $user->company;
        }
    }

    public function setValues()
    {
        $this->hours = $this->getUserType($this->user)->involvements->sum('hours');
        $this->donations = $this->getUserType($this->user)->involvements->sum('donations');
        $this->matches = $this->getUserType($this->user)->involvements->sum('matches');
        $this->contributions = $this->donations + $this->matches;
    }
}
