<?php


namespace App\Services;


use App\Models\Employee;
use App\Models\QuizAnswer;

class QuizService
{
    public static function hasTaken() : bool
    {
        if (auth()->check() && auth()->user()->type == 'employee') {
            $employee = auth()->user()->employee;
            if (optional($employee)->quizPrimary || optional($employee)->quizSecondary) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return mixed
     */
    public static function getAllAnswers()
    {
        return QuizAnswer::get();
    }
}