<?php

declare(strict_types=1);

namespace App\Helper;

use Livewire\Attributes\Url;

/**
 * Tri de colonnes réutilisable pour les listes Livewire.
 *
 * - clic sur un en-tête : tri ascendant, re-clic : descendant.
 * - l'état est reflété dans l'URL (?sort=&dir=) pour être partageable.
 * - applySort() consomme une table de correspondance champ => colonne|Closure,
 *   et retombe sur un tri par défaut quand aucune colonne n'est sélectionnée.
 */
trait WithSorting
{
    #[Url(as: 'sort')]
    public ?string $sortField = null;

    #[Url(as: 'dir')]
    public string $sortDirection = 'asc';

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    /**
     * Applique le tri courant à la requête.
     *
     * @param  array<string, string|\Closure>  $map  champ => colonne, ou Closure(query, dir)
     * @param  \Closure|null  $default  tri appliqué quand aucune colonne n'est sélectionnée
     */
    protected function applySort($query, array $map, ?\Closure $default = null)
    {
        if ($this->sortField !== null && isset($map[$this->sortField])) {
            $column = $map[$this->sortField];
            $direction = $this->sortDirection === 'asc' ? 'asc' : 'desc';

            if ($column instanceof \Closure) {
                return $column($query, $direction);
            }

            return $query->orderBy($column, $direction);
        }

        if ($default !== null) {
            return $default($query);
        }

        return $query->orderByDesc('id');
    }
}
