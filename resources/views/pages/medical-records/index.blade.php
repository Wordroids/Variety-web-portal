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
                <i class="fa-solid fa-file-medical"></i> Import Medical Record
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
                            <th class="px-4 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <!-- Empty State -->
                        <template x-if="records.length === 0">
                            <tr>
                                <td colspan="22" class="px-4 py-8 text-center text-gray-500">
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
                                    <div class="flex justify-end gap-2">
                                        <button @click="openCommentsModal(record)" class="text-purple-600 hover:text-purple-800" title="Comments">
                                            <i class="fa-solid fa-comment"></i>
                                        </button>
                                        <button @click="openImagesModal(record)" class="text-orange-600 hover:text-orange-800" title="Images">
                                            <i class="fa-solid fa-image"></i>
                                        </button>
                                        <button class="text-blue-600 hover:text-blue-800" title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button class="text-green-600 hover:text-green-800" title="Edit">
                                            <i class="fa-solid fa-edit"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-800" title="Delete">
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

        <!-- View Events Modal -->
        <div x-show="showEventsModal" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="events-modal-title" 
            role="dialog" 
            aria-modal="true">
            
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                @click="closeEventsModal()"></div>

            <!-- Modal panel -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-lg bg-white shadow-xl transition-all w-full max-w-6xl max-h-[90vh] overflow-y-auto">
                    <div class="bg-white px-6 pt-5 pb-4">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6 border-b pb-3">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fa-solid fa-calendar-alt text-red-600"></i> Events Details
                            </h3>
                            <button type="button" @click="closeEventsModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-times text-xl"></i>
                            </button>
                        </div>

                        <!-- Loading State -->
                        <div x-show="loadingEvents" class="text-center py-12">
                            <i class="fa-solid fa-spinner fa-spin text-4xl text-red-600 mb-4"></i>
                            <p class="text-gray-600">Loading events...</p>
                        </div>

                        <!-- Events List -->
                        <div x-show="!loadingEvents">
                            <!-- No Events State -->
                            <template x-if="eventsData.length === 0">
                                <div class="text-center py-12">
                                    <i class="fa-solid fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-600 text-lg">No events found</p>
                                    <p class="text-gray-500 text-sm mt-2">There are currently no events available</p>
                                </div>
                            </template>

                            <!-- Events Grid -->
                            <div class="grid gap-6">
                                <template x-for="event in eventsData" :key="event.id">
                                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                                        <!-- Event Header with Cover Image -->
                                        <div class="relative h-48 bg-gradient-to-r from-red-500 to-red-600">
                                            <template x-if="event.cover_image_path">
                                                <img :src="event.cover_image_path" :alt="event.title" 
                                                     class="w-full h-full object-cover">
                                            </template>
                                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-end">
                                                <div class="p-6 text-white w-full">
                                                    <h4 class="text-2xl font-bold mb-2" x-text="event.title"></h4>
                                                    <div class="flex items-center gap-4 text-sm">
                                                        <span>
                                                            <i class="fa-solid fa-calendar"></i>
                                                            <span x-text="formatDate(event.start_date)"></span>
                                                        </span>
                                                        <span>
                                                            <i class="fa-solid fa-calendar-check"></i>
                                                            <span x-text="formatDate(event.end_date)"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Event Details -->
                                        <div class="p-6">
                                            <div class="grid md:grid-cols-2 gap-6">
                                                <!-- Left Column -->
                                                <div class="space-y-4">
                                                    <!-- Description -->
                                                    <div>
                                                        <h5 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                                            <i class="fa-solid fa-align-left text-red-600"></i>
                                                            Description
                                                        </h5>
                                                        <p class="text-gray-600 text-sm" x-text="event.description || 'No description available'"></p>
                                                    </div>

                                                    <!-- Event Days -->
                                                    <div>
                                                        <h5 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                                            <i class="fa-solid fa-calendar-day text-red-600"></i>
                                                            Event Days
                                                        </h5>
                                                        <template x-if="event.days && event.days.length > 0">
                                                            <div class="space-y-2">
                                                                <template x-for="day in event.days" :key="day.id">
                                                                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                                                        <div class="flex items-center justify-between">
                                                                            <span class="font-medium text-gray-800" x-text="day.name"></span>
                                                                            <span class="text-sm text-gray-600" x-text="formatDate(day.date)"></span>
                                                                        </div>
                                                                        <template x-if="day.description">
                                                                            <p class="text-sm text-gray-600 mt-1" x-text="day.description"></p>
                                                                        </template>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                        </template>
                                                        <template x-if="!event.days || event.days.length === 0">
                                                            <p class="text-gray-500 text-sm">No event days configured</p>
                                                        </template>
                                                    </div>
                                                </div>

                                                <!-- Right Column -->
                                                <div class="space-y-4">
                                                    <!-- Sponsor Image -->
                                                    <div>
                                                        <h5 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                                            <i class="fa-solid fa-handshake text-red-600"></i>
                                                            Sponsor
                                                        </h5>
                                                        <template x-if="event.sponsor_image_path">
                                                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                                                <img :src="event.sponsor_image_path" alt="Sponsor" 
                                                                     class="max-h-32 object-contain">
                                                            </div>
                                                        </template>
                                                        <template x-if="!event.sponsor_image_path">
                                                            <p class="text-gray-500 text-sm">No sponsor image available</p>
                                                        </template>
                                                    </div>

                                                    <!-- Participants Count -->
                                                    <div>
                                                        <h5 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                                            <i class="fa-solid fa-users text-red-600"></i>
                                                            Participants
                                                        </h5>
                                                        <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                                                            <div class="flex items-center justify-between">
                                                                <span class="text-gray-700">Total Participants</span>
                                                                <span class="text-2xl font-bold text-red-600" 
                                                                      x-text="event.participants_count || 0"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Event Status -->
                                                    <div>
                                                        <h5 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                                            <i class="fa-solid fa-info-circle text-red-600"></i>
                                                            Status
                                                        </h5>
                                                        <div class="flex gap-2">
                                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                                                                  :class="getEventStatusClass(event)">
                                                                <i class="fa-solid fa-circle text-xs mr-1"></i>
                                                                <span x-text="getEventStatus(event)"></span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comments Modal -->
        <div x-show="showCommentsModal" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="comments-modal-title" 
            role="dialog" 
            aria-modal="true">
            
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                @click="closeCommentsModal()"></div>

            <!-- Modal panel -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-lg bg-white shadow-xl transition-all w-full max-w-2xl">
                    <div class="bg-white px-6 pt-5 pb-4">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6 border-b pb-3">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fa-solid fa-comment text-purple-600"></i> Comments
                            </h3>
                            <button type="button" @click="closeCommentsModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-times text-xl"></i>
                            </button>
                        </div>

                        <!-- Comments Content -->
                        <div class="space-y-4">
                            <!-- Record Info -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-semibold text-gray-700">Vehicle:</span>
                                        <span class="text-gray-900 ml-2" x-text="selectedRecord?.vehicle || 'N/A'"></span>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-700">Name:</span>
                                        <span class="text-gray-900 ml-2" x-text="(selectedRecord?.first_name || '') + ' ' + (selectedRecord?.last_name || '')"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Comments Display -->
                            <div class="bg-white rounded-lg border border-gray-200 p-4 min-h-[200px]">
                                <template x-if="selectedRecord?.comments && selectedRecord.comments !== ''">
                                    <div class="prose max-w-none">
                                        <p class="text-gray-700 whitespace-pre-wrap" x-text="selectedRecord.comments"></p>
                                    </div>
                                </template>
                                <template x-if="!selectedRecord?.comments || selectedRecord.comments === ''">
                                    <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                                        <i class="fa-solid fa-comment-slash text-4xl mb-3"></i>
                                        <p class="text-sm">No comments available</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="mt-6 flex justify-end">
                            <button @click="closeCommentsModal()" 
                                class="rounded-lg bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-300">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Images Modal -->
        <div x-show="showImagesModal" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="images-modal-title" 
            role="dialog" 
            aria-modal="true">
            
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                @click="closeImagesModal()"></div>

            <!-- Modal panel -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-lg bg-white shadow-xl transition-all w-full max-w-4xl">
                    <div class="bg-white px-6 pt-5 pb-4">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6 border-b pb-3">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fa-solid fa-image text-orange-600"></i> Images
                            </h3>
                            <button type="button" @click="closeImagesModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-times text-xl"></i>
                            </button>
                        </div>

                        <!-- Images Content -->
                        <div class="space-y-4">
                            <!-- Record Info -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-semibold text-gray-700">Vehicle:</span>
                                        <span class="text-gray-900 ml-2" x-text="selectedRecord?.vehicle || 'N/A'"></span>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-700">Name:</span>
                                        <span class="text-gray-900 ml-2" x-text="(selectedRecord?.first_name || '') + ' ' + (selectedRecord?.last_name || '')"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Images Display -->
                            <div class="bg-white rounded-lg border border-gray-200 p-4 min-h-[300px]">
                                <template x-if="selectedRecord?.images && selectedRecord.images.length > 0">
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        <template x-for="(image, index) in selectedRecord.images" :key="index">
                                            <div class="relative group">
                                                <img :src="image" :alt="'Image ' + (index + 1)" 
                                                     class="w-full h-48 object-cover rounded-lg border border-gray-200 hover:shadow-lg transition-shadow cursor-pointer"
                                                     @click="viewFullImage(image)">
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-opacity rounded-lg"></div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="!selectedRecord?.images || selectedRecord.images.length === 0">
                                    <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                                        <i class="fa-solid fa-images text-4xl mb-3"></i>
                                        <p class="text-sm">No images available</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="mt-6 flex justify-end">
                            <button @click="closeImagesModal()" 
                                class="rounded-lg bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-300">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function medicalRecordsPage() {
            return {
                showModal: false,
                showEventsModal: false,
                showCommentsModal: false,
                showImagesModal: false,
                loadingEvents: false,
                records: [],
                events: [], // Will be populated from server
                eventsData: [], // For events modal
                selectedRecord: null, // For comments and images modals
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
                },

                // Events Modal Functions
                openEventsModal() {
                    this.showEventsModal = true;
                    document.body.style.overflow = 'hidden';
                    this.loadEventDetails();
                },

                closeEventsModal() {
                    this.showEventsModal = false;
                    document.body.style.overflow = 'auto';
                },

                // Comments Modal Functions
                openCommentsModal(record) {
                    this.selectedRecord = record;
                    this.showCommentsModal = true;
                    document.body.style.overflow = 'hidden';
                },

                closeCommentsModal() {
                    this.showCommentsModal = false;
                    document.body.style.overflow = 'auto';
                    this.selectedRecord = null;
                },

                // Images Modal Functions
                openImagesModal(record) {
                    this.selectedRecord = record;
                    this.showImagesModal = true;
                    document.body.style.overflow = 'hidden';
                },

                closeImagesModal() {
                    this.showImagesModal = false;
                    document.body.style.overflow = 'auto';
                    this.selectedRecord = null;
                },

                viewFullImage(imageUrl) {
                    // Open image in new tab for full view
                    window.open(imageUrl, '_blank');
                },

                loadEventDetails() {
                    this.loadingEvents = true;
                    
                    // Fetch events with full details from server
                    fetch('{{ route("events.list") }}')
                        .then(response => response.json())
                        .then(data => {
                            this.eventsData = data;
                            this.loadingEvents = false;
                        })
                        .catch(error => {
                            console.error('Error loading events:', error);
                            this.loadingEvents = false;
                            alert('Failed to load events. Please try again.');
                        });
                },

                formatDate(date) {
                    if (!date) return 'N/A';
                    const dateObj = new Date(date);
                    return dateObj.toLocaleDateString('en-GB', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });
                },

                getEventStatus(event) {
                    const now = new Date();
                    const startDate = new Date(event.start_date);
                    const endDate = new Date(event.end_date);
                    
                    if (now < startDate) {
                        return 'Upcoming';
                    } else if (now > endDate) {
                        return 'Completed';
                    } else {
                        return 'Ongoing';
                    }
                },

                getEventStatusClass(event) {
                    const status = this.getEventStatus(event);
                    
                    switch(status) {
                        case 'Upcoming':
                            return 'bg-blue-100 text-blue-800';
                        case 'Ongoing':
                            return 'bg-green-100 text-green-800';
                        case 'Completed':
                            return 'bg-gray-100 text-gray-800';
                        default:
                            return 'bg-gray-100 text-gray-800';
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
