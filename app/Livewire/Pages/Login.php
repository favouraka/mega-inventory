<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\Attributes\Title;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    #[Title('Login')]

    public $username;
    public $password;

    protected $rules = [
        'username' => 'string|required|min:8',
        'password' => 'string|required|min:8',
    ];

    public function login()
    {
        $validated = $this->validate();
        
        $user = User::where('username', $validated['username'])->first();

        if( $user && Auth::attempt([
            'email' => $user->email,
            'password' => $validated['password']
        ]) ){
            return redirect('dashboard');
        } 
        
        $this->addError('username', 'The credentials do not match our records.');
    }

    public function render()
    {
        return view('livewire.pages.login')->layout('layouts.home');
    }
}
