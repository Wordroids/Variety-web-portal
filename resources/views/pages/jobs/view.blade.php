<x-app-layout>
    <div
        x-data="{
            importModalOpen: false,
            selectedEventId: @js($event->id),
            selectedEventTitle: @js($event->title),
            permitStoreUrlTemplate: @js(route('events.permits.store', ['event' => '__EVENT__'])),
            openImportModal(id, title) {
                this.selectedEventId = id;
                this.selectedEventTitle = title;
                this.importModalOpen = true;
            },
            closeImportModal() {
                this.importModalOpen = false;
            }
        }"
        x-effect="document.body.classList.toggle('overflow-hidden', importModalOpen)"
        @keydown.escape.window="closeImportModal()"
        class="max-w-7xl mx-auto p-6 space-y-6">
        @if (session('success'))
            <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div>
            <h1 class="text-2xl font-bold text-gray-900">Jobs for {{ $event->title }}</h1>
            <p class="mt-1 text-sm text-gray-500">Displaying all jobs.</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">

            <div class="mb-3 flex flex-wrap items-center gap-2">
                <a href="{{ route('events.index') }}"
                    class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-gray-50">
                    View events
                </a>
                <a href="{{ route('events.edit', $event) }}"
                    class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-gray-50">
                    Edit event
                </a>
                <button type="button"
                    @click="openImportModal({{ $event->id }}, @js($event->title))"
                    class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-gray-50">
                    Import jobs
                </button>
                <form action="{{ route('jobs.destroy', $event) }}" method="POST"
                    onsubmit="return confirm('Delete all jobs for this event?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50">
                        Delete jobs
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-[1600px] w-full border-collapse text-sm text-gray-700">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="border border-gray-200 px-3 py-2 text-left font-semibold">#</th>
                            <th class="border border-gray-200 px-3 py-2 text-left font-semibold">Import Date</th>
                            <th class="border border-gray-200 px-3 py-2 text-left font-semibold">Event Day</th>
                            <th class="border border-gray-200 px-3 py-2 text-left font-semibold">Vehicle</th>
                            <th class="border border-gray-200 px-3 py-2 text-left font-semibold">Duty Code</th>
                            <th class="border border-gray-200 px-3 py-2 text-left font-semibold">Duty Description</th>
                            <th class="border border-gray-200 px-3 py-2 text-left font-semibold">Location</th>
                            <th class="border border-gray-200 px-3 py-2 text-left font-semibold">AM/PM</th>
                            <th class="border border-gray-200 px-3 py-2 text-left font-semibold">KM</th>
                            <th class="border border-gray-200 px-3 py-2 text-left font-semibold">OV Arrive</th>
                            <th class="border border-gray-200 px-3 py-2 text-left font-semibold">Field Arrive</th>
                            <th class="border border-gray-200 px-3 py-2 text-left font-semibold">OV Departure</th>
                            <th class="border border-gray-200 px-3 py-2 text-left font-semibold">Comment</th>
                            <th class="border border-gray-200 px-3 py-2 text-left font-semibold">Vehicle Description</th>
                            <th class="border border-gray-200 px-3 py-2 text-left font-semibold">Image</th>
                            <th class="border border-gray-200 px-3 py-2 text-left font-semibold">Latitude</th>
                            <th class="border border-gray-200 px-3 py-2 text-left font-semibold">Longitude</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobs as $job)
                            @php
                                $importedAt = $job->uploaded_at ? \Carbon\Carbon::parse($job->uploaded_at) : $job->created_at;
                            @endphp
                            <tr class="odd:bg-white even:bg-gray-50">
                                <td class="border border-gray-200 px-3 py-2">{{ $job->id }}</td>
                                <td class="border border-gray-200 px-3 py-2">{{ optional($importedAt)->format('Y-m-d h:i:s A') ?? '-' }}</td>
                                <td class="border border-gray-200 px-3 py-2">-1</td>
                                <td class="border border-gray-200 px-3 py-2">OV</td>
                                <td class="border border-gray-200 px-3 py-2">{{ $job->title }}</td>
                                <td class="border border-gray-200 px-3 py-2">{{ $job->title }}</td>
                                <td class="border border-gray-200 px-3 py-2">-</td>
                                <td class="border border-gray-200 px-3 py-2">{{ optional($importedAt)->format('A') ?? '-' }}</td>
                                <td class="border border-gray-200 px-3 py-2">0</td>
                                <td class="border border-gray-200 px-3 py-2">{{ optional($importedAt)->format('g:i A') ?? '-' }}</td>
                                <td class="border border-gray-200 px-3 py-2">-</td>
                                <td class="border border-gray-200 px-3 py-2">{{ optional($importedAt)->format('g:i A') ?? '-' }}</td>
                                <td class="border border-gray-200 px-3 py-2">-</td>
                                <td class="border border-gray-200 px-3 py-2">{{ $job->filename }}</td>
                                <td class="border border-gray-200 px-3 py-2">
                                    <a href="{{ Storage::url($job->path) }}" target="_blank" class="text-sky-700 hover:text-sky-900">Open</a>
                                </td>
                                <td class="border border-gray-200 px-3 py-2">-</td>
                                <td class="border border-gray-200 px-3 py-2">-</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="17" class="border border-gray-200 px-3 py-10 text-center text-gray-500">
                                    No jobs found for this event.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @include('pages.jobs.import-modal')
    </div>
</x-app-layout>
