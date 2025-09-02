<div>
    <div class="flex justify-between items-center border-b pb-4">
        <div>
            <h1 class="font-medium text-xl">Resident & Vehicle Off Site Details</h1>
            <div class="flex mt-1 items-center">
                <span class="text-sm mr-2">#Request ID | Status:</span>
                <span @class([
                    'flex w-4 h-4 rounded-full shadow -mt-0.5',
                    'bg-red-500 border-red-500' =>
                        $carRequest->isRejected() || $carRequest->isExpired(),
                    'bg-orange-200 border-orange-200' => $carRequest->isPending(),
                    'bg-yellow-400 border-yellow-400' => $carRequest->isProgress(), // adjust if you mean "bg-warning"
                ])>
                </span>
                <span class="text-sm font-semibold ml-1">{{ $carRequest->status }}</span>
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('car.index') }}" class="btn-secondary flex items-center gap-1 border rounded">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to List
            </a>


            <!-- Edit Button -->
            <a href="{{ route('car.edit', ['CarRequest' => $carRequest]) }}"
                class="btn-secondary flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 4h2M12 20h.01M4 12h.01M20 12h.01M5.636 5.636l.01.01M18.364 18.364l.01.01M5.636 18.364l.01-.01M18.364 5.636l.01-.01M12 8v8m-4-4h8" />
                </svg>
                Edit
            </a>

            <!-- Download Button -->
            <button wire:click="download_pdf({{ $carRequest }})" wire:loading.attr="disabled"
                wire:target="download_pdf"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm flex items-center gap-2">

                <!-- Download icon (when not loading) -->
                <span wire:loading.remove wire:target="download_pdf" class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 4v12" />
                    </svg>
                    Download
                </span>

                <!-- Loading icon (spinner) -->
                <span wire:loading wire:target="download_pdf" class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 animate-spin" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m0 14v1m8-8h1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m0 12.728l.707-.707M17.657 6.343l.707-.707" />
                    </svg>
                    Processing...
                </span>
            </button>
        </div>

    </div>

    <div class="bg-white rounded mt-4 shadow border p-6">
        <table class="table-auto w-full text-sm">
            <tbody class="">
                <tr>
                    <th class="text-left py-2 pr-4">Company</th>
                    <td>{{ $carRequest->company }}</td>
                </tr>
                <tr class="bg-gray-50">
                    <th class="text-left py-2 pr-4">Somisy Vehicle</th>
                    <td>{{ $carRequest->somisy_car }}</td>
                </tr>
                <tr>
                    <th class="text-left py-2 pr-4">Camp Resident</th>
                    <td>{{ $carRequest->resident }}</td>
                </tr>
                <tr class="bg-gray-50">
                    <th class="text-left py-2 pr-4">Expatriate</th>
                    <td>{{ $carRequest->expatriate }}</td>
                </tr>
                @foreach ($carRequest->loadMissing('car_drivers')->car_drivers as $row)
                    <tr>
                        <th class="text-left py-2 pr-4">Drvier Name</th>
                        <td>{{ $row->name }}</td>
                    </tr>
                    <tr class="bg-gray-50">
                        <th class="text-left py-2 pr-4">Phone</th>
                        <td>{{ $row->contact }}</td>
                    </tr>
                @endforeach
                <tr class="bg-gray-50">
                    <th class="text-left py-2 pr-4">Licence</th>
                    <td>{{ $carRequest->licence }}</td>
                </tr>
                <tr>
                    <th class="text-left py-2 pr-4">Vehicle Type</th>
                    <td>{{ $carRequest->car_type }}</td>
                </tr>
                <tr class="bg-gray-50">
                    <th class="text-left py-2 pr-4">Vehicle Number</th>
                    <td>{{ $carRequest->car_number }}</td>
                </tr>
                <tr>
                    <th class="text-left py-2 pr-4">Route</th>
                    <td>{{ $carRequest->route }}</td>
                </tr>
                <tr class="bg-gray-50">
                    <th class="text-left py-2 pr-4">Date Valid From</th>
                    <td>{{ $carRequest->start }}</td>
                </tr>
                <tr>
                    <th class="text-left py-2 pr-4">Date Until</th>
                    <td>{{ $carRequest->end }}</td>
                </tr>
                <tr class="bg-gray-50">
                    <th class="text-left py-2 pr-4">Departure Time</th>
                    <td>{{ $carRequest->depart_at }}</td>
                </tr>
                <tr>
                    <th class="text-left py-2 pr-4">Arrival Time</th>
                    <td>{{ $carRequest->arrive_at }}</td>
                </tr>
                <tr class="bg-gray-50">
                    <th class="text-left py-2 pr-4">Destinations</th>
                    <td>{{ $carRequest->destination }}</td>
                </tr>
                <tr>
                    <th class="text-left py-2 pr-4">Reason for Travel</th>
                    <td>{{ $carRequest->reason }}</td>
                </tr>

                @foreach ($carRequest->loadMissing('passengers')->passengers as $row)
                    <tr class="bg-gray-50">
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
                        <x-request-status :status="$carRequest->getStatusFor('hod')" />
                        <x-form-request :model="$carRequest" type="car" />
                    </td>
                    <td class="px-4 py-2"> {{ $carRequest->hod_comment }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2">2</td>
                    <td class="px-4 py-2">GM</td>
                    <td class="px-4 py-2">
                        <x-request-status :status="$carRequest->getStatusFor('gm')" />
                        <x-form-request :model="$carRequest" type="car" />
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
