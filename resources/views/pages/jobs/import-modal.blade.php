<div x-show="importModalOpen"
     class="fixed inset-0 z-50 overflow-y-auto"
     x-cloak>

    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" @click="closeImportModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4">
        <div x-show="importModalOpen"
             class="relative w-full max-w-xl overflow-hidden rounded-xl bg-white shadow-2xl transition-all">

            <div class="p-6">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Import Jobs</h3>
                    <p class="text-sm text-gray-500">Upload a CSV file to associate jobs with an event.</p>
                </div>

                <form method="POST"
                      action="{{ route('jobs.store') }}"
                      enctype="multipart/form-data"
                      class="space-y-5">
                    @csrf

                    <div>
                        <label for="relatedEvent" class="block text-sm font-medium text-gray-700 mb-1">Related Event</label>
                        <select id="relatedEvent" name="event_id" x-model="selectedEventId" required
                                class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500 text-sm">
                            <option value="" disabled>-- Select an Event --</option>
                            @foreach($events as $eventOption)
                                <option value="{{ $eventOption->id }}">{{ $eventOption->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CSV File</label>
                        <div class="rounded-lg border-2 border-dashed border-gray-300 p-4 text-center">
                            <input id="jobsFile" name="file" type="file" accept=".csv,.txt" required
                                   class="hidden" x-ref="jobFileInput"
                                   @change="jobFileName = $event.target.files[0] ? $event.target.files[0].name : ''">

                            <button type="button" @click="$refs.jobFileInput.click()"
                                    class="inline-flex items-center gap-2 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                <i class="fa-solid fa-folder-open text-gray-400"></i> Choose File
                            </button>

                            <p class="mt-2 text-xs text-gray-500" x-text="jobFileName || 'No file selected (.csv or .txt)'"></p>
                        </div>

                        <div class="mt-2">
                            <button type="button" class="text-xs text-red-600 hover:text-red-700 font-medium underline">
                                Download template
                            </button>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <input type="checkbox" id="acknowledge_delete" x-model="jobAcknowledge" required
                               class="mt-1 h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <label for="acknowledge_delete" class="text-xs text-gray-600 leading-relaxed">
                            I understand that submitting this form will **overwrite** any existing jobs for the selected event.
                        </label>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" @click="closeImportModal()"
                                class="rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 transition">
                            Cancel
                        </button>
                        <button type="submit"
                                class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition"
                                :disabled="!selectedEventId || !jobFileName || !jobAcknowledge">
                            <i class="fa-solid fa-upload mr-1"></i> Start Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
