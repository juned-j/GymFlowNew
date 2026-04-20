<?php

namespace App\Filament\Resources\Subscriptions\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

use Filament\Schemas\Components\Grid;

class SubscriptionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Subscription Information')
                    ->description('Overview of the member\'s current plan and payment status.')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('user.name')
                                    ->label('Member Name')
                                    ->weight('bold'),

                                TextEntry::make('membershipPlan.name')
                                    ->label('Plan Name')
                                    ->badge()
                                    ->color('info'),

                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'active' => 'success',
                                        'trialing' => 'warning',
                                        'canceled', 'expired' => 'danger',
                                        default => 'gray',
                                    }),
                            ]),

                        Grid::make(3)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Started On')
                                    ->dateTime(),

                                TextEntry::make('trial_ends_at')
                                    ->label('Trial Ends')
                                    ->dateTime()
                                    ->placeholder('No Trial'),

                                TextEntry::make('ends_at')
                                    ->label('Expires On')
                                    ->dateTime()
                                    ->placeholder('Lifetime / Ongoing'),
                            ]),

                        TextEntry::make('stripe_subscription_id')
                            ->label('Stripe Reference')
                            ->copyable()
                            ->fontFamily('mono')
                            ->placeholder('Manual / Cash Entry'),
                    ]),
            ]);
    }
}
