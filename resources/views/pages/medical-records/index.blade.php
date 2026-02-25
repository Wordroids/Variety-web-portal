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
                <div class="relative transform overflow-hidden rounded-lg bg-white shadow-xl transition-all w-full max-w-4xl max-h-[90vh] overflow-y-auto">
                    <form @submit.prevent="saveRecord">
                        <div class="bg-white px-6 pt-5 pb-4">
                            <!-- Header -->
                            <div class="flex items-center justify-between mb-4 border-b pb-3">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    <i class="fa-solid fa-file-medical text-red-600"></i> Add Medical Record
                                </h3>
                                <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                                    <i class="fa-solid fa-times text-xl"></i>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Vehicle -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle</label>
                                    <input type="text" x-model="form.vehicle" 
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" 
                                        required>
                                </div>

                                <!-- First Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                    <input type="text" x-model="form.first_name" 
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" 
                                        required>
                                </div>

                                <!-- Last Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                    <input type="text" x-model="form.last_name" 
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" 
                                        required>
                                </div>

                                <!-- Nickname -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nickname</label>
                                    <input type="text" x-model="form.nickname" 
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">
                                </div>

                                <!-- Address 1 -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address 1</label>
                                    <input type="text" x-model="form.address1" 
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">
                                </div>

                                <!-- Address 2 -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address 2</label>
                                    <input type="text" x-model="form.address2" 
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">
                                </div>

                                <!-- Address 3 -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address 3</label>
                                    <input type="text" x-model="form.address3" 
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">
                                </div>

                                <!-- Address 4 -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address 4</label>
                                    <input type="text" x-model="form.address4" 
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">
                                </div>

                                <!-- Address 5 -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address 5</label>
                                    <input type="text" x-model="form.address5" 
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">
                                </div>

                                <!-- Address 6 -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address 6</label>
                                    <input type="text" x-model="form.address6" 
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">
                                </div>

                                <!-- Mobile -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Mobile</label>
                                    <input type="tel" x-model="form.mobile" 
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">
                                </div>

                                <!-- Next Of Kin -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Next Of Kin</label>
                                    <input type="text" x-model="form.next_of_kin" 
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">
                                </div>

                                <!-- NOK Phone -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">NOK Phone</label>
                                    <input type="tel" x-model="form.nok_phone" 
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">
                                </div>

                                <!-- NOK Alt Phone -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">NOK Alt Phone</label>
                                    <input type="tel" x-model="form.nok_alt_phone" 
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">
                                </div>

                                <!-- DOB -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                                    <input type="date" x-model="form.dob" 
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">
                                </div>

                                <!-- Allergies -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Allergies</label>
                                    <textarea x-model="form.allergies" rows="2"
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500"></textarea>
                                </div>

                                <!-- Dietary Requirement -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Dietary Requirement</label>
                                    <textarea x-model="form.dietary_requirement" rows="2"
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500"></textarea>
                                </div>

                                <!-- Past Medical History -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Past Medical History</label>
                                    <textarea x-model="form.past_medical_history" rows="3"
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500"></textarea>
                                </div>

                                <!-- Current Medical History -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Medical History</label>
                                    <textarea x-model="form.current_medical_history" rows="3"
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500"></textarea>
                                </div>

                                <!-- Current Medications -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Medications</label>
                                    <textarea x-model="form.current_medications" rows="3"
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500"></textarea>
                                </div>

                                <!-- Vehicle Image -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Image</label>
                                    <input type="file" @change="handleVehicleImage" accept="image/*"
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">
                                </div>

                                <!-- Images -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Additional Images</label>
                                    <input type="file" @change="handleImages" accept="image/*" multiple
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">
                                </div>

                                <!-- Comments -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Comments</label>
                                    <textarea x-model="form.comments" rows="3"
                                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="bg-gray-50 px-6 py-3 flex justify-end gap-3">
                            <button type="button" @click="closeModal()"
                                class="rounded-lg bg-white border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                                <i class="fa-solid fa-save"></i> Save Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        function medicalRecordsPage() {
            return {
                showModal: false,
                records: [],
                form: {
                    vehicle: '',
                    first_name: '',
                    last_name: '',
                    nickname: '',
                    address1: '',
                    address2: '',
                    address3: '',
                    address4: '',
                    address5: '',
                    address6: '',
                    mobile: '',
                    next_of_kin: '',
                    nok_phone: '',
                    nok_alt_phone: '',
                    dob: '',
                    allergies: '',
                    dietary_requirement: '',
                    past_medical_history: '',
                    current_medical_history: '',
                    current_medications: '',
                    vehicle_image: null,
                    images: [],
                    comments: ''
                },

                openModal() {
                    this.showModal = true;
                    document.body.style.overflow = 'hidden';
                },

                closeModal() {
                    this.showModal = false;
                    document.body.style.overflow = 'auto';
                    this.resetForm();
                },

                resetForm() {
                    this.form = {
                        vehicle: '',
                        first_name: '',
                        last_name: '',
                        nickname: '',
                        address1: '',
                        address2: '',
                        address3: '',
                        address4: '',
                        address5: '',
                        address6: '',
                        mobile: '',
                        next_of_kin: '',
                        nok_phone: '',
                        nok_alt_phone: '',
                        dob: '',
                        allergies: '',
                        dietary_requirement: '',
                        past_medical_history: '',
                        current_medical_history: '',
                        current_medications: '',
                        vehicle_image: null,
                        images: [],
                        comments: ''
                    };
                },

                handleVehicleImage(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.form.vehicle_image = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },

                handleImages(event) {
                    const files = Array.from(event.target.files);
                    files.forEach(file => {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.form.images.push(e.target.result);
                        };
                        reader.readAsDataURL(file);
                    });
                },

                saveRecord() {
                    // Add the record to the records array
                    this.records.push({ ...this.form, id: Date.now() });
                    
                    // Send to server
                    fetch('{{ route("medical-records.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(this.form)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        }
                    })
                    .catch(error => console.error('Error:', error));

                    this.closeModal();
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
