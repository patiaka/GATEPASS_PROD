@props(['title', 'addbtn' => true, 'filter' => '', 'addcreate' => '', 'rows' => ''])

<div>
    <div class="flex items-center justify-between mb-6 border-b pb-6">
        <div class="flex-1">
            <h5 {{ $attributes->merge(['class' => 'text-2xl font-bold text-[#134169] tracking-tight']) }}>{{ $title }}</h5>
        </div>
        <div class="flex items-center space-x-2">
            @if ($addbtn)
            <x-button-add />
            @endif

            @if ($addcreate)
            {{ $addcreate }}
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md grid gap-4 py-4">
        
        @if ($filter)
        <div class="px-4 flex">
            {{ $filter }}
        </div>
        @endif
    
        <div class="flex items-center justify-between px-4">
            <div class="w-1/3">
                <div class="relative flex items-center">
                    <span class="absolute left-3 text-gray-400">
                        <i data-lucide="search"></i>
                    </span>
                    <input wire:model.live.debounce.100ms='search' type="text"
                        class="w-full pl-10 pr-4 py-2 border rounded-md" placeholder="Search...">
                </div>
            </div>
    
            <div class="flex-1 text-right">
                <button wire:click='ResetFilter' {{ $attributes->merge(['class' => 'ml-3 bg-red-600 hover:bg-red-700
                    text-white
                    px-4 py-2 rounded-md flex items-center']) }}
                    type="button">
                    <i class='mr-1' data-lucide="x"></i>
                    Reset Filters
                </button>
            </div>
        </div>
    
        <div {{ $attributes->merge(['class' => 'overflow-x-auto']) }}>
            <table {{ $attributes->merge(['class' => 'w-full text-sm text-left text-gray-700 bg-white border-b border-gray
                rounded shadow-sm']) }}>
                {{ $slot }}
            </table>
        </div>
    
        @if($rows)
        <div class="p-4">
            {{ $rows->links() }}
        </div>
        @endif
    </div>
</div>