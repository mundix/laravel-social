<?php

namespace App\Http\Livewire\Employees\Profile\Edit;

use App\Models\QuizAnswer;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class QuizPrimary extends Component
{
    use SupportUiNotification;

    public $employee;
    public $answer;

    public function render()
    {
        return view('livewire.employees.profile.edit.quiz-primary');
    }

    public function updatedAnswer($value)
    {
        $quizAnswer = QuizAnswer::whereSlug($value)->first();
        $this->employee->update(['quiz_primary_id' => $quizAnswer->id]);
        $this->employee = $this->employee->refresh();

        session()->flash('notification_title' ,'Your Primary Answer');

        return redirect()->route('users.profile.edit');

    }
}
