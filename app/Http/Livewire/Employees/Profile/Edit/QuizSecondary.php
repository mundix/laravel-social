<?php

namespace App\Http\Livewire\Employees\Profile\Edit;

use App\Models\QuizAnswer;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class QuizSecondary extends Component
{

    use SupportUiNotification;

    public $employee;
    public $answer;


    public function render()
    {
        return view('livewire.employees.profile.edit.quiz-secondary');
    }

    public function updatedAnswer($value)
    {
        $quizAnswer = QuizAnswer::whereSlug($value)->first();
        $this->employee->update(['quiz_secondary_id' => $quizAnswer->id]);
        $this->employee = $this->employee->refresh();

        session()->flash('notification_title' ,'Your Secondary Answer');

        return redirect()->route('users.profile.edit');
    }
}
