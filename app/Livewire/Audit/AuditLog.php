<?php

declare(strict_types=1);

namespace App\Livewire\Audit;

use App\Models\ActivityLog;
use App\Models\LoginLog;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Audit log')]
final class AuditLog extends Component
{
    use WithPagination;

    /** activity | logins */
    #[Url]
    public string $tab = 'activity';

    #[Url]
    public string $search = '';

    public function setTab(string $tab): void
    {
        $this->tab = in_array($tab, ['activity', 'logins'], true) ? $tab : 'activity';
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $term = '%'.$this->search.'%';

        if ($this->tab === 'logins') {
            $rows = LoginLog::query()
                ->when($this->search !== '', fn ($q) => $q->where(fn ($w) => $w
                    ->where('user_name', 'like', $term)
                    ->orWhere('ip_address', 'like', $term)))
                ->latest('id')
                ->paginate(20);
        } else {
            $rows = ActivityLog::query()
                ->when($this->search !== '', fn ($q) => $q->where(fn ($w) => $w
                    ->where('subject_ref', 'like', $term)
                    ->orWhere('causer_name', 'like', $term)
                    ->orWhere('event', 'like', $term)
                    ->orWhere('subject_type', 'like', $term)))
                ->latest('id')
                ->paginate(20);
        }

        return view('livewire.audit.audit-log', ['rows' => $rows]);
    }
}
