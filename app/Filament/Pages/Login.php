<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    protected string $view = 'filament.pages.login';

    public function getHeading(): string|Htmlable|null
    {
        return null;
    }

    public function getSubheading(): string|Htmlable|null
    {
        return null;
    }

    public function authenticateAction(): Action
{
    return Action::make('authenticate')
        ->label('Sign in')
        ->submit('authenticate')
        ->extraAttributes([
            'class' => 'w-full',
        ])
        ->button();
}

    public function registerAction(): Action
    {
        return Action::make('register')
            ->link()
            ->label('Sign up for new account')
            ->url(route('register.gym'));
    }
}