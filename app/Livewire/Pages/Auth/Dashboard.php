<?php

namespace App\Livewire\Pages\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;

use Illuminate\Support\Facades\Auth;

#[Title('Home')]
class Dashboard extends Component
{
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.pages.auth.dashboard')->layout('layouts.dashboard');
    }
}
