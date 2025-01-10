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
                    <div class="mt-3">
                        <h4>Material request images</h4>
                        @foreach ($material->documents as $row)
                        <div class="col-md-3">
                            <div class="card flex-fill">
                                <img alt="" src="{{ $row->DocLink() }}" class="card-img-top">
                            </div>
                        </div>
                        @endforeach

                    </div>
                    <div class="mt-3">
                        <h4 class="review-subtitle text-md-left">HOD Approval</h4>
                        @if(!$material->isHodApproved())
                        <form wire:submit.prevent="approveByHod({{ $material->id }})">
                            <x-select label="Status" wire:model.live='status'>
                                @foreach (App\Enum\MaterialRequestStatus::cases() as $row)
                                @continue($row->value === "Progress" || $row->value === "Pending")
                                <option value="{{ $row }}">{{ $row }}</option>
                                @endforeach
                            </x-select>
                            @error('status')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <x-textarea wire:model="hod_comment" label="Head of Department (HOD) comments"
                                place="add a comment (optionnel)" />
                            <div>
                                @error('hod_comment')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success mt-2">
                                Approve as HOD
                            </button>
                        </form>
                        @else

                        <div class="table-responsive">
                            <table class="table table-bordered review-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Hod User</th>
                                        <th>Position</th>
                                        <th>Department</th>
                                        <th>Approv date</th>
                                        <th>Status</th>
                                        <th>Comment</th>
                                        <th>Signature</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $material->hod_approval_view() }}</td>
                                        <td>
                                            {{ $material->hodApproval ? $material->hodApproval->department->name : '' }}
                                        </td>
                                        <td>
                                            {{ $material->hodApproval ? $material->hodApproval->poste : '' }}
                                        </td>
                                        <td>{{ $material->hodApproval ? $material->hod_approval_date_format : '' }}
                                        </td>
                                        <td>{{ $material->status }}</td>
                                        <td>
                                            <p class="text-wrap">{{ $material->hod_comment }}</p>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        @endif
                        <h4 class="review-subtitle text-md-left my-3">GM Approval</h4>
                        @if(!$material->isGmApproved())
                        <form wire:submit.prevent="approveByGm({{ $material->id }})">
                            <x-select label="Status" wire:model.live='status'>
                                @foreach (App\Enum\MaterialRequestStatus::cases() as $row)
                                @continue($row->value === "Progress" || $row->value === "Pending")
                                <option value="{{ $row }}">{{ $row }}</option>
                                @endforeach
                            </x-select>
                            @error('status')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <x-textarea wire:model="gm_comment" label="General Manager (GM) comments"
                                place="Add a comment" />
                            @error('gm_comment')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <button type="submit" class="btn btn-success mt-2">
                                Approve as GM
                            </button>
                        </form>
                        @else
                        <div class="table-responsive">
                            <table class="table table-bordered review-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Gm User</th>
                                        <th>Position</th>
                                        <th>Department</th>
                                        <th>Approv date</th>
                                        <th>status</th>
                                        <th>Comment</th>
                                        <th>Signature</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $material->gm_approval_view() }}</td>
                                        <td>
                                            {{ $material->gmApproval ? $material->gmApproval->department->name : '' }}
                                        </td>
                                        <td>
                                            {{ $material->gmApproval ? $material->gmApproval->poste : '' }}
                                        </td>
                                        <td>{{ $material->gmApproval ? $material->gm_approval_date_format : '' }}
                                        </td>
                                        <td>{{ $material->status }}</td>
                                        <td>
                                            <p class="text-wrap">{{ $material->gm_comment }}</p>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @endif
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
</script>