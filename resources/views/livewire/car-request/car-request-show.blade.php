<div>
    <div class="flex justify-between items-center border-b pb-4">
        <div>
            <h1 class="font-medium text-xl">Resident & Vehicle Off Site Details</h1>
            <div class="flex mt-1 items-center">
                <span class="text-sm mr-2">#Request ID | Status:</span>
                <span @class([ 'flex w-4 h-4 rounded-full shadow -mt-0.5' , 'bg-red-500 border-red-500'=>
                    $carRequest->isRejected() || $carRequest->isExpired(),
                    'bg-orange-200 border-orange-200' => $carRequest->isPending(),
                    'bg-yellow-400 border-yellow-400' => $carRequest->isProgress(), // adjust if you mean "bg-warning"
                    ])>
                </span>

                <span class="text-sm font-semibold ml-1">{{ $carRequest->status }}</span>
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('car.index') }}" class="btn-secondary">← Back</a>
            <a href="{{ route('car.edit', ['CarRequest' => $carRequest]) }}" class="btn-secondary">✏️ Edit</a>
            <a href="#" role="button" wire:click="download_pdf({{ $carRequest }})" class="btn-secondary">⬇️ Download</a>
        </div>
    </div>

    <div class="bg-white rounded mt-4 shadow border p-6">
        <table class="table-auto w-full text-sm">
            <tbody class="divide-y">
                <tr>
                    <th class="text-left py-2 pr-4">Company</th>
                    <td>{{ $carRequest->company }}</td>
                </tr>
                <tr>
                    <th class="text-left py-2 pr-4">Somisy Vehicle</th>
                    <td>{{ $carRequest->somisy_car }}</td>
                </tr>
                <tr>
                    <th class="text-left py-2 pr-4">Camp Resident</th>
                    <td>{{ $carRequest->resident }}</td>
                </tr>
                <tr>
                    <th class="text-left py-2 pr-4">Expatriate</th>
                    <td>{{ $carRequest->expatriate }}</td>
                </tr>
                @foreach($carRequest->loadMissing('car_drivers')->car_drivers as $row)
                <tr>
                    <th class="text-left py-2 pr-4">Drvier Name {{ $row->name }}</th>
                    <td>Drvier {{ $row->name }}</td>
                </tr>
                <tr>
                    <th class="text-left py-2 pr-4">Phone {{ $row->contact }}</th>
                    <td>{{ $row->contact }}</td>
                </tr>
                @endforeach
                <tr>
                    <th class="text-left py-2 pr-4">Phone</th>
                    <td>+223 76 12 34 56</td>
                </tr>
                <tr>
                    <th class="text-left py-2 pr-4">Licence</th>
                    <td>{{ $carRequest->licence }}</td>
                </tr>
                <tr>
                    <th class="text-left py-2 pr-4">Vehicle Type</th>
                    <td>{{ $carRequest->car_type }}</td>
                </tr>
                <tr>
                    <th class="text-left py-2 pr-4">Vehicle Number</th>
                    <td>{{ $carRequest->car_number }}</td>
                </tr>
                <tr>
                    <th class="text-left py-2 pr-4">Route</th>
                    <td>{{ $carRequest->route }}</td>
                </tr>
                <tr>
                    <th class="text-left py-2 pr-4">Date Valid From</th>
                    <td>{{ $carRequest->start }}</td>
                </tr>
                <tr>
                    <th class="text-left py-2 pr-4">Date Until</th>
                    <td>{{ $carRequest->end }}</td>
                </tr>
                <tr>
                    <th class="text-left py-2 pr-4">Departure Time</th>
                    <td>{{ $carRequest->depart_at }}</td>
                </tr>
                <tr>
                    <th class="text-left py-2 pr-4">Arrival Time</th>
                    <td>{{ $carRequest->arrive_at }}</td>
                </tr>
                <tr>
                    <th class="text-left py-2 pr-4">Destinations</th>
                    <td>{{ $carRequest->destination }}</td>
                </tr>
                <tr>
                    <th class="text-left py-2 pr-4">Reason for Travel</th>
                    <td>{{ $carRequest->reason }}</td>
                </tr>

                @foreach($carRequest->loadMissing('passengers')->passengers as $row)
                <tr>
                    <th class="text-left py-2 pr-4">Resident Name {{ $row->name }}</th>
                    <td>Resident {{ $row->name }}</td>
                </tr>
                <tr>
                    <th class="text-left py-2 pr-4">Phone {{ $row->contact }}</th>
                    <td>{{ $row->contact }}</td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    {{-- Approval Section --}}
    <h2 class="mt-6 text-lg font-medium">Approvals</h2>
    <div class="bg-white rounded mt-2 shadow border p-4">
        <table class="w-full text-sm table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="w-10 text-left px-4 py-2 font-semibold text-gray-700">#</th>
                    <th class="w-1/4 text-left px-4 py-2 font-semibold text-gray-700">Approver</th>
                    <th class="w-1/4 text-left px-4 py-2 font-semibold text-gray-700">Status</th>
                    <th class="text-left px-4 py-2 font-semibold text-gray-700">Comments</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr>
                    <td class="px-4 py-2">1</td>
                    <td class="px-4 py-2">HOD</td>
                    <td class="px-4 py-2">
                        <x-request-status :model="$carRequest" type="hod" />
                    </td>
                    <td class="px-4 py-2"> {{ $carRequest->hod_comment }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2">2</td>
                    <td class="px-4 py-2">GM</td>
                    <td class="px-4 py-2">
                        <x-request-status :model="$carRequest" type="gm" />
                    </td>
                    <td class="px-4 py-2">{{ $carRequest->gm_comment }}</td>
                </tr>
                {{-- <tr>
                    <td class="px-4 py-2">3</td>
                    <td class="px-4 py-2">Security</td>
                    <td class="px-4 py-2">Pending</td>
                    <td class="px-4 py-2"></td>
                </tr> --}}
            </tbody>
        </table>

    </div>
</div>