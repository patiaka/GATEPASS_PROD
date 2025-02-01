<div>

    <div class="card p-3">

        <h2 class="text-center mb-4">Car Request Details</h2>

        <!-- Document Details -->
        <div class="mb-4 d-flex justify-content-between">
            <div>
                <p><strong>Document No:</strong> {{ $carRequest->reference }}</p>
                <p><strong>Title:</strong> Resident & Vehicle Off-Site Travel Approval</p>
                <p><strong>Revision:</strong> 2.0</p>
                <p><strong>Date:</strong> {{ $carRequest->created_at }}</p>
            </div>
            <div>
                <x-button-print href="{{ route('car.print', ['car' => $carRequest]) }}" :row="$carRequest" />
            </div>
        </div>

        <!-- Vehicle and Resident Details -->
        <table class="table table-bordered">
            <thead class="table-secondary">
                <tr>
                    <th>SOMISY VEHICLE</th>
                    <th>CAMP RESIDENT</th>
                    <th>EXPATRIATE</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $carRequest->somisy_car }}</td>
                    <td>{{ $carRequest->resident }}</td>
                    <td>{{ $carRequest->expatriate }}</td>
                </tr>
            </tbody>
        </table>

        {{-- driver list --}}
        <h5 class="mt-4">Driver list</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>name</th>
                    <th>contact</th>
                </tr>
            </thead>
            <tbody>
                @foreach($carRequest->loadMissing('car_drivers')->car_drivers as $row)
                <tr>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->contact }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- passengers list --}}
        <h5 class="mt-4">Passengers list</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>name</th>
                    <th>contact</th>
                </tr>
            </thead>
            <tbody>
                @foreach($carRequest->loadMissing('passengers')->passengers as $row)
                <tr>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->contact }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Vehicle -->
        <h5 class="mt-4">Vehicle infos</h5>
        <table class="table table-bordered">

            <tr>
                <th>Licence(s)</th>
                <td>
                    Mali DL: {{ $carRequest->licence === 'Mali DL' ? 'Yes' : 'No' }} <br>
                    Foreign DL: {{ $carRequest->licence === 'Foreign DL' ? 'Yes' : 'No' }} <br>
                    Intl Permit: {{ $carRequest->licence === 'Intl Permit' ? 'Yes' : 'No' }}
                </td>
            </tr>
            <tr>
                <th>Vehicle Type</th>
                <td>{{ $carRequest->car_type }}</td>
            </tr>
            <tr>
                <th>Vehicle number</th>
                <td>{{ $carRequest->car_number }}</td>
            </tr>
            <tr>
                <th>Department/Company</th>
                <td>{{ $carRequest->user->department->name }}</td>
            </tr>
        </table>

        <!-- Journey Details -->
        <h5 class="mt-4">Journey</h5>
        <table class="table table-bordered">
            <tr>
                <th>Date Valid From</th>
                <td>{{ $carRequest->start_format }}</td>
            </tr>
            <tr>
                <th>Date Until</th>
                <td>{{ $carRequest->end_format }}</td>
            </tr>
            <tr>
                <th>Departure Time</th>
                <td>{{ $carRequest->depart_at }}</td>
            </tr>
            <tr>
                <th>Arrival Time</th>
                <td>{{ $carRequest->arrive_at }}</td>
            </tr>
            <tr>
                <th>Destination(s)</th>
                <td>{{ $carRequest->destination }}</td>
            </tr>
            <tr>
                <th>Justification</th>
                <td>{{ $carRequest->justification }}</td>
            </tr>
        </table>

        <!-- Security Use -->
        <h5 class="mt-4">Security Use Only</h5>
        <table class="table table-bordered">
            <tr>
                <th>Security Supervisor Name</th>
                <td>{{ $carRequest->security_supervisor_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Escort</th>
                <td>{{ $carRequest->escort_level ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>GPS</th>
                <td>{{ $carRequest->gps ?? 'N/A' }}</td>
            </tr>

            <tr>
                <th>Date</th>
                <td>{{ $carRequest->security_date ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Time Out</th>
                <td>{{ $carRequest->time_out ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Time In</th>
                <td>{{ $carRequest->time_in ?? 'N/A' }}</td>
            </tr>
        </table>

        <x-form-request-validate :model="$carRequest" type="car" />

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