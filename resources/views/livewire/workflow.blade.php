<div>
    @foreach($transitions as $transition)
        <button wire:click="apply('{{ $transition->getName() }}')" type="button" class="focus:outline-none text-white text-sm py-2.5 px-5 rounded-md bg-blue-500 hover:bg-blue-600 hover:shadow-lg">{{ $transition->getName() }}</button>
    @endforeach

    <x-jet-secondary-button wire:click="$toggle('modal')" wire:loading.attr="disabled">
        Nevermind
    </x-jet-secondary-button>

    <x-jet-dialog-modal wire:model="modal">
        <x-slot name="title">
            Title
        </x-slot>

        <x-slot name="content">
            Content
        </x-slot>

        <x-slot name="footer">
            Footer
        </x-slot>
    </x-jet-dialog-modal>
</div>
