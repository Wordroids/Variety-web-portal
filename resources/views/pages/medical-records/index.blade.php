<x-app-layout>
    <div class="max-w-7xl mx-auto p-6" x-data="medicalRecordsPage()">

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
                            <th class="px-4 py-3 font-medium text-right sticky right-0 bg-gray-50">Actions</th>
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

                        <template x-for="record in records" :key="record.id">
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="font-semibold text-gray-900" x-text="record.vehicle"></div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap" x-text="record.first_name"></td>
                                <td class="px-4 py-3 whitespace-nowrap" x-text="record.last_name"></td>
                                <td class="px-4 py-3 whitespace-nowrap" x-text="record.nickname || '—'"></td>
                                <td class="px-4 py-3 whitespace-nowrap" x-text="record.address1 || '—'"></td>
                                <td class="px-4 py-3 whitespace-nowrap" x-text="record.address2 || '—'"></td>
                                <td class="px-4 py-3 whitespace-nowrap" x-text="record.address3 || '—'"></td>
                                <td class="px-4 py-3 whitespace-nowrap" x-text="record.address4 || '—'"></td>
                                <td class="px-4 py-3 whitespace-nowrap" x-text="record.address5 || '—'"></td>
                                <td class="px-4 py-3 whitespace-nowrap" x-text="record.address6 || '—'"></td>
                                <td class="px-4 py-3 whitespace-nowrap" x-text="record.mobile || '—'"></td>
                                <td class="px-4 py-3 whitespace-nowrap" x-text="record.next_of_kin || '—'"></td>
                                <td class="px-4 py-3 whitespace-nowrap" x-text="record.nok_phone || '—'"></td>
                                <td class="px-4 py-3 whitespace-nowrap" x-text="record.nok_alt_phone || '—'"></td>
                                <td class="px-4 py-3 whitespace-nowrap" x-text="formatDate(record.dob) || '—'"></td>
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
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <template x-if="record.vehicle_image">
                                        <img :src="record.vehicle_image" alt="Vehicle" class="w-10 h-10 object-cover rounded">
                                    </template>
                                    <template x-if="!record.vehicle_image">
                                        <span class="text-gray-400">—</span>
                                    </template>
                                </td>
                                <td class="px-4 py-3 sticky right-0 bg-white whitespace-nowrap border-l border-gray-100">
                                    <div class="flex justify-end gap-3">
                                        <button @click="openCommentsModal(record)" class="text-purple-600 hover:text-purple-800 transition" title="Comments">
                                            <i class="fa-solid fa-comment"></i>
                                        </button>
                                        <button @click="openImagesModal(record)" class="text-orange-600 hover:text-orange-800 transition" title="Images">
                                            <i class="fa-solid fa-image"></i>
                                        </button>
                                        <button @click="openViewModal(record)" class="text-blue-600 hover:text-blue-800 transition" title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button @click="openEditModal(record)" class="text-green-600 hover:text-green-800 transition" title="Edit">
                                            <i class="fa-solid fa-edit"></i>
                                        </button>
                                        <button @click="deleteRecord(record.id)" class="text-red-600 hover:text-red-800 transition" title="Delete">
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

        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal()"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-lg bg-white shadow-xl transition-all w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                    <div class="bg-white px-6 pt-5 pb-4">
                        <div class="flex items-center justify-between mb-6 border-b pb-3">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fa-solid fa-file-medical text-red-600"></i> Upload Medical Record
                            </h3>
                            <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-times text-xl"></i>
                            </button>
                        </div>
                        @include('pages.medical-records.addMedicalRecord')
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showViewModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="view-modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeViewModal()"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-lg bg-white shadow-xl transition-all w-full max-w-4xl max-h-[90vh] overflow-y-auto">
                    <div class="bg-white px-6 pt-5 pb-4">
                        <div class="flex items-center justify-between mb-6 border-b pb-3">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fa-solid fa-eye text-blue-600"></i> View Medical Record
                            </h3>
                            <button type="button" @click="closeViewModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-times text-xl"></i>
                            </button>
                        </div>
                        <template x-if="selectedRecord">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8 text-sm">
                                <div class="col-span-1 md:col-span-2 bg-gray-50 p-3 rounded-lg border border-gray-100 mb-2">
                                    <h4 class="font-bold text-gray-800 mb-3 border-b pb-2">Personal Information</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div><span class="font-semibold text-gray-600">Vehicle:</span> <span class="ml-1 text-gray-900" x-text="selectedRecord.vehicle || 'N/A'"></span></div>
                                        <div><span class="font-semibold text-gray-600">First Name:</span> <span class="ml-1 text-gray-900" x-text="selectedRecord.first_name || 'N/A'"></span></div>
                                        <div><span class="font-semibold text-gray-600">Last Name:</span> <span class="ml-1 text-gray-900" x-text="selectedRecord.last_name || 'N/A'"></span></div>
                                        <div><span class="font-semibold text-gray-600">Nickname:</span> <span class="ml-1 text-gray-900" x-text="selectedRecord.nickname || 'N/A'"></span></div>
                                        <div><span class="font-semibold text-gray-600">DOB:</span> <span class="ml-1 text-gray-900" x-text="formatDate(selectedRecord.dob) || 'N/A'"></span></div>
                                        <div><span class="font-semibold text-gray-600">Mobile:</span> <span class="ml-1 text-gray-900" x-text="selectedRecord.mobile || 'N/A'"></span></div>
                                    </div>
                                </div>

                                <div class="col-span-1 bg-white p-3 rounded-lg border border-gray-100">
                                    <h4 class="font-bold text-gray-800 mb-3 border-b pb-2">Address Information</h4>
                                    <div class="space-y-2">
                                        <div><span class="font-semibold text-gray-600">Address 1:</span> <span class="ml-1 text-gray-900" x-text="selectedRecord.address1 || 'N/A'"></span></div>
                                        <div><span class="font-semibold text-gray-600">Address 2:</span> <span class="ml-1 text-gray-900" x-text="selectedRecord.address2 || 'N/A'"></span></div>
                                        <div><span class="font-semibold text-gray-600">Address 3:</span> <span class="ml-1 text-gray-900" x-text="selectedRecord.address3 || 'N/A'"></span></div>
                                        <div><span class="font-semibold text-gray-600">Address 4:</span> <span class="ml-1 text-gray-900" x-text="selectedRecord.address4 || 'N/A'"></span></div>
                                        <div><span class="font-semibold text-gray-600">Address 5:</span> <span class="ml-1 text-gray-900" x-text="selectedRecord.address5 || 'N/A'"></span></div>
                                        <div><span class="font-semibold text-gray-600">Address 6:</span> <span class="ml-1 text-gray-900" x-text="selectedRecord.address6 || 'N/A'"></span></div>
                                    </div>
                                </div>

                                <div class="col-span-1 bg-white p-3 rounded-lg border border-gray-100">
                                    <h4 class="font-bold text-gray-800 mb-3 border-b pb-2">Emergency Contact</h4>
                                    <div class="space-y-2">
                                        <div><span class="font-semibold text-gray-600">Next of Kin:</span> <span class="ml-1 text-gray-900" x-text="selectedRecord.next_of_kin || 'N/A'"></span></div>
                                        <div><span class="font-semibold text-gray-600">NOK Phone:</span> <span class="ml-1 text-gray-900" x-text="selectedRecord.nok_phone || 'N/A'"></span></div>
                                        <div><span class="font-semibold text-gray-600">NOK Alt Phone:</span> <span class="ml-1 text-gray-900" x-text="selectedRecord.nok_alt_phone || 'N/A'"></span></div>
                                    </div>
                                </div>

                                <div class="col-span-1 md:col-span-2 bg-red-50 p-3 rounded-lg border border-red-100 mt-2">
                                    <h4 class="font-bold text-red-800 mb-3 border-b border-red-200 pb-2">Medical Information</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <span class="font-semibold text-gray-700 block mb-1">Allergies:</span>
                                            <p class="text-gray-900 bg-white p-2 rounded border border-gray-200" x-text="selectedRecord.allergies || 'None recorded'"></p>
                                        </div>
                                        <div>
                                            <span class="font-semibold text-gray-700 block mb-1">Dietary Requirements:</span>
                                            <p class="text-gray-900 bg-white p-2 rounded border border-gray-200" x-text="selectedRecord.dietary_requirement || 'None recorded'"></p>
                                        </div>
                                        <div>
                                            <span class="font-semibold text-gray-700 block mb-1">Past Medical History:</span>
                                            <p class="text-gray-900 bg-white p-2 rounded border border-gray-200" x-text="selectedRecord.past_medical_history || 'None recorded'"></p>
                                        </div>
                                        <div>
                                            <span class="font-semibold text-gray-700 block mb-1">Current Medical History:</span>
                                            <p class="text-gray-900 bg-white p-2 rounded border border-gray-200" x-text="selectedRecord.current_medical_history || 'None recorded'"></p>
                                        </div>
                                        <div class="col-span-1 md:col-span-2">
                                            <span class="font-semibold text-gray-700 block mb-1">Current Medications:</span>
                                            <p class="text-gray-900 bg-white p-2 rounded border border-gray-200" x-text="selectedRecord.current_medications || 'None recorded'"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div class="mt-6 flex justify-end">
                            <button @click="closeViewModal()" class="rounded-lg bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-300">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="edit-modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeEditModal()"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-lg bg-white shadow-xl transition-all w-full max-w-5xl max-h-[90vh] overflow-y-auto">
                    <div class="bg-white px-6 pt-5 pb-4">
                        <div class="flex items-center justify-between mb-6 border-b pb-3">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fa-solid fa-edit text-green-600"></i> Edit Medical Record
                            </h3>
                            <button type="button" @click="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-times text-xl"></i>
                            </button>
                        </div>
                        <form @submit.prevent="updateRecord" class="space-y-6">

                            <h4 class="font-bold text-gray-800 border-b pb-2">Personal Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Vehicle</label>
                                    <input type="text" x-model="editForm.vehicle" class="mt-1 w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">First Name</label>
                                    <input type="text" x-model="editForm.first_name" class="mt-1 w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                                    <input type="text" x-model="editForm.last_name" class="mt-1 w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nickname</label>
                                    <input type="text" x-model="editForm.nickname" class="mt-1 w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">DOB (yyyy-mm-dd)</label>
                                    <input type="date" x-model="editForm.dob" class="mt-1 w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Mobile</label>
                                    <input type="text" x-model="editForm.mobile" class="mt-1 w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-bold text-gray-800 border-b pb-2 mb-4">Address</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="col-span-2"><input type="text" x-model="editForm.address1" placeholder="Address 1" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"></div>
                                        <div class="col-span-2"><input type="text" x-model="editForm.address2" placeholder="Address 2" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"></div>
                                        <div><input type="text" x-model="editForm.address3" placeholder="Address 3" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"></div>
                                        <div><input type="text" x-model="editForm.address4" placeholder="Address 4" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"></div>
                                        <div><input type="text" x-model="editForm.address5" placeholder="Address 5" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"></div>
                                        <div><input type="text" x-model="editForm.address6" placeholder="Address 6" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"></div>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 border-b pb-2 mb-4">Emergency Contact (Next of Kin)</h4>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Name</label>
                                            <input type="text" x-model="editForm.next_of_kin" class="mt-1 w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Phone</label>
                                                <input type="text" x-model="editForm.nok_phone" class="mt-1 w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Alt Phone</label>
                                                <input type="text" x-model="editForm.nok_alt_phone" class="mt-1 w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h4 class="font-bold text-red-800 border-b border-red-200 pb-2 mt-4">Medical Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-red-50 p-4 rounded-lg border border-red-100">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Allergies</label>
                                    <textarea x-model="editForm.allergies" rows="2" class="mt-1 w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Dietary Requirements</label>
                                    <textarea x-model="editForm.dietary_requirement" rows="2" class="mt-1 w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Past Medical History</label>
                                    <textarea x-model="editForm.past_medical_history" rows="2" class="mt-1 w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Current Medical History</label>
                                    <textarea x-model="editForm.current_medical_history" rows="2" class="mt-1 w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"></textarea>
                                </div>
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Current Medications</label>
                                    <textarea x-model="editForm.current_medications" rows="2" class="mt-1 w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"></textarea>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end gap-3 border-t pt-4">
                                <button type="button" @click="closeEditModal()" class="rounded-lg bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-300">Cancel</button>
                                <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showCommentsModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="comments-modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeCommentsModal()"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-lg bg-white shadow-xl transition-all w-full max-w-2xl">
                    <div class="bg-white px-6 pt-5 pb-4">
                        <div class="flex items-center justify-between mb-6 border-b pb-3">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fa-solid fa-comment text-purple-600"></i> Comments
                            </h3>
                            <button type="button" @click="closeCommentsModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-times text-xl"></i>
                            </button>
                        </div>
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div><span class="font-semibold text-gray-700">Vehicle:</span> <span class="text-gray-900 ml-2" x-text="selectedRecord?.vehicle || 'N/A'"></span></div>
                                    <div><span class="font-semibold text-gray-700">Name:</span> <span class="text-gray-900 ml-2" x-text="(selectedRecord?.first_name || '') + ' ' + (selectedRecord?.last_name || '')"></span></div>
                                </div>
                            </div>
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
                        <div class="mt-6 flex justify-end">
                            <button @click="closeCommentsModal()" class="rounded-lg bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-300">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showImagesModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="images-modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeImagesModal()"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-lg bg-white shadow-xl transition-all w-full max-w-4xl">
                    <div class="bg-white px-6 pt-5 pb-4">
                        <div class="flex items-center justify-between mb-6 border-b pb-3">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fa-solid fa-image text-orange-600"></i> Images
                            </h3>
                            <button type="button" @click="closeImagesModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-times text-xl"></i>
                            </button>
                        </div>
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div><span class="font-semibold text-gray-700">Vehicle:</span> <span class="text-gray-900 ml-2" x-text="selectedRecord?.vehicle || 'N/A'"></span></div>
                                    <div><span class="font-semibold text-gray-700">Name:</span> <span class="text-gray-900 ml-2" x-text="(selectedRecord?.first_name || '') + ' ' + (selectedRecord?.last_name || '')"></span></div>
                                </div>
                            </div>
                            <div class="bg-white rounded-lg border border-gray-200 p-4 min-h-[300px]">
                                <template x-if="selectedRecord?.images && selectedRecord.images.length > 0">
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        <template x-for="(image, index) in selectedRecord.images" :key="index">
                                            <div class="relative group">
                                                <img :src="image" :alt="'Image ' + (index + 1)" class="w-full h-48 object-cover rounded-lg border border-gray-200 hover:shadow-lg transition-shadow cursor-pointer" @click="viewFullImage(image)">
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
                        <div class="mt-6 flex justify-end">
                            <button @click="closeImagesModal()" class="rounded-lg bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-300">Close</button>
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
                showViewModal: false,
                showEditModal: false,
                loadingEvents: false,
                records: @json($records ?? []),
                events: @json($events ?? []),
                eventsData: [],
                selectedRecord: null,
                editForm: {},
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
                },
                closeModal() {
                    this.showModal = false;
                    document.body.style.overflow = 'auto';
                    this.resetForm();
                },

                //VIEW
                openViewModal(record) {
                    this.selectedRecord = record;
                    this.showViewModal = true;
                    document.body.style.overflow = 'hidden';
                },
                closeViewModal() {
                    this.showViewModal = false;
                    document.body.style.overflow = 'auto';
                    this.selectedRecord = null;
                },

                //EDIT & UPDATE
                openEditModal(record) {
                    this.selectedRecord = record;


                    let dobFormatted = record.dob;
                    if(dobFormatted && dobFormatted.includes('T')) {
                        dobFormatted = dobFormatted.split('T')[0];
                    }

                    this.editForm = { ...record, dob: dobFormatted };
                    this.showEditModal = true;
                    document.body.style.overflow = 'hidden';
                },
                closeEditModal() {
                    this.showEditModal = false;
                    document.body.style.overflow = 'auto';
                    this.selectedRecord = null;
                    this.editForm = {};
                },
                async updateRecord() {
                    try {
                        const response = await fetch(`/medical-records/${this.selectedRecord.id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(this.editForm)
                        });

                        const data = await response.json();

                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Failed to update the record.');
                    }
                },

                // DELETE
                async deleteRecord(id) {
                    if (!confirm('Are you sure you want to delete this medical record? This action cannot be undone.')) {
                        return;
                    }

                    try {
                        const response = await fetch(`/medical-records/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Failed to delete the record.');
                    }
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

                    fetch('{{ route('medical-records.upload') }}', {
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

                // Comments/Images
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
                    window.open(imageUrl, '_blank');
                },

                formatDate(date) {
                    if (!date) return '';
                    const dateObj = new Date(date);
                    return dateObj.toLocaleDateString('en-GB', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });
                }
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>
