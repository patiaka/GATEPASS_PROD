<div>
    <x-table title="List of material request" :addbtn="false">
        <x-slot:addcreate>
            <a href="{{ route('material.create') }}" role="button" class="btn btn-primary">
                <i class='me-1 bx bx-plus-circle'></i> New
            </a>
        </x-slot:addcreate>
        <x-slot:filter>

            <div class="col-sm-6 col-md-3">
                <x-input type="text" wire:model.live="search" label="Search" />
            </div>
            <div class="col-sm-6 col-md-3">
                <x-select label="Department" wire:model.live='department'>
                    @foreach ($departments as $row)
                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                    @endforeach
                </x-select>
            </div>
            <div class="col-sm-6 col-md-3">
                <x-select label="User" wire:model.live='user'>
                    @foreach ($users as $row)
                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                    @endforeach
                </x-select>
            </div>
            <div class="col-sm-6 col-md-3">
                <x-select label="Status" wire:model.live='status'>
                    @foreach (App\Enum\MaterialRequestStatus::cases() as $row)
                    <option value="{{ $row }}">{{ $row }}</option>
                    @endforeach
                </x-select>
            </div>
            <div class="col-md-5">
                <hr>
                <h3>apply Action</h3>
                <div class="mb-3">
                    <x-select label="Status" wire:model='bulkAction'>
                        @foreach (App\Enum\MaterialRequestStatus::cases() as $row)
                        <option value="{{ $row }}">{{ $row }}</option>
                        @endforeach
                    </x-select>

                    <button class="btn btn-danger" wire:click="bulkDelete" @if(empty($selectedRows)) disabled @endif>
                        Delete Selected
                    </button>
                </div>
            </div>

        </x-slot:filter>
        <thead>
            <tr>
                <th>
                    <input type="checkbox" wire:click="selectAll" wire:model="selectedRows" id="select-all">
                </th>
                <th>ID</th>
                <th>Reference</th>
                <th>Email/Name</th>
                <th>GM Approval</th>
                <th>HOD Approval</th>
                <th>Status</th>
                <th>Created Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @forelse ($this->rows as $row)
            <tr wire:key="row-{{ $row->id }}" @class(['table-primary'=> in_array($row->id, $selectedRows)
                ])>
                <td>
                    <input type="checkbox" wire:model.live="selectedRows" value="{{ $row->id }}">
                </td>
                <td>{{ $row->id }}</td>
                <td>{{ $row->reference }}</td>
                <td>{{ $row->user->name }}</td>
                <td>{{ $row->gm_approval_view() }}</td>
                <td>{{ $row->hod_approval_view() }}</td>
                <td>
                    <div class="dropdown action-label">
                        <span class="btn badge rounded-pill bg-success btn-sm">
                            <i @class([ 'bx bx-dot-circle-o' , 'text-success'=> $row->isApproved(),
                                'text-danger' => $row->isRejected(),
                                'text-info' => $row->isPending(),
                                ]) ></i>
                            {{ $row->status }}
                        </span>
                    </div>
                </td>
                <td>{{ $row->created_at }}</td>
                <td>
                    <button wire:click="show_detail({{ $row->id }})" class="btn btn-success">
                        <i class="bx bx-check-circle"></i>
                    </button>
                    <x-button-edit href="{{ route('material.edit', ['material' => $row]) }}" />
                    <x-button-show href="{{ route('material.show', ['material' => $row]) }}" />
                    <x-button-delete url="{{ url('material/' . $row->id) }}" />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">No result</td>
            </tr>
            @endforelse
        </tbody>

    </x-table>

    <div wire:ignore.self>
        <div id="modalCenter" class="modal custom-modal fade" role="dialog" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Material request infos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        @if ($material)
                        <section class="review-section">
                            <div class="review-header text-star">

                                <h4 class="review-subtitle text-md-left">
                                    <span class="review-subtitle-text">Reference: {{ $material->reference }}</span> <br>
                                    <span class="review-subtitle-text">Status: {{ $material->status }}</span> <br>
                                    <span class="review-subtitle-text">User Created: {{ $material->user->name }}</span>
                                    <br>
                                    <span class="review-subtitle-text">User Department: {{
                                        $material->user->department->name }}</span>
                                    <br>
                                    <span class="review-subtitle-text">Created Date: {{ $material->created_at }}</span>
                                </h4>

                            </div>
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="mt-3">
                                        <h4 class="review-subtitle text-md-left mt-2">HOD Validation infos</h4>
                                        @if(!$material->isHodApproved())
                                        <form wire:submit.prevent="approveByHod({{ $material->id }})">
                                            <x-textarea wire:model="hod_comment" :required="false"
                                                label="Head of Department (HOD) comments"
                                                place="add a comment (optionnel)" />
                                            <button type="submit" class="btn btn-success mt-2">
                                                Validate as HOD
                                            </button>
                                        </form>
                                        @else
                                        <p>HOD a validé le {{ $material->hod_approval_date_format }}</p>
                                        <p>Comment : {{ $material->hod_comment }}</p>
                                        @endif
                                        <h4 class="review-subtitle text-md-left">GM Validation infos</h4>
                                        @if(!$material->isGmApproved())
                                        <form wire:submit.prevent="approveByGm({{ $material->id }})">
                                            <x-textarea wire:model="gm_comment" label="General Manager (GM) comments"
                                                place="Add a comment (optionnel)" :required="false" />
                                            <button type="submit" class="btn btn-success mt-2">
                                                Validate as HOD
                                            </button>
                                        </form>
                                        @else
                                        <div class="table-responsive">
                                            <table class="table table-bordered review-table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>GM User</th>
                                                        <th>Department</th>
                                                        <th>Approv date</th>
                                                        <th>Comment</th>
                                                        <th>Signature</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr>
                                                        <td>{{ $material->user->email }}<br>{{ $material->user->name }}
                                                        </td>
                                                        <td>{{ $material->user->department->name }}</td>
                                                        <td>{{ $material->gm_approval_date_format }}</td>
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
                        <div class="modal-footer mt-2 justify-content-center">
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">
                                Close
                            </button>
                        </div>
                        @else
                        <p>Loading...</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('show-modal', function () {
        $('#modalCenter').modal('show');
    });
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