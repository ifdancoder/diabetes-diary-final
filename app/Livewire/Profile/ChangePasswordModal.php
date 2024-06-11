<?php

namespace App\Livewire\Profile;

use LivewireUI\Modal\ModalComponent;
use App\Models\User;
use Hash;

class ChangePasswordModal extends ModalComponent
{
    public $user;

    public $old_password;

    public $password;

    public $password_confirmation;

    public function mount($id)
    {
        $this->user = User::find($id);
    }

    public function render()
    {
        return view('livewire.profile.change-password-modal');
    }

    public function changePassword()
    {
        $validated = $this->validate([
            'old_password' => [
                'required', function ($attribute, $value, $fail) {
                    if (!Hash::check($value, $this->user->password)) {
                        $fail('Вы неверно ввели старый пароль');
                    }
                },
            ],
            'password' => [
                'required',
                'min:6',
                'confirmed',
            ]
        ], [
            'old_password.required' => 'Ввод старого пароля обязателен',
            'password.required' => 'Ввод нового пароля обязателен',
            'password.min' => 'Новый пароль должен содержать не менее 6 символов',
            'password.confirmed' => 'Пароли не совпадают',
        ]);

        $this->user->update(['password' => bcrypt($this->password)]);

        $this->closeModal();
    }
}
