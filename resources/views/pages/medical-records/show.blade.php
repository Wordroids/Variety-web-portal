<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">

        @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="items-center justify-between mb-4">
            <!-- Top: Back + Title + Actions -->
            <div class="flex items-center justify-between">
                <a href="{{ route('medical-records.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                    <i class="fa-solid fa-chevron-left"></i>
                    Back to all Medical Records
                </a>

                <div class="flex gap-2">
                    <a href="{{ route('events.show', $event) }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        <i class="fa-solid fa-eye"></i> View Event
                    </a>
                    <form action="{{ route('medical-records.destroy', $event) }}"
                          method="POST"
                          onsubmit="return confirm('Are you sure you want to delete these records?');">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-50">
                            <i class="fa-solid fa-trash"></i> Delete Records
                        </button>
                    </form>
                </div>
            </div>
            <!-- Title + subtitle -->
            <div class="mt-2">
                <h1 class="text-xl md:text-2xl font-bold text-gray-900">Medical Records</h1>
                <p class="text-gray-500 text-sm">For {{ $event->title }} participants</p>
            </div>

        </div>

        {{-- <div class="mb-4 flex gap-4">
            <form method="GET" class="flex-1">
                <input name="q" value="{{ request('q') }}"
                    placeholder="Search by name, vehicle, mobile, or address…"
                    class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" />
            </form>
            <select name="filter" class="rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">
                <option value="">All Records</option>
                <option value="recent">Recent</option>
                <option value="has_allergies">Has Allergies</option>
                <option value="has_medications">On Medications</option>
                <option value="archived">Archived</option>
            </select>
        </div> --}}

        <div class="mb-4 text-sm text-black flex gap-4">
            <div class="mb-1">
                <span class="font-semibold">Import Date:</span>
                <span>{{ $records->first()->imported_at->format('d/m/Y') }}</span>
            </div>
            <div>
                <span class="font-semibold">Destroy Date:</span>
                <span>{{ $records->first()->expires_at->format('d/m/Y') }}</span>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-gray-600 whitespace-nowrap">
                            <th class="px-4 py-3 font-medium">Vehicle</th>
                            <th class="px-4 py-3 font-medium">First Name</th>
                            <th class="px-4 py-3 font-medium">Last Name</th>
                            <th class="px-4 py-3 font-medium">Nickname</th>
                            <th class="px-4 py-3 font-medium">Address 1</th>
                            <th class="px-4 py-3 font-medium">Address 2</th>
                            <th class="px-4 py-3 font-medium">Address 3</th>
                            <th class="px-4 py-3 font-medium">Address 4</th>
                            <th class="px-4 py-3 font-medium">Address 5</th>
                            <th class="px-4 py-3 font-medium">Address 6</th>
                            <th class="px-4 py-3 font-medium">Mobile</th>
                            <th class="px-4 py-3 font-medium">Next Of Kin</th>
                            <th class="px-4 py-3 font-medium">NOK Phone</th>
                            <th class="px-4 py-3 font-medium">NOK Alt Phone</th>
                            <th class="px-4 py-3 font-medium">DOB</th>
                            <th class="px-4 py-3 font-medium">Allergies</th>
                            <th class="px-4 py-3 font-medium">Dietary Requirement</th>
                            <th class="px-4 py-3 font-medium">Past Medical History</th>
                            <th class="px-4 py-3 font-medium">Current Medical History</th>
                            <th class="px-4 py-3 font-medium">Current Medications</th>
                            <th class="px-4 py-3 font-medium">Vehicle Image</th>
                            <th class="px-4 py-3 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($records as $record)
                            @php
                                $content = json_decode($record->content);
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap font-semibold text-gray-900">{{ $content->vehicle ?? '—' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $content->first_name ?? '—' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $content->last_name ?? '—' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $content->nickname ?? '—' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $content->address1 ?? '—' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $content->address2 ?? '—' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $content->address3 ?? '—' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $content->address4 ?? '—' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $content->address5 ?? '—' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $content->address6 ?? '—' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $content->mobile ?? '—' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $content->next_of_kin ?? '—' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $content->nok_phone ?? '—' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $content->nok_alt_phone ?? '—' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $content->dob ?? '—' }}</td>

                                {{-- Truncated fields for better layout --}}
                                <td class="px-4 py-3"><div class="max-w-[200px] truncate" title="{{ $content->allergies ?? '' }}">{{ $content->allergies ?? 'None' }}</div></td>
                                <td class="px-4 py-3"><div class="max-w-[200px] truncate" title="{{ $content->dietary_requirement ?? '' }}">{{ $content->dietary_requirement ?? 'None' }}</div></td>
                                <td class="px-4 py-3"><div class="max-w-[200px] truncate" title="{{ $content->past_medical_history ?? '' }}">{{ $content->past_medical_history ?? 'None' }}</div></td>
                                <td class="px-4 py-3"><div class="max-w-[200px] truncate" title="{{ $content->current_medical_history ?? '' }}">{{ $content->current_medical_history ?? 'None' }}</div></td>
                                <td class="px-4 py-3"><div class="max-w-[200px] truncate" title="{{ $content->current_medications ?? '' }}">{{ $content->current_medications ?? 'None' }}</div></td>

                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if(!empty($content->vehicle_image))
                                        <a href="{{ asset('storage/' . $content->vehicle_image) }}" target="_blank" class="text-blue-600 hover:underline">View Image</a>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <a href="{{ route('medical-records.show-record', [$event, $record]) }}" class="text-red-600 hover:text-red-900 font-medium">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
