<?php

namespace App\Livewire\Profile;

use LivewireUI\Modal\ModalComponent;
use App\Models\User;
use Hash;

class ChangeEmailModal extends ModalComponent
{
    public $user;

    public $password;

    public $email;

    public function mount($id)
    {
        $this->user = User::find($id);
    }

    public function changePassword()
    {
        $validated = $this->validate([
            'password' => [
                'required', function ($attribute, $value, $fail) {
                    if (!Hash::check($value, $this->user->password)) {
                        $fail('Вы неверно ввели старый пароль');
                    }
                },
            ],
            'email' => [
                'required',
                'min:6'
            ]
        ], [
            'password.required' => 'Ввод пароля обязателен',
            'email.required' => 'Ввод электронной почты обязателен',
            'email.email' => 'Введите действительный адрес электронной почты',
        ]);

        $this->user->update([
            'email' => $this->email,
        ]);

        $this->dispatch('reRenderBasic');

        $this->closeModal();
    }
    public function render()
    {
        return view('livewire.profile.change-email-modal');
    }
}
