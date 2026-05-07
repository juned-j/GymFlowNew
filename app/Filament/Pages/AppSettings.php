<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use App\Models\Currency;
use Filament\Forms\Components\Select;

class AppSettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog;
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Settings';
    public function getView(): string
    {
        return 'filament.pages.app-settings';
    }
    public ?array $data = [];
    public $tenant;
    public function mount(): void
    {
        $tenant = app()->bound('tenant') ? app('tenant') : null;
        if (!$tenant) {
            abort(404, 'Tenant not found');
        }
        $this->tenant = $tenant;
        $settings = $tenant->app_settings;
        if (is_string($settings)) {
            $settings = json_decode($settings, true) ?? [];
        }
        $this->data = $settings ?: [
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
        $this->data['tenant'] = [
            'name' => $tenant->name,
            'logo_url' => $tenant->logo_url,
            'email' => $tenant->email,
            'phone' => $tenant->phone,
            'address' => $tenant->address,
            'city' => $tenant->city,
            'country' => $tenant->country,
            'timezone' => $tenant->timezone,
            'currency' => $tenant->currency,
            'currency_symbol' => $tenant->currency_symbol,
            'status' => $tenant->status,
            'is_active' => $tenant->is_active,
            'trial_ends_at' => $tenant->trial_ends_at,
        ];
        $this->form->fill($this->data);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->columns(2)
            ->schema([

                /*
                |--------------------------------------------------------------------------
                | Tenant Information (Read Only)
                |--------------------------------------------------------------------------
                */
                Section::make('Tenant Information')
                    ->description('Basic tenant details')
                    ->columns(2)
                    ->schema([

                        Forms\Components\TextInput::make('tenant.name')
                            ->label('Name')
                            ->disabled(),

                        Forms\Components\TextInput::make('tenant.logo_url')
                            ->label('Logo URL')
                            ->disabled(),

                        Forms\Components\TextInput::make('tenant.email')
                            ->label('Email')
                            ->disabled(),

                        Forms\Components\TextInput::make('tenant.phone')
                            ->label('Phone')
                            ->disabled(),

                        Forms\Components\TextInput::make('tenant.address')
                            ->label('Address')
                            ->disabled(),

                        Forms\Components\TextInput::make('tenant.city')
                            ->label('City')
                            ->disabled(),

                        Forms\Components\TextInput::make('tenant.country')
                            ->label('Country')
                            ->disabled(),

                        Forms\Components\TextInput::make('tenant.timezone')
                            ->label('Timezone')
                            ->disabled(),

                        Forms\Components\TextInput::make('tenant.currency')
                            ->label('Currency')
                            ->disabled(),

                        Forms\Components\TextInput::make('tenant.currency_symbol')
                            ->label('Currency Symbol')
                            ->disabled(),

                        Forms\Components\TextInput::make('tenant.status')
                            ->label('Status')
                            ->disabled(),

                        Forms\Components\Toggle::make('tenant.is_active')
                            ->label('Is Active')
                            ->disabled(),

                        Forms\Components\DateTimePicker::make('tenant.trial_ends_at')
                            ->label('Trial Ends At')
                            ->disabled(),
                    ])
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | Branding
                |--------------------------------------------------------------------------
                */

                Section::make('Branding')
                    ->columns(2)
                    ->schema([
                        Forms\Components\ColorPicker::make('branding.primary_color'),

                        Forms\Components\ColorPicker::make('branding.secondary_color'),

                        Forms\Components\ColorPicker::make('branding.accent_color'),

                        Forms\Components\TextInput::make('branding.logo_url'),

                        Forms\Components\TextInput::make('branding.splash_screen_url'),
                    ]),

                /*
                |--------------------------------------------------------------------------
                | App
                |--------------------------------------------------------------------------
                */

                Section::make('App')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('app.app_name')
                            ->required(),

                        Forms\Components\TextInput::make('app.bundle_id'),

                        Forms\Components\TextInput::make('app.version'),

                        Forms\Components\Toggle::make('app.force_update'),

                        Forms\Components\Toggle::make('app.maintenance_mode'),
                    ]),

                /*
                |--------------------------------------------------------------------------
                | Features
                |--------------------------------------------------------------------------
                */

                Section::make('Features')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Toggle::make('features.enable_chat'),

                        Forms\Components\Toggle::make('features.enable_notifications'),

                        Forms\Components\Toggle::make('features.enable_payments'),

                        Forms\Components\Toggle::make('features.enable_workouts'),

                        Forms\Components\Toggle::make('features.enable_diet_plans'),

                        Forms\Components\Toggle::make('features.enable_referrals'),
                    ]),

                /*
                |--------------------------------------------------------------------------
                | Auth
                |--------------------------------------------------------------------------
                */

                Section::make('Auth')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Toggle::make('auth.allow_social_login'),

                        Forms\Components\Toggle::make('auth.otp_login'),

                        Forms\Components\Toggle::make('auth.email_login'),
                    ]),

                /*
                |--------------------------------------------------------------------------
                | Payments
                |--------------------------------------------------------------------------
                */

                Section::make('Payments')
                    ->columns(2)
                    ->schema([

                        Forms\Components\TextInput::make('payments.provider')
                            ->label('Payment Provider'),

                        Select::make('payments.currency')
                            ->label('Currency')
                            ->options(
                                Currency::query()
                                    ->pluck('currency_name', 'currency_name')
                                    ->toArray()
                            )
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('payments.stripe_publishable_key')
                            ->label('Stripe Publishable Key')
                            ->placeholder('pk_test_...')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('payments.stripe_secret_key')
                            ->label('Stripe Secret Key')
                            ->placeholder('sk_test_...')
                            ->password()
                            ->revealable()
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('payments.allow_subscriptions'),

                    ]),

                /*
                |--------------------------------------------------------------------------
                | Notifications
                |--------------------------------------------------------------------------
                */

                Section::make('Notifications')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Toggle::make('notifications.push_enabled'),

                        Forms\Components\Toggle::make('notifications.email_enabled'),

                        Forms\Components\Toggle::make('notifications.sms_enabled'),
                    ]),

                /*
                |--------------------------------------------------------------------------
                | Content
                |--------------------------------------------------------------------------
                */

                Section::make('Content')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('content.terms_url'),

                        Forms\Components\TextInput::make('content.privacy_policy_url'),

                        Forms\Components\TextInput::make('content.support_email'),
                    ]),

                /*
                |--------------------------------------------------------------------------
                | UI
                |--------------------------------------------------------------------------
                */

                Section::make('UI')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Toggle::make('ui.dark_mode_enabled'),

                        Forms\Components\TextInput::make('ui.default_language'),

                        Forms\Components\TagsInput::make('ui.supported_languages')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function save(): void
    {
        if (!$this->tenant) {
            return;
        }

        $data = $this->form->getState();

        $this->tenant->update([
            'app_settings' => $data
        ]);

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}
