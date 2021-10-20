<?php

namespace App\Http\Livewire\Commons\Involvements;

use App\Models\Involvement;
use App\Models\User;
use Livewire\Component;

class CommonInvolvementDashboardComponent extends Component
{
    public $user;
    public $hours = 0;
    public $donations = 0;
    public $matches = 0;
    public $contributions = 0;

    protected $listeners = ['refreshCommonInvolvementDashboardComponent' => 'setValues'];

    public function render()
    {
        if($this->user->company) {
            return view('livewire.commons.involvements.common-company-involvement-dashboard-component');
        } else {
            return view('livewire.commons.involvements.common-employee-involvement-dashboard-component');
        }
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
        $this->contributions = Involvement::sum('donations');
    }
}
