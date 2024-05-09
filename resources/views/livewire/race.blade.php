<div>
    @if(auth()->check())
        <div class="container mx-auto text-center py-12 text-xl">
            <div>Recording Clicks For : {{ ucwords(auth()->user()->name) }}</div>

            @if($user->clicks < 100)
                <button type="button" wire:click="increment" value="Increment" class="p-2 px-6 my-6 border bg-gray-300">CLICK ME!</button><br>
            @endif

            <div>Click: {{ $user->clicks }}</div>

            @if(auth()->user()->name == 'matthew')
                <button type="button" wire:click="resetClicks" value="RESET" class="p-2 px-6 my-6 border bg-gray-300">RESET</button><br>
                <button type="button" wire:click="startUserClicks" value="START" class="p-2 px-6 my-6 border bg-gray-300">START</button><br>
            @endif
        </div>
    @endif

</div>
