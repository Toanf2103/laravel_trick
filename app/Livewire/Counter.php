<?php

namespace App\Livewire;

use Livewire\Component;

class Counter extends Component
{
    public $count = 1;
    public $title;
    public function increment()
    {
        $this->count++;
    }

    public function decrement()
    {
        $this->count--;
    }

    public function testLoad()
    {
        sleep(3);
        $this->count+=10;
    }

    public function render()
    {
        return view('livewire.counter');
    }
}
