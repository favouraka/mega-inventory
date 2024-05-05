<?php

namespace App\Livewire\Pages\Auth\Stock;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Stock;

#[Title('View Stock')]
class View extends Component
{
    public $stock;

    public function mount(Stock $stock)
    {
        $this->stock = $stock;
    }

    #[Layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.pages.auth.stock.view');
    }
}
