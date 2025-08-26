<div>
    {{-- <div class="card p-3">
        <section class="review-section">
            <h3 class="review-title">Material request</h3>
            <div class="review-header text-star d-flex justify-content-between">
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
                <div>
                    <x-button-print href="{{ route('material.print', ['material' => $material]) }}" :row="$material" />
                </div>
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
                                    @if ($material->user_id === Auth::user()->id and $material->isPending())
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
    </div> --}}
    <main class="p-4 md:p-6 space-y-6">
        <!-- Header -->
        <header class="relative h-20 bg-[#0F3369] text-white overflow-hidden flex items-center">
            <!-- Logo left -->
            <div class="h-full w-44 flex items-center justify-start pl-3">
                <img src="assets/images/logo.jpg" alt="Company Logo" class="h-full w-auto object-contain" />
            </div>
            <!-- Centered title -->
            <div class="absolute inset-0 flex items-center justify-center">
                <h1 class="text-xl font-bold tracking-wide text-center">GATE PASS / BON DE SORTIE</h1>
            </div>
        </header>
        <!-- Request Info -->
        <section class="break-inside-avoid">
            <h2 class="sr-only">Request Information</h2>
            <table class="w-full border-2 border-black border-collapse">
                <tbody>
                    <tr>
                        <th class="w-1/5 border-2 border-black bg-gray-100 p-2 text-left">Date</th>
                        <td class="border-2 border-black p-2">{{ $MaterialRequest->created_at }}</td>
                    </tr>
                    <tr>
                        <th class="w-1/5 border-2 border-black bg-gray-100 p-2 text-left">Name</th>
                        <td class="border-2 border-black p-2">{{ $MaterialRequest->user->name }}</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Material Items -->
        <section class="break-inside-avoid">
            <h2 class="sr-only">Material Items</h2>
            <table class="w-full border-2 border-black border-collapse">
                <thead>
                    <tr class="[&>th]:border-2 [&>th]:border-black [&>th]:bg-gray-100 [&>th]:p-2 text-left">
                        <th class="w-12">#</th>
                        <th>DESCRIPTION / DESIGNATION</th>
                        <th class="w-24">QUANTITY</th>
                        <th class="w-40">Serial Number</th>
                        <th class="w-32">PHOTO</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example row (duplicate/remove as needed) -->
                    @foreach ($MaterialRequest->loadMissing('material_request_items')->material_request_items as $row)
                    <tr class="[&>td]:border-2 [&>td]:border-black [&>td]:p-2 align-top">
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->designation }}</td>
                        <td>{{ $row->quantity }}</td>
                        <td>{{ $row->serial_number }}</td>
                        <td class="text-center">
                            @foreach ($MaterialRequest->loadMissing('documents')->documents as $row)
                            <!-- Replace with actual path -->
                            <img src="{{ $row->DocLink() }}" alt="Item photo"
                                class="inline-block max-w-[90px] max-h-20 object-contain" />
                            @endforeach
                        </td>
                    </tr>
                    @endforeach

                    <!-- /Example rows -->
                </tbody>
            </table>
        </section>
        <!-- Signature Approvals -->
        <section class="break-inside-avoid">
            <h3 class="text-center font-semibold text-[#0F3369] text-base">AUTHORISED SIGNATURE APPROVALS</h3>
            <table class="w-full border-2 border-black border-collapse">
                <thead>
                    <tr class="[&>th]:border-2 [&>th]:border-black [&>th]:bg-gray-100 [&>th]:p-2 text-center">
                        <th>Company / Dept</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Signature</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Applicant -->
                    <tr class="[&>td]:border-2 [&>td]:border-black [&>td]:p-2 text-center">
                        <td>{{ $MaterialRequest->user->department->name }}</td>
                        <td>{{ $MaterialRequest->user->name }}</td>
                        <td>{{ $MaterialRequest->user->poste }}</td>
                        <td class="h-14"></td>
                    </tr>
                    <!-- HOD -->
                    <tr class="[&>td]:border-2 [&>td]:border-black [&>td]:p-2 text-center">
                        <td>{{ $MaterialRequest->user->department->name }}</td>
                        <td>{{ $MaterialRequest->hodApproval ? $MaterialRequest->hodApproval->department->name : '—' }}
                        </td>
                        <td>Head of Department</td>
                        <td class="h-14">
                            <x-request-status :model="$MaterialRequest" type="hod" />
                            <!-- Replace with actual signature path or leave empty -->
                            {{-- <img src="storage/signatures/hod-sign.png" alt="HOD Signature"
                                class="mx-auto h-12 object-contain" /> --}}
                        </td>
                    </tr>
                    <!-- GM -->
                    <tr class="[&>td]:border-2 [&>td]:border-black [&>td]:p-2 text-center">
                        <td>{{ $MaterialRequest->user->department->name }}</td>
                        <td>{{ $MaterialRequest->gmApproval ? $MaterialRequest->gmApproval->department->name : '—' }}
                        </td>
                        <td>General Manager</td>
                        <td>{{ $MaterialRequest->gm_approval_view() }}</td>
                        <td class="h-14">
                            <x-request-status :model="$MaterialRequest" type="gm" />
                            {{-- <img src="storage/signatures/gm-sign.png" alt="GM Signature"
                                class="mx-auto h-12 object-contain" /> --}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Notes -->
        <section class="text-[11px] space-y-1">
            <p class="font-semibold">Notes:</p>
            <ol class="list-decimal pl-5 space-y-1">
                <li>
                    The date items will be removed from site. Up to seven days can be nominated for where multiple
                    exit and
                    re-entry required.
                </li>
                <li>
                    General Manager - Somisy, General Manager – Operations, or General Manager – Sustainability.
                </li>
            </ol>
        </section>

    </main>
</div>