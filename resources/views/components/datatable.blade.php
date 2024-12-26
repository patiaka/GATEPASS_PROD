@props(['title','addbtn' => true])

<div class="card">
    <div class="card-datatable table-responsive">
        <div class="row m-2">
            <div class="col">
                <h3>{{ $title }}</h3>
            </div>
            <div class="col-auto">
                @if ($addbtn)
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCenter">
                    <i class='bx bx-plus-circle'></i> Nouveau
                </button>
                @endif
            </div>
        </div>
        <div {{ $attributes->merge(['class' => 'table-responsive text-nowrap']) }} >
            <table {{ $attributes->merge(['class' => 'datatable table table-stripped mb-0']) }}>
                {{ $slot }}
            </table>
        </div>
    </div>
</div>