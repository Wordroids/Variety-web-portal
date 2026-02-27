<x-app-layout>
    <div class="max-w-7xl mx-auto p-6" x-data="medicalRecordsPage()">

        <!-- Success Message -->
        @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('success') }}
        </div>
        @endif

        <!-- Error Message -->
        @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
            <ul class="list-disc pl-5 text-sm">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Medical Records</h1>
                <p class="text-sm text-gray-500 mt-1">Displaying all medical records</p>
            </div>

            <button @click="openModal()" 
                class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                <i class="fa-solid fa-file-medical"></i> Add Medical Record
            </button>
        </div>

        <!-- Search and Filters -->
        <div class="mb-4 flex gap-4">
            <form method="GET" class="flex-1">
                <input name="q" value="{{ request('q') }}" placeholder="Search by name, vehicle, mobile, or address…"
                    class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" />
            </form>
            <select name="filter" class="rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">
                <option value="">All Records</option>
                <option value="recent">Recent</option>
                <option value="has_allergies">Has Allergies</option>
                <option value="has_medications">On Medications</option>
                <option value="archived">Archived</option>
            </select>
        </div>

        <!-- Action Buttons (Top) -->
        <div class="mb-4 flex gap-3">
            <button class="inline-flex items-center gap-2 rounded-lg bg-white border border-red-600 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">
                <i class="fa-solid fa-eye"></i> View Events
            </button>
            <button class="inline-flex items-center gap-2 rounded-lg bg-white border border-red-600 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">
                <i class="fa-solid fa-edit"></i> Edit Events
            </button>
            <button class="inline-flex items-center gap-2 rounded-lg bg-white border border-red-600 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">
                <i class="fa-solid fa-file-import"></i> Import Records
            </button>
            <button class="inline-flex items-center gap-2 rounded-lg bg-white border border-red-600 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">
                <i class="fa-solid fa-trash"></i> Delete Records
            </button>
        </div>

        <!-- Import and Destroy Dates -->
        <div class="mb-4 text-sm text-black">
            <div class="mb-1">
                <span class="font-semibold">Import Date:</span>
                <span>{{ date('d/m/Y H:i:s') }}</span>
            </div>
            <div>
                <span class="font-semibold">Destroy Date:</span>
                <span>{{ date('d/m/Y H:i:s') }}</span>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-gray-600">
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
                            <th class="px-4 py-3 font-medium">Comments</th>
                            <th class="px-4 py-3 font-medium">Images</th>
                            <th class="px-4 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <!-- Empty State -->
                        <template x-if="records.length === 0">
                            <tr>
                                <td colspan="24" class="px-4 py-8 text-center text-gray-500">
                                    <i class="fa-solid fa-file-medical text-4xl mb-2 text-gray-300"></i>
                                    <p>No medical records found</p>
                                    <p class="text-xs mt-1">Start by adding a new medical record</p>
                                </td>
                            </tr>
                        </template>

                        <!-- Records -->
                        <template x-for="record in records" :key="record.id">
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-gray-900" x-text="record.vehicle"></div>
                                </td>
                                <td class="px-4 py-3" x-text="record.first_name"></td>
                                <td class="px-4 py-3" x-text="record.last_name"></td>
                                <td class="px-4 py-3" x-text="record.nickname || '—'"></td>
                                <td class="px-4 py-3" x-text="record.address1 || '—'"></td>
                                <td class="px-4 py-3" x-text="record.address2 || '—'"></td>
                                <td class="px-4 py-3" x-text="record.address3 || '—'"></td>
                                <td class="px-4 py-3" x-text="record.address4 || '—'"></td>
                                <td class="px-4 py-3" x-text="record.address5 || '—'"></td>
                                <td class="px-4 py-3" x-text="record.address6 || '—'"></td>
                                <td class="px-4 py-3" x-text="record.mobile || '—'"></td>
                                <td class="px-4 py-3" x-text="record.next_of_kin || '—'"></td>
                                <td class="px-4 py-3" x-text="record.nok_phone || '—'"></td>
                                <td class="px-4 py-3" x-text="record.nok_alt_phone || '—'"></td>
                                <td class="px-4 py-3" x-text="record.dob || '—'"></td>
                                <td class="px-4 py-3">
                                    <div class="max-w-[200px] truncate" x-text="record.allergies || '—'"></div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="max-w-[200px] truncate" x-text="record.dietary_requirement || '—'"></div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="max-w-[200px] truncate" x-text="record.past_medical_history || '—'"></div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="max-w-[200px] truncate" x-text="record.current_medical_history || '—'"></div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="max-w-[200px] truncate" x-text="record.current_medications || '—'"></div>
                                </td>
                                <td class="px-4 py-3">
                                    <template x-if="record.vehicle_image">
                                        <img :src="record.vehicle_image" alt="Vehicle" class="w-10 h-10 object-cover rounded">
                                    </template>
                                    <template x-if="!record.vehicle_image">
                                        <span class="text-gray-400">—</span>
                                    </template>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="max-w-[200px] truncate" x-text="record.comments || '—'"></div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-1">
                                        <template x-for="img in record.images" :key="img">
                                            <img :src="img" alt="Image" class="w-8 h-8 object-cover rounded">
                                        </template>
                                        <template x-if="record.images.length === 0">
                                            <span class="text-gray-400">—</span>
                                        </template>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <button class="text-blue-600 hover:text-blue-800">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button class="text-green-600 hover:text-green-800">
                                            <i class="fa-solid fa-edit"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-800">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Action Buttons (Bottom) -->
        <div class="mt-4 flex gap-3">
            <button class="inline-flex items-center gap-2 rounded-lg bg-white border border-red-600 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">
                <i class="fa-solid fa-eye"></i> View Events
            </button>
            <button class="inline-flex items-center gap-2 rounded-lg bg-white border border-red-600 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">
                <i class="fa-solid fa-edit"></i> Edit Events
            </button>
            <button class="inline-flex items-center gap-2 rounded-lg bg-white border border-red-600 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">
                <i class="fa-solid fa-file-import"></i> Import Records
            </button>
            <button class="inline-flex items-center gap-2 rounded-lg bg-white border border-red-600 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">
                <i class="fa-solid fa-trash"></i> Delete Records
            </button>
        </div>

        {{-- Pagination will go here --}}
        {{-- {{ $records->links() }} --}}

        <!-- Add Medical Record Modal -->
        <div x-show="showModal" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="modal-title" 
            role="dialog" 
            aria-modal="true">
            
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                @click="closeModal()"></div>

            <!-- Modal panel -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-lg bg-white shadow-xl transition-all w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                    <div class="bg-white px-6 pt-5 pb-4">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6 border-b pb-3">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fa-solid fa-file-medical text-red-600"></i> Upload Medical Record
                            </h3>
                            <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-times text-xl"></i>
                            </button>
                        </div>

                        <!-- Form Component -->
                        @include('pages.medical-records.addMedicalRecord')
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function medicalRecordsPage() {
            return {
                showModal: false,
                records: [],
                events: [], // Will be populated from server
                form: {
                    event_id: '',
                    csv_filename: '',
                    csv_file: null,
                    destroy_date: '',
                    acknowledge: false
                },

                openModal() {
                    this.showModal = true;
                    document.body.style.overflow = 'hidden';
                    this.loadEvents();
                },

                closeModal() {
                    this.showModal = false;
                    document.body.style.overflow = 'auto';
                    this.resetForm();
                },

                resetForm() {
                    this.form = {
                        event_id: '',
                        csv_filename: '',
                        csv_file: null,
                        destroy_date: '',
                        acknowledge: false
                    };
                },

                loadEvents() {
                    // Fetch events from server
                    fetch('{{ route("events.list") }}')
                        .then(response => response.json())
                        .then(data => {
                            this.events = data;
                        })
                        .catch(error => console.error('Error loading events:', error));
                },

                handleCsvFile(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.form.csv_file = file;
                        this.form.csv_filename = file.name;
                    }
                },

                uploadMedicalRecords() {
                    const formData = new FormData();
                    formData.append('event_id', this.form.event_id);
                    formData.append('csv_file', this.form.csv_file);
                    formData.append('destroy_date', this.form.destroy_date);
                    formData.append('acknowledge', this.form.acknowledge);

                    fetch('{{ route("medical-records.upload") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error uploading medical records');
                    });

                    this.closeModal();
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
