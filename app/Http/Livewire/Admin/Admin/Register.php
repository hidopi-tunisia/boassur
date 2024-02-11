<?php

namespace App\Http\Livewire\Admin\Admin;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public $name = '';

    public $email = '';

    public $password = '';

    public $passwordConfirmation = '';

    public function register()
    {
        $data = $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:8|same:passwordConfirmation',
        ]);

        $user = Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        auth('admin')->login($user);

        return redirect('/');
    }

    public function render()
    {
        return view('admin.admin.register')
            ->layout('admin.layouts.app');
    }
}
