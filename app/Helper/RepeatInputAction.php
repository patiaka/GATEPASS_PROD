<?php

declare(strict_types=1);

namespace App\Helper;

trait RepeatInputAction
{

    public array $drivers = []; // Tableau pour stocker les drivers
    public array $passengers = []; // Tableau pour stocker les passengers
    public array $materials = []; // Tableau pour stocker les matériels

    public function add(string $type): void
    {
        if ($type === 'driver') {
            $this->drivers[] = ['name' => '', 'contact' => ''];
        } elseif ($type === 'passenger') {
            $this->passengers[] = ['name' => '', 'contact' => ''];
        }
    }

    public function remove(string $type, int $index): void
    {
        if ($type === 'driver') {
            unset($this->drivers[$index]);
            $this->drivers = array_values($this->drivers); // Réindexer le tableau
        } elseif ($type === 'passenger') {
            unset($this->passengers[$index]);
            $this->passengers = array_values($this->passengers); // Réindexer le tableau
        }
    }

    public function addMaterial(): void
    {
        $this->materials[] = ['designation' => '', 'quantity' => 1];
    }

    public function removeMaterial($index): void
    {
        unset($this->materials[$index]);
        $this->materials = array_values($this->materials); // Réindexer le tableau
    }
}
