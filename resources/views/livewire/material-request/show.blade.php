<div>
    <div class="card p-3">
        <section class="review-section">
            <div class="review-header text-star">
                <h3 class="review-title">Material request</h3>
                <h4 class="review-subtitle text-md-left">
                    <span class="review-subtitle-text">Reference: {{ $material->reference }}</span> <br>
                    <span class="review-subtitle-text">Status: {{ $material->status }}</span> <br>
                    <span class="review-subtitle-text">Department: {{
                        $material->user->department->name }}</span>
                    <br>
                    <span class="review-subtitle-text">Requestor: {{ $material->user->name }}</span>
                    <br>

                    <span class="review-subtitle-text">Created Date: {{ $material->created_at }}</span>
                </h4>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered review-table mb-0">
                            <thead>
                                <tr>
                                    <th style="width:40px;">#</th>
                                    <th>Designation</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($material->material_request_items as $row)
                                <tr>
                                    <td>{{ $row->id }}</td>
                                    <td>{{ $row->designation }}</td>
                                    <td>{{ $row->quantity }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="my-5">
                        <h4>Material request images</h4>
                        <div class="row">
                            @foreach ($material->loadMissing('documents')->documents as $row)
                            <div class="col-md-3">
                                <div class="card flex-fill">
                                    <img alt="image" src="{{ $row->DocLink() }}" class="card-img-top">
                                    @if (Auth::user()->isAdmin())
                                    <div class="card-img-overlay">
                                        <x-button-edit href="{{ route('document.edit', ['document' => $row]) }}" />
                                        <x-button-delete url="{{ url('document/' . $row->id) }}" />
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <hr>

                    <div class="mt-3">
                        <x-form-request-validate :model="$material" type="material" />
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<script>
    $(".select2").each(function () {
            var current = $(this);
            current.wrap('<div class="position-relative"></div>').select2({
                placeholder: "Selectionner",
                dropdownParent: current.parent(),
            });
            // Get the Livewire property name from the wire:model attribute
            var propertyName = current.attr('wire:model.live');
            // Listen for change event and update Livewire property
            current.on('change', function (e) {
            // Add opacity to table
            $('.table-responsive').addClass('opacity-50');

            @this.set(propertyName, $(this).val()).then(() => {
                // Remove opacity after Livewire updates
                $('.table-responsive').removeClass('opacity-50');
            });
            });
        });
</script>