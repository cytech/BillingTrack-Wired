<?php

namespace BT\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class Modals extends Component
{
    public $alias;
    public $params = [];
    public $classes;

//    protected $listeners = ['showModal', 'resetModal'];

    public function render()
    {
        return view('livewire.modals');
    }

    #[On('showModal')]
    public function showModal($alias, $params, $classes = null) //public function showModal($alias, ...$params)
    {
        $this->alias = $alias;
        $this->params = $params;
        $this->classes = $classes;

        $this->dispatch('showBootstrapModal');
    }

    #[On('resetModal')]
    public function resetModal()
    {
        $this->reset();
    }
}
