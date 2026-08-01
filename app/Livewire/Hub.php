<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.hub')]
#[Title('Choose a system')]
class Hub extends Component
{
    public function render()
    {
        return view('livewire.hub');
    }
}
