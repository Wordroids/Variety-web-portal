<div class="space-y-6">
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900">
            Select an event to upload medical records
        </h3>
    </div>

    <form @submit.prevent="uploadMedicalRecords" class="space-y-6">
        <!-- Related Event Dropdown -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Related Event</label>
            <select x-model="form.event_id"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-red-500 focus:ring-red-500"
                required>
                <option value="">-- Select an Event --</option>
                <template x-for="event in events" :key="event.id">
                    <option :value="event.id" x-text="event.title"></option>
                </template>
            </select>
        </div>

        <!-- CSV File Upload -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">CSV File</label>
            <div class="space-y-2">
                <button type="button" @click="$refs.csvInput.click()"
                    class="inline-flex items-center gap-2 rounded-lg bg-white border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <i class="fa-solid fa-folder-open"></i> Browse
                </button>
                <input type="file" x-ref="csvInput" @change="handleCsvFile" accept=".csv,.txt" class="hidden">
                <p class="text-sm text-gray-600">Please select a .csv or .txt file.</p>
                <a href="#" class="text-sm text-red-600 hover:text-red-700 font-medium">
                    <i class="fa-solid fa-download"></i> Download template
                </a>
            </div>
            <template x-if="form.csv_filename">
                <div class="mt-2 text-sm text-green-600">
                    <i class="fa-solid fa-check-circle"></i> <span x-text="form.csv_filename"></span>
                </div>
            </template>
        </div>

        <!-- Destroy Date -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Destroy Date</label>
            <input type="date" x-model="form.destroy_date" placeholder="yyyy-mm-dd"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-red-500 focus:ring-red-500"
                required>
            <p class="text-xs text-gray-500 mt-2">
                <span class="text-gray-400">(yyyy-mm-dd)</span> A future date which these records will be automatically
                destroyed and no longer available.
            </p>
        </div>

        <div class="flex items-start gap-3 pt-4 border-t">
            <input type="checkbox" x-model="form.acknowledge" id="acknowledge"
                class="mt-1 rounded border-gray-300 focus:border-red-500 focus:ring-red-500" required>
            <label for="acknowledge" class="text-sm text-gray-700">
                I understand that by submitting this form any existing medical records for the selected event will be
                deleted.
            </label>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t">
            <button type="button" @click="closeModal()"
                class="rounded-lg bg-white border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </button>
            <button type="submit"
                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="!form.event_id || !form.csv_filename || !form.destroy_date || !form.acknowledge">
                <i class="fa-solid fa-upload"></i> Upload Medical Records
            </button>
        </div>
    </form>
</div>
