@props(['model','type'])
<div class="row">
    <div class="col-md-12">
        <div class="mt-3">
            <h4 class="review-subtitle text-md-left">HOD Approval</h4>
            @if(!$model->isHodApproved())
            <form wire:submit.prevent="approveByHod({{ $model->id }}, {{ $type }})">
                <x-select label="Status" wire:model.live='status'>
                    @foreach (App\Enum\MaterialRequestStatus::cases() as $row)
                    @continue($row->value === "Progress" || $row->value === "Pending")
                    <option value="{{ $row }}">{{ $row }}</option>
                    @endforeach
                </x-select>
                @error('status')
                <small class="text-danger">{{ $message }}</small>
                @enderror
                <x-textarea wire:model="hod_comment" label="Head of Department (HOD) comments" place="add a comment" />
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
                            <td>{{ $model->hod_approval_view() }}</td>
                            <td>
                                {{ $model->hodApproval ? $model->hodApproval->department->name :
                                '' }}
                            </td>
                            <td>
                                {{ $model->hodApproval ? $model->hodApproval->poste : '' }}
                            </td>
                            <td>{{ $model->hodApproval ? $model->hod_approval_date_format : '' }}
                            </td>
                            <td>{{ $model->status }}</td>
                            <td>
                                <p class="text-wrap">{{ $model->hod_comment }}</p>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @endif
            <h4 class="review-subtitle text-md-left my-3">GM Approval</h4>
            @if(!$model->isGmApproved())
            <form wire:submit.prevent="approveByGm({{ $model->id }}, {{ $type }})">
                <x-select label="Status" wire:model.live='status'>
                    @foreach (App\Enum\MaterialRequestStatus::cases() as $row)
                    @continue($row->value === "Progress" || $row->value === "Pending")
                    <option value="{{ $row }}">{{ $row }}</option>
                    @endforeach
                </x-select>
                @error('status')
                <small class="text-danger">{{ $message }}</small>
                @enderror
                <x-textarea wire:model="gm_comment" label="General Manager (GM) comments" place="Add a comment" />
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
                            <td>{{ $model->gm_approval_view() }}</td>
                            <td>
                                {{ $model->gmApproval ? $model->gmApproval->department->name : ''
                                }}
                            </td>
                            <td>
                                {{ $model->gmApproval ? $model->gmApproval->poste : '' }}
                            </td>
                            <td>{{ $model->gmApproval ? $model->gm_approval_date_format : '' }}
                            </td>
                            <td>{{ $model->status }}</td>
                            <td>
                                <p class="text-wrap">{{ $model->gm_comment }}</p>
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