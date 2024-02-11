<?php

namespace App\Http\Livewire\Admin\Admin;

use App\Models\Admin as AdminModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
    public AdminModel $admin;

    public $adminId = '';

    public $name = '';

    public $email = '';

    public $password = '';

    public $passwordConfirmation = '';

    public $title = '';

    public function mount()
    {
        $this->name = $this->admin->name;
        $this->email = $this->admin->email;
        $this->adminId = $this->admin->id;
        $this->title = 'Édition de '.$this->admin->name;
    }

    public function update()
    {
        $data = $this->validate([
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('admins')->ignore($this->adminId)],
            'password' => 'nullable|min:8|same:passwordConfirmation',
        ]);

        $this->admin->name = $data['name'];
        $this->admin->email = $data['email'];

        if ($data['password']) {
            $this->admin->password = Hash::make($data['password']);
        }

        $this->admin->save();
        $this->emit('notify-saved');
    }

    public function render()
    {
        return view('admin.admin.edit')
            ->layout('admin.layouts.app');
    }
}
