@props(['title','addbtn' => true,'filter' => '','addcreate' => '', 'rows' => ''])
<div class="card">
    <div class="row m-2">
        <div class="col">
            <h5 {{ $attributes->merge(['class' => 'card-header py-0']) }}>{{ $title }}</h5>
        </div>
        <div class="col-auto">
            @if ($addbtn)
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCenter">
                <i class='me-1 bx bx-plus-circle'></i> Nouveau
            </button>
            @endif
            @if ($addcreate)
            {{ $addcreate }}
            @endif
        </div>
    </div>
    <div class="row mx-2 filter-row">
        {{ $filter }}
    </div>
    <div class="row mx-2 my-3">

        <div class="col">
            <button wire:click='ResetFilter' {{ $attributes->merge(['class' => 'btn btn-danger']) }}
                type="button">
                <i class='bx bx-reset'></i>
                Reset Filtres
            </button>
        </div>
    </div>

    <div {{ $attributes->merge(['class' => 'table-responsive text-nowrap']) }} >
        <table {{ $attributes->merge(['class' => 'table table-hover']) }}>
            {{ $slot }}
        </table>
    </div>
    @if($rows)
    {{ $rows->links() }}
    @endif

</div>
