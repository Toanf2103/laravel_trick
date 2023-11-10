<div>
    a
    <h1>{{ $count }}</h1>
    <h1>{{ time() }}</h1>

    <input  type="text" wire:model.live="title"/>
    <h2>title- {{ $title }}</h2>
    <h1 wire:loading.remove wire:target="testLoad">loader</h1>
    <h2 wire:loading wire:target="testLoad">loading</h2>
    <button wire:click="increment">+</button>

    <button wire:click="decrement">-</button>
    <button wire:click="testLoad">testload</button>
</div>