<?php

declare(strict_types=1);

namespace App\Helper;

trait RepeatInputAction
{
    public function addDriver()
    {
        $this->form->add('driver');
    }

    public function addPassenger()
    {
        $this->form->add('passenger');
    }

    public function removeDriver($index)
    {
        $this->form->remove('driver', $index);
    }

    public function removePassenger($index)
    {
        $this->form->remove('passenger', $index);
    }

    public function addMaterial(): void
    {
        $this->form->addMaterial();
    }

    public function removeMaterial($index): void
    {
        $this->form->removeMaterial($index);
    }
}
