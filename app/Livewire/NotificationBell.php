<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enum\MaterialRequestStatus;
use App\Models\CarRequest;
use App\Models\MaterialRequest;
use App\Models\Recording;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

use function compact;

final class NotificationBell extends Component
{
    public function render()
    {
        $user = Auth::user();

        // 1) En attente de l'approbation de cet utilisateur (selon son étape : HOD / Directeur / GM)
        $awaiting = collect();
        if ($user->isApprover()) {
            $cars = CarRequest::query()->awaitingApprovalBy($user)
                ->with('user:id,name')->latest('id')->take(15)->get()
                ->map(fn ($r) => (object) [
                    'type' => 'Vehicle',
                    'ref' => $r->reference,
                    'who' => $r->user?->name,
                    'link' => route('car.show', $r),
                    'at' => Carbon::parse($r->getRawOriginal('created_at')),
                ]);

            $mats = MaterialRequest::query()->awaitingApprovalBy($user)
                ->with('user:id,name')->latest('id')->take(15)->get()
                ->map(fn ($r) => (object) [
                    'type' => 'Material',
                    'ref' => $r->reference,
                    'who' => $r->user?->name,
                    'link' => route('material.show', $r),
                    'at' => Carbon::parse($r->getRawOriginal('created_at')),
                ]);

            $awaiting = $cars->concat($mats)->sortByDesc('at')->take(15)->values();
        }

        // 2) Décisions finales sur les demandes de l'utilisateur (approuvée / rejetée)
        $finals = [MaterialRequestStatus::Approved, MaterialRequestStatus::Rejected];

        $carDec = CarRequest::query()->where('user_id', $user->id)->whereIn('status', $finals)
            ->latest('updated_at')->take(6)->get()
            ->map(fn ($r) => (object) [
                'type' => 'Vehicle',
                'ref' => $r->reference,
                'status' => $r->status->value,
                'link' => route('car.show', $r),
                'at' => $r->updated_at,
            ]);

        $matDec = MaterialRequest::query()->where('user_id', $user->id)->whereIn('status', $finals)
            ->latest('updated_at')->take(6)->get()
            ->map(fn ($r) => (object) [
                'type' => 'Material',
                'ref' => $r->reference,
                'status' => $r->status->value,
                'link' => route('material.show', $r),
                'at' => $r->updated_at,
            ]);

        $myDecisions = $carDec->concat($matDec)->sortByDesc('at')->take(6)->values();

        // 3) Véhicules actuellement dehors (Admin / GM / Security)
        $vehiclesOut = ($user->isAdmin() || $user->isGm() || $user->isSecurity())
            ? Recording::query()->vehiclesOut()->with('requestable')->get()
            : collect();

        // Badge = actions requises de l'utilisateur (en attente d'approbation)
        $badge = $awaiting->count();

        return view('livewire.notification-bell', compact('awaiting', 'myDecisions', 'vehiclesOut', 'badge'));
    }
}
