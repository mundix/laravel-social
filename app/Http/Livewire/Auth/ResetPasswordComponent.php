<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;

class ResetPasswordComponent extends Component
{
    public function render()
    {
        return view( 'livewire.auth.reset-password')
            ->layout( 'layouts.base');
    }
}
