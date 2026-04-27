<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;

class AppSettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog;
    protected static ?string $navigationLabel = 'App Settings';

    public function getView(): string
    {
        return 'filament.pages.app-settings';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public ?array $data = [];

    public $tenant; // ✅ STORE TENANT HERE

    public function mount(): void
    {
        $tenant = app()->bound('tenant') ? app('tenant') : null;

        if (!$tenant) {
            \Log::error('❌ Tenant not found in mount');
            abort(404, 'Tenant not found');
        }

        $this->tenant = $tenant; // ✅ IMPORTANT FIX

        \Log::info('✅ Tenant loaded in mount', [
            'tenant_id' => $tenant->id
        ]);

        $this->data = $tenant->app_settings ?? [
            'branding' => [
                'primary_color' => '#FF5733',
                'secondary_color' => '#222222',
                'accent_color' => '#FFC107',
            ],
            'app' => [
                'app_name' => 'GymFlow',
                'version' => '1.0.0',
            ],
            'ui' => [
                'default_language' => 'en',
                'supported_languages' => ['en'],
            ],
        ];

        $this->form->fill($this->data);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->schema([

                Section::make('Branding')
                    ->schema([
                        Forms\Components\ColorPicker::make('branding.primary_color'),
                        Forms\Components\ColorPicker::make('branding.secondary_color'),
                        Forms\Components\ColorPicker::make('branding.accent_color'),
                        Forms\Components\FileUpload::make('branding.logo_url')->image(),
                        Forms\Components\FileUpload::make('branding.splash_screen_url')->image(),
                    ]),

                Section::make('App')
                    ->schema([
                        Forms\Components\TextInput::make('app.app_name')->required(),
                        Forms\Components\TextInput::make('app.bundle_id'),
                        Forms\Components\TextInput::make('app.version'),
                        Forms\Components\Toggle::make('app.force_update'),
                        Forms\Components\Toggle::make('app.maintenance_mode'),
                    ]),

                Section::make('Features')
                    ->schema([
                        Forms\Components\Toggle::make('features.enable_chat'),
                        Forms\Components\Toggle::make('features.enable_notifications'),
                        Forms\Components\Toggle::make('features.enable_payments'),
                        Forms\Components\Toggle::make('features.enable_workouts'),
                        Forms\Components\Toggle::make('features.enable_diet_plans'),
                        Forms\Components\Toggle::make('features.enable_referrals'),
                    ]),

                Section::make('Auth')
                    ->schema([
                        Forms\Components\Toggle::make('auth.allow_social_login'),
                        Forms\Components\Toggle::make('auth.otp_login'),
                        Forms\Components\Toggle::make('auth.email_login'),
                    ]),

                Section::make('Payments')
                    ->schema([
                        Forms\Components\TextInput::make('payments.provider'),
                        Forms\Components\TextInput::make('payments.currency'),
                        Forms\Components\Toggle::make('payments.allow_subscriptions'),
                    ]),

                Section::make('Notifications')
                    ->schema([
                        Forms\Components\Toggle::make('notifications.push_enabled'),
                        Forms\Components\Toggle::make('notifications.email_enabled'),
                        Forms\Components\Toggle::make('notifications.sms_enabled'),
                    ]),

                Section::make('Content')
                    ->schema([
                        Forms\Components\TextInput::make('content.terms_url'),
                        Forms\Components\TextInput::make('content.privacy_policy_url'),
                        Forms\Components\TextInput::make('content.support_email'),
                    ]),

                Section::make('UI')
                    ->schema([
                        Forms\Components\Toggle::make('ui.dark_mode_enabled'),
                        Forms\Components\TextInput::make('ui.default_language'),
                        Forms\Components\TagsInput::make('ui.supported_languages'),
                    ]),
            ]);
    }

    public function save(): void
    {
        if (!$this->tenant) {
            \Log::error('❌ Tenant missing in component');
            return;
        }

        $data = $this->form->getState(); // ✅ IMPORTANT FIX

        \Log::info('💾 Saving app settings', [
            'tenant_id' => $this->tenant->id,
        ]);

        $this->tenant->update([
            'app_settings' => $data
        ]);

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}