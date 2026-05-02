<?php

namespace App\Filament\Resources\Members\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Members\MemberResource;
use App\Models\Member;
use Illuminate\Support\Facades\Log;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        $tenant = app('tenant');

        $allowed = true;
        $message = null;

        if ($tenant) {

            // ✅ SAFE COUNT (tenant scoped)
            $count = Member::where('tenant_id', $tenant->id)->count();

            // ✅ SAFE PLAN FETCH
            $plan = $tenant->plan ?? null;

            if (!$plan) {
                $allowed = false;
                $message = 'No plan assigned to your account.';
            } else {

                // 🔥 BULLETPROOF LIMIT PARSER
                $rawLimit = $plan->max_members;
                $limit = null;

                if ($rawLimit === null) {
                    $limit = null;
                } elseif (is_numeric($rawLimit)) {
                    // handles int, "2000", "2000.00"
                    $limit = (int) $rawLimit;
                } else {
                    // handles "2,000", "2000 members", etc.
                    $clean = preg_replace('/[^0-9]/', '', $rawLimit);
                    $limit = $clean !== '' ? (int) $clean : null;
                }

                // 🔍 DEBUG (VERY IMPORTANT FOR SERVER)
                Log::info('MEMBER LIMIT DEBUG', [
                    'tenant_id'   => $tenant->id,
                    'raw_limit'   => $rawLimit,
                    'clean_limit' => $limit,
                    'count'       => $count,
                    'comparison'  => $limit !== null ? ($count >= $limit) : null,
                ]);

                // 🚨 MISSING CONFIG
                if ($limit === null) {
                    $allowed = false;
                    $message = 'Member limit is not configured in your plan.';
                }

                // ✅ UNLIMITED
                elseif ($limit === 0) {
                    $allowed = true;
                }

                // ❌ LIMIT REACHED
                elseif ($count >= $limit) {
                    $allowed = false;
                    $message = 'Member limit reached. Please upgrade your plan.';
                }

                // ✅ ALLOWED
                else {
                    $allowed = true;
                }
            }
        }

        return [
            CreateAction::make()
                ->label('Add Member')
                ->color(!$allowed ? 'danger' : 'primary')
                ->disabled(!$allowed)
                ->tooltip($message)
                ->before(function () use ($allowed, $message) {

                    if (!$allowed) {
                        Notification::make()
                            ->title('Action Blocked')
                            ->body($message)
                            ->warning()
                            ->send();

                        return false; // ⛔ stop safely
                    }
                }),
        ];
    }
}