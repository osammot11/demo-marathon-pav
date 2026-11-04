<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateDemoAdmin extends Command
{
    protected $signature = 'demo:admin {email} {--name=Demo Admin}';
    protected $description = 'Create or update an administrator for the shared demo panel';

    public function handle(): int
    {
        $email = (string) $this->argument('email');
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('A valid email address is required.');
            return self::FAILURE;
        }
        $password = $this->secret('Password (at least 12 characters)');
        $confirm = $this->secret('Confirm password');
        if (! is_string($password) || strlen($password) < 12 || $password !== $confirm) {
            $this->error('Passwords must match and contain at least 12 characters.');
            return self::FAILURE;
        }

        User::updateOrCreate(['email' => $email], [
            'name' => (string) $this->option('name'), 'password' => $password,
            'is_demo_admin' => true,
        ]);
        $this->info('Demo administrator saved.');
        return self::SUCCESS;
    }
}
