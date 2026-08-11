<div class="p-4 md:p-6">

    {{-- Header --}}
    <div class="border-b pb-4 mb-5">
        <h1 class="text-2xl font-bold text-[#134169]">{{ __('Audit log') }}</h1>
        <p class="text-sm text-slate-500 mt-1">{{ __('Logins and changes to requests') }}</p>
    </div>

    {{-- Tabs + search --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <div class="inline-flex rounded-lg border border-gray-300 bg-gray-50 p-0.5 self-start">
            @foreach (['activity' => __('Request changes'), 'logins' => __('Logins')] as $key => $label)
                <button type="button" wire:click="setTab('{{ $key }}')" @class([
                    'px-4 py-1.5 rounded-md text-sm font-medium transition',
                    'bg-[#134169] text-white shadow-sm' => $tab === $key,
                    'text-slate-600 hover:text-slate-900' => $tab !== $key,
                ])>{{ $label }}</button>
            @endforeach
        </div>

        <div class="relative w-full sm:w-72">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3M11 19a8 8 0 100-16 8 8 0 000 16z" />
                </svg>
            </span>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('Search…') }}"
                class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-[#134169]/20 focus:border-[#134169] outline-none">
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr class="uppercase text-xs tracking-wider text-slate-500">
                        @if ($tab === 'logins')
                            <th class="px-4 py-3 text-left font-medium">{{ __('Date') }}</th>
                            <th class="px-4 py-3 text-left font-medium">{{ __('User') }}</th>
                            <th class="px-4 py-3 text-left font-medium">{{ __('IP address') }}</th>
                            <th class="px-4 py-3 text-left font-medium">{{ __('Device') }}</th>
                        @else
                            <th class="px-4 py-3 text-left font-medium">{{ __('Date') }}</th>
                            <th class="px-4 py-3 text-left font-medium">{{ __('User') }}</th>
                            <th class="px-4 py-3 text-left font-medium">{{ __('Type') }}</th>
                            <th class="px-4 py-3 text-left font-medium">{{ __('Reference') }}</th>
                            <th class="px-4 py-3 text-left font-medium">{{ __('Event') }}</th>
                            <th class="px-4 py-3 text-left font-medium">{{ __('Changes') }}</th>
                        @endif
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-100 text-sm">
                    @forelse ($rows as $row)
                        <tr wire:key="log-{{ $tab }}-{{ $row->id }}" class="hover:bg-slate-50 align-top">
                            @if ($tab === 'logins')
                                <td class="px-4 py-3 text-slate-600 whitespace-nowrap">{{ $row->created_at?->format('d-m-Y H:i') }}</td>
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $row->user_name ?? '—' }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $row->ip_address ?? '—' }}</td>
                                <td class="px-4 py-3 text-slate-500 max-w-[320px] truncate" title="{{ $row->user_agent }}">{{ $row->user_agent ?? '—' }}</td>
                            @else
                                <td class="px-4 py-3 text-slate-600 whitespace-nowrap">{{ $row->created_at?->format('d-m-Y H:i') }}</td>
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $row->causer_name ?? __('System') }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px] font-medium">
                                        {{ $row->subject_type === 'CarRequest' ? __('Vehicle') : __('Material') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-[#134169] font-medium">{{ $row->subject_ref ?? '#'.$row->subject_id }}</td>
                                <td class="px-4 py-3">
                                    <span @class([
                                        'inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold ring-1',
                                        'bg-emerald-50 text-emerald-700 ring-emerald-200' => $row->event === 'created',
                                        'bg-blue-50 text-blue-700 ring-blue-200' => $row->event === 'updated',
                                        'bg-rose-50 text-rose-700 ring-rose-200' => $row->event === 'deleted',
                                    ])>{{ __(ucfirst($row->event)) }}</span>
                                </td>
                                <td class="px-4 py-3 text-slate-500 max-w-[360px]">
                                    @if (! empty($row->changes))
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($row->changes as $field => $value)
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-slate-50 border border-slate-100 text-[11px]">
                                                    <span class="font-medium text-slate-600">{{ $field }}</span>
                                                    <span class="text-slate-400">{{ \Illuminate\Support\Str::limit(is_scalar($value) ? (string) $value : json_encode($value), 24) }}</span>
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-slate-400 text-sm">{{ __('No entry.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($rows->hasPages())
            <div class="p-4">{{ $rows->links() }}</div>
        @endif
    </div>
</div>
