<?php
namespace App\Services;
use App\Models\Billing;

class BillingService {
    public function LogTransaction(\App\Models\User $user, \App\Models\Workspace $workspace, string $service_name, int $duration_ms = 0): void
    {
        $cost = 0;

        switch ($service_name) {
            case 'chatterblast':
                $cost = ($duration_ms / 10);
                break;
            case 'image_generation':
                $cost = 0.50;
                break;
            case 'upscale':
            case 'zoom_in':
            case 'zoom_out':
                $cost = 0.10;
                break;
            case 'image_recognition':
                $cost = 0.30;
                break;
        }

        Billing::query()->create([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'service_name' => $service_name,
            'cost' => $cost,
            'duration_ms' => $duration_ms,
        ]);
    }
}
