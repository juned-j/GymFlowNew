<?php

namespace App\Filament\Resources\Subscriptions\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use App\Models\MembershipPlan;
use App\Models\User;

class SubscriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            // Automatically inject the tenant_id from the authenticated user's context
            Hidden::make('tenant_id')
                ->default(fn() => auth()->user()->getTenantId()),

            Section::make('Subscription Assignment')
                ->description('Link a member to a membership plan.')
                ->schema([
                    Select::make('user_id')
                        ->label('Member')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        // Ensure we only see users associated with this tenant
                        ->options(function () {
                            $tenantId = auth()->user()->getTenantId();
                            return User::whereHas('roles', function ($query) use ($tenantId) {
                                $query->where('tenant_id', $tenantId);
                            })->pluck('name', 'id');
                        }),

                    Select::make('membership_plan_id')
                        ->label('Membership Plan')
                        ->relationship('membershipPlan', 'name')
                        ->required()
                        // Ensure we only see plans created by this gym
                        ->options(function () {
                            return MembershipPlan::where('tenant_id', auth()->user()->getTenantId())
                                ->pluck('name', 'id');
                        }),
                ])->columnSpanFull(),

            Section::make('Status & Timing')
                ->schema([
                    Select::make('status')
                        ->options([
                            'active' => 'Active',
                            'trialing' => 'Trialing',
                            'past_due' => 'Past Due',
                            'canceled' => 'Canceled',
                            'expired' => 'Expired',
                        ])
                        ->default('active')
                        ->required(),

                    TextInput::make('stripe_subscription_id')
                        ->label('Stripe ID')
                        ->placeholder('sub_xxxxxxxxxxxx')
                        ->helperText('Leave blank if this is a manual/cash subscription.'),

                    DateTimePicker::make('trial_ends_at')
                        ->label('Trial Expiry'),

                    DateTimePicker::make('ends_at')
                        ->label('Subscription Expiry')
                        ->helperText('Leave blank for lifetime or open-ended plans.'),
                ])->columnSpanFull(),
        ]);
    }
}
