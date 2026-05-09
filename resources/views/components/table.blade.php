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

    <div class="bg-white rounded-lg shadow-md">
        
        <div class="p-4 grid gap-4">
            @if ($filter)
            <div class="flex">
                {{ $filter }}
            </div>
            @endif
        
            <div class="flex items-center justify-between">
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
                    <button wire:click='ResetFilter' {{ $attributes->merge([
                        'class' => 'ml-3 bg-white hover:bg-[#0e3a61] text-[#134169] hover:text-white border border-[#134169] px-4 py-2 rounded-md flex items-center'
                    ]) }}
                        type="button">
                        <i class='mr-1' data-lucide="x"></i>
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>
    
        <div {{ $attributes->merge(['class' => 'overflow-x-auto border border-gray-200 bg-white']) }}>
            <table {{ $attributes->merge(['class' => 'min-w-[900px] w-full text-left text-[13px]']) }}>
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