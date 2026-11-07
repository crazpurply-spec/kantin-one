<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateAdminUser extends Command
{
    protected $signature = 'app:create-admin';

    protected $description = 'Create the first administrator account';

    public function handle(): int
    {
        if (User::where('is_protected', true)->exists()) {
            $this->error('Akun Super Admin utama sudah terdaftar.');

            return self::FAILURE;
        }

        $name = $this->ask('Nama administrator');
        $email = $this->ask('Email administrator');
        $username = $this->ask('Username administrator');

        // Validasi email
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Format email tidak valid.');

            return self::FAILURE;
        }

        if (User::where('email', $email)->exists()) {
            $this->error('Email tersebut sudah terdaftar.');

            return self::FAILURE;
        }

        // Validasi username
        if (
            empty($username) ||
            Str::lower($username) !== $username ||
            preg_match('/\s/u', $username)
        ) {
            $this->error('Username wajib diisi, tidak boleh menggunakan huruf besar atau spasi.');

            return self::FAILURE;
        }

        if (User::where('username', $username)->exists()) {
            $this->error('Username tersebut sudah digunakan.');

            return self::FAILURE;
        }

        // Validasi password
        $password = $this->secret('Password administrator');
        $confirmation = $this->secret('Konfirmasi password');

        if (empty($password) || $password !== $confirmation) {
            $this->error('Password kosong atau tidak cocok.');

            return self::FAILURE;
        }

        // Membuat akun Super Admin utama
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'username' => $username,
            'password' => Hash::make($password),
            'role' => 'super_admin',
            'is_active' => true,
            'is_protected' => true,
        ]);

        $this->newLine();
        $this->info('Akun administrator berhasil dibuat!');
        $this->line('User ID : '.$user->id);
        $this->line('Email   : '.$user->email);
        $this->line('Username: '.$user->username);
        $this->line('Role    : '.$user->role);

        return self::SUCCESS;
    }
}
