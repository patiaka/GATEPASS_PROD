<div>
    <section class="review-section">
        <div class="review-header text-center">
            <h3 class="review-title">Material request infos</h3>
            <h4 class="review-subtitle text-md-left">
                <span class="review-subtitle-text">Reference: {{ $material->reference }}</span> <br>
                <span class="review-subtitle-text">Status: {{ $material->status }}</span> <br>
                <span class="review-subtitle-text">User Created: {{ $material->user->email }}</span>
                <br>
                <span class="review-subtitle-text">User Department: {{
                    $material->user->department->name }}</span>
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
                    <h4 class="review-subtitle text-md-left">HOD Validation infos</h4>
                    @if(!$material->isHodApproved())
                    <form wire:submit.prevent="approveByHod({{ $material->id }})">
                        <x-textarea wire:model="hod_comment" :required="false" label="Head of Department (HOD) comments"
                            place="add a comment (optionnel)" />
                        <button type="submit" class="btn btn-success mt-2">
                            Validate as GM
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
                    <h4 class="review-subtitle text-md-left mt-2">GM Validation infos</h4>
                    @if(!$material->isGmApproved())
                    <form wire:submit.prevent="approveByGm({{ $material->id }})">
                        <x-textarea wire:model="gm_comment" label="General Manager (GM) comments"
                            place="Add a comment (optionnel)" :required="false" />
                        <button type="submit" class="btn btn-success mt-2">
                            Validate as GM
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
</div>