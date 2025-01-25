@props(['title','addbtn' => true, 'buttons' => false])
<style>
    div.dt-buttons>.dt-button,
    div.dt-buttons>div.dt-button-split .dt-button {
        background-color: #696cff !important;
        color: white !important;
    }
</style>
<div class="card">
    <div class="card-datatable table-responsive">
        <div class="row m-2">
            <div class="col">
                <h5 {{ $attributes->merge(['class' => 'card-header']) }}>{{ $title }}</h5>
            </div>
            <div class="col-auto">
                @if ($addbtn)
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCenter">
                    <i class='bx bx-plus-circle'></i> New
                </button>
                @endif
            </div>
        </div>
        <div {{ $attributes->merge(['class' => 'table-responsive text-nowrap']) }} >
            <table {{ $attributes->merge(['class' => 'table table-hover myTable']) }}>
                {{ $slot }}
            </table>
        </div>
    </div>
</div>
<x-slot:js>
    <!-- CSS pour DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.css">
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>

    <script>
        $(".myTable").DataTable({
            responsive: true,
            autoWidth: true,
            'order': [[0, 'desc']],
        }
        );

    </script>
</x-slot:js>