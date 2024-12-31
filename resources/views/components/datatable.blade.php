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
                    <i class='bx bx-plus-circle'></i> Nouveau
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
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    @if ($buttons)
    <!-- DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.dataTables.js"></script>

    <!-- JSZip pour export Excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <!-- pdfMake pour export PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js"></script>

    <!-- Export HTML5 (CSV, Excel, PDF) -->
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
    <script>
        $(".myTable").DataTable({
        responsive: true,
        autoWidth: true,
        'order': [[0, 'desc']],
        dom: 'Bfrtip',
            buttons: [
                'print',
                'excelHtml5',
                'pdfHtml5',
            ],
        language: { url: 'https://cdn.datatables.net/plug-ins/1.10.24/i18n/French.json'},
    }
    );
    </script>
    @else
    <script>
        $(".myTable").DataTable({
            responsive: true,
            autoWidth: true,
            'order': [[0, 'desc']],
            language: { url: 'https://cdn.datatables.net/plug-ins/1.10.24/i18n/French.json'},
        }
        );

    </script>
    @endif
</x-slot:js>