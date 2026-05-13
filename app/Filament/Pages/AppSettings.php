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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;

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
        $this->data['branding'] = $this->data['branding'] ?? [];
        $logoPath = $this->data['branding']['logo_url'] ?? $this->tenant->logo_url;
        if ($logoPath) {
            $this->data['branding']['logo_url'] = $logoPath;
            $this->data['branding']['logo_full_url'] = asset('storage/' . $logoPath);
        } else {

            $this->data['branding']['logo_url'] = null;
            $this->data['branding']['logo_full_url'] = null;
        }
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
            'is_active' => $tenant->is_active,
            'trial_ends_at' => $tenant->trial_ends_at,

        ];

        // Payments data
        $this->data['payments'] = $this->data['payments'] ?? [];
        $this->data['payments']['currency'] = $tenant->currency;
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
                            ->label('Name'),
                        Forms\Components\TextInput::make('tenant.email')
                            ->label('Email'),
                        Forms\Components\TextInput::make('tenant.phone')
                            ->label('Phone'),
                        Forms\Components\TextInput::make('tenant.address')
                            ->label('Address'),
                        Forms\Components\TextInput::make('tenant.city')
                            ->label('City'),
                        Forms\Components\TextInput::make('tenant.country')
                            ->label('Country'),
                        Forms\Components\TextInput::make('tenant.timezone')
                            ->label('Timezone'),

                        Select::make('tenant.currency')
                            ->label('Currency')
                            ->options(
                                \App\Models\Currency::query()
                                    ->get()
                                    ->mapWithKeys(fn($currency) => [
                                        $currency->id => "{$currency->currency_name} ({$currency->currency_symbol})"
                                    ])
                                    ->toArray()
                            )
                            ->searchable()
                            ->required(),
                        //dd
                        Forms\Components\Toggle::make('tenant.is_active')
                            ->label('Is Active'),
                        Forms\Components\DateTimePicker::make('tenant.trial_ends_at')
                            ->label('Trial Ends At'),
                    ])
                    ->columnSpanFull(),
                Section::make('Branding')
                    ->columns(2)
                    ->schema([
                        Forms\Components\ColorPicker::make('branding.primary_color'),
                        Forms\Components\ColorPicker::make('branding.secondary_color'),
                        Forms\Components\ColorPicker::make('branding.accent_color'),
                        FileUpload::make('branding.logo_url')
                            ->label('Logo')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('tenant-branding/logos')
                            ->visibility('public')
                            ->preserveFilenames()
                            ->maxSize(2048)
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set(
                                    'branding.logo_full_url',
                                    $state ? asset('storage/' . $state) : null
                                );
                            }),
                        TextInput::make('branding.logo_full_url')
                            ->label('Logo URL')
                            ->readOnly()
                            ->dehydrated(false),
                        FileUpload::make('branding.splash_screen_url')
                            ->label('Splash Screen')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('tenant-branding/splash')
                            ->visibility('public')
                            ->preserveFilenames()
                            ->maxSize(4096)
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set(
                                    'branding.splash_screen_full_url',
                                    $state ? asset('storage/' . $state) : null
                                );
                            }),
                        TextInput::make('branding.splash_screen_full_url')
                            ->label('Splash Screen URL')
                            ->readOnly()
                            ->dehydrated(false),
                    ])->columnSpanFull(),
                Section::make('App')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('app.app_name')
                            ->required(),
                        Forms\Components\TextInput::make('app.bundle_id'),
                        Forms\Components\TextInput::make('app.version'),
                        Forms\Components\Toggle::make('app.force_update'),
                        Forms\Components\Toggle::make('app.maintenance_mode'),
                    ])->columnSpanFull(),
                Section::make('Features')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Toggle::make('features.enable_chat'),
                        Forms\Components\Toggle::make('features.enable_notifications'),
                        Forms\Components\Toggle::make('features.enable_payments'),
                        Forms\Components\Toggle::make('features.enable_workouts'),
                        Forms\Components\Toggle::make('features.enable_diet_plans'),
                        Forms\Components\Toggle::make('features.enable_referrals'),
                    ])->columnSpanFull(),
                Section::make('Authentication')
                    ->columns(2)
                    ->schema([
                        /*
        |--------------------------------------------------------------------------
        | Basic Auth
        |--------------------------------------------------------------------------
        */
                        Forms\Components\Toggle::make('auth.allow_social_login')
                            ->live(),
                        Forms\Components\Toggle::make('auth.otp_login'),
                        Forms\Components\Toggle::make('auth.email_login'),
                        /*
        |--------------------------------------------------------------------------
        | GOOGLE LOGIN
        |--------------------------------------------------------------------------
        */

                        Section::make('Google Login')
                            ->visible(fn($get) => $get('auth.allow_social_login'))
                            ->columns(2)
                            ->schema([
                                Forms\Components\Toggle::make('auth.google.enabled')
                                    ->label('Enable Google Login'),
                                Forms\Components\TextInput::make('auth.google.web_client_id')
                                    ->label('Web Client ID'),
                                Forms\Components\TextInput::make('auth.google.android_client_id')
                                    ->label('Android Client ID'),
                                Forms\Components\TextInput::make('auth.google.ios_client_id')
                                    ->label('iOS Client ID'),
                                Forms\Components\Textarea::make('auth.google.android_sha1')
                                    ->label('Android SHA1'),

                            ])->columnSpanFull(),

                        /*
        |--------------------------------------------------------------------------
        | FACEBOOK LOGIN
        |--------------------------------------------------------------------------
        */
                        Section::make('Facebook Login')
                            ->visible(fn($get) => $get('auth.allow_social_login'))
                            ->columns(2)
                            ->schema([
                                Forms\Components\Toggle::make('auth.facebook.enabled')
                                    ->label('Enable Facebook Login'),
                                Forms\Components\TextInput::make('auth.facebook.app_id')
                                    ->label('Facebook App ID'),
                                Forms\Components\TextInput::make('auth.facebook.client_token')
                                    ->label('Client Token'),
                                Forms\Components\TextInput::make('auth.facebook.app_secret')
                                    ->password()
                                    ->revealable()
                                    ->label('App Secret'),
                            ])->columnSpanFull(),

                        /*
        |--------------------------------------------------------------------------
        | APPLE LOGIN
        |--------------------------------------------------------------------------
        */
                        Section::make('Apple Login')
                            ->visible(fn($get) => $get('auth.allow_social_login'))
                            ->columns(2)
                            ->schema([
                                Forms\Components\Toggle::make('auth.apple.enabled')
                                    ->label('Enable Apple Login'),
                                Forms\Components\TextInput::make('auth.apple.client_id')
                                    ->label('Client ID'),
                                Forms\Components\TextInput::make('auth.apple.team_id')
                                    ->label('Team ID'),
                                Forms\Components\TextInput::make('auth.apple.key_id')
                                    ->label('Key ID'),
                                Forms\Components\Textarea::make('auth.apple.private_key')
                                    ->label('Private Key')
                                    ->rows(8),
                            ])->columnSpanFull(),
                    ])->columnSpanFull(),
                Section::make('Payments')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('payments.provider')
                            ->label('Payment Provider'),
                        Select::make('payments.currency')
                            ->label('Currency')
                            ->options(
                                \App\Models\Currency::query()
                                    ->get()
                                    ->mapWithKeys(fn($currency) => [
                                        $currency->id => "{$currency->currency_name} ({$currency->currency_symbol})"
                                    ])
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
                        Forms\Components\TextInput::make('payments.stripe_webhook_secret')
                            ->label('Stripe Webhook Secret')
                            ->placeholder('whsec_...')
                            ->password()
                            ->revealable()
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('payments.allow_subscriptions'),
                    ])
                    ->columnSpanFull(),
                Section::make('Notifications')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Toggle::make('notifications.push_enabled'),
                        Forms\Components\Toggle::make('notifications.email_enabled'),
                        Forms\Components\Toggle::make('notifications.sms_enabled'),
                    ])->columnSpanFull(),
                Section::make('Content')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('content.terms_url'),
                        Forms\Components\TextInput::make('content.privacy_policy_url'),
                        Forms\Components\TextInput::make('content.support_email'),
                    ])->columnSpanFull(),

            ]);
    }
    public function save(): void
    {
        if (!$this->tenant) {
            return;
        }
        $data = $this->form->getState();
        $this->tenant->update([
            'name'          => $data['tenant']['name'] ?? null,
            'logo_url'      => $data['branding']['logo_url'] ?? null,
            'email'         => $data['tenant']['email'] ?? null,
            'phone'         => $data['tenant']['phone'] ?? null,
            'address'       => $data['tenant']['address'] ?? null,
            'city'          => $data['tenant']['city'] ?? null,
            'country'       => $data['tenant']['country'] ?? null,
            'timezone'      => $data['tenant']['timezone'] ?? null,
            'currency'      => $data['payments']['currency'] ?? null,
            'is_active'     => $data['tenant']['is_active'] ?? false,
            'trial_ends_at' => $data['tenant']['trial_ends_at'] ?? null,
        ]);

        $settingsJson = $data;
        unset($settingsJson['tenant']);

        $this->tenant->update([
            'app_settings' => $settingsJson,
        ]);

        // 5. Success Notification
        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
        //dd
    }
}
