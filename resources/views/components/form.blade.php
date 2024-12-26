@props(['type'=> '','url' => '','route'])
@if($type === "update")
<h2 class="p-4 text-center">Form of update</h2>
@endif
<form novalidate="" action="{{ $route }}" {{ $attributes->merge(['class' => 'needs-validation']) }}
    enctype="multipart/form-data" method="post">
    @csrf
    <div class="card-body px-0">

        <div class="row">
            {{ $slot }}
        </div>
    </div>
    </div>
    @if($type === "update")
    @method('PATCH')
    @endif
    <div class="modal-footer mt-2 justify-content-center">
        @if($type === "update" || $type === "create")
        <a href="{{ $url }}" {{ $attributes->merge(['role' => 'button'])
            }} {{
            $attributes->merge(['class' => 'btn btn-outline-danger'])
            }}>
            Cancel
        </a>
        @else
        <button type="button" {{ $attributes->merge(['class'=>'btn btn-outline-danger']) }}
            data-bs-dismiss="modal">
            Close
        </button>
        @endif

        <button {{ $attributes->merge(['type'=>'submit']) }} {{
            $attributes->merge(['class'=>'mx-2 btn btn-success'])
            }}>Validate</button>
    </div>
</form>