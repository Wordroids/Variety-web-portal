<div x-show="importModalOpen" x-transition class="fixed inset-0 z-50" style="display: none;">
    <div class="absolute inset-0 bg-black/40" @click="closeImportModal()"></div>

    <div class="relative flex min-h-full items-center justify-center p-4">
        <div class="w-full max-w-3xl rounded-xl border border-gray-200 bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Import Jobs</h2>
                <button type="button" @click="closeImportModal()"
                    class="inline-flex h-8 w-8 items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST"
                :action="selectedEventId ? permitStoreUrlTemplate.replace('__EVENT__', selectedEventId) : '#'
                "
                enctype="multipart/form-data"
                class="space-y-5 px-6 pt-2 pb-5">
                @csrf

                <p class="text-sm text-gray-500">Select an event to upload jobs.</p>

                <div class="grid gap-6 md:grid-cols-[170px_minmax(0,1fr)] md:items-start">
                    <label for="relatedEvent" class="text-sm font-medium text-gray-700 md:pt-2">Related event</label>
                    <select id="relatedEvent" name="event_id" x-model="selectedEventId" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-200">
                        <option value="" disabled>Select event</option>
                        @foreach($events as $eventOption)
                            <option value="{{ $eventOption->id }}">{{ $eventOption->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid gap-6 md:grid-cols-[170px_minmax(0,1fr)] md:items-start">
                    <label for="jobsFile" class="text-sm font-medium text-gray-700 md:pt-2">CSV File</label>
                    <div>
                        <input id="jobsFile" name="file" type="file" accept=".csv,.txt" required
                            class="block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border file:border-gray-300 file:bg-white file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-50" />
                        <p class="mt-2 text-sm text-gray-500">Please select a .csv or .txt file.</p>
                        <button type="button" class="mt-1 text-sm font-medium text-red-600 hover:text-red-700 hover:underline">
                            Download template
                        </button>
                    </div>
                </div>

                <input type="hidden" name="title" :value="selectedEventTitle ? 'Jobs import - ' + selectedEventTitle : 'Jobs import'">

                <label class="flex items-start gap-2 text-sm text-gray-600">
                    <input type="checkbox" required class="mt-0.5 h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500" />
                    <span>I understand that by submitting this form any existing jobs for the selected event will be deleted</span>
                </label>

                <div>
                    <button type="submit"
                        class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
