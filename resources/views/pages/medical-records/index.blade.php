<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">

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

            <a href="{{ route('medical-records.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                <i class="fa-solid fa-file-medical"></i> Add Medical Record
            </a>
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
                        {{-- Sample data row - replace with actual data --}}
                        <tr>
                            <td colspan="24" class="px-4 py-8 text-center text-gray-500">
                                <i class="fa-solid fa-file-medical text-4xl mb-2 text-gray-300"></i>
                                <p>No medical records found</p>
                                <p class="text-xs mt-1">Start by adding a new medical record</p>
                            </td>
                        </tr>
                        {{-- 
                        Example data row structure:
                        <tr>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-900">Vehicle Name</div>
                            </td>
                            <td class="px-4 py-3">John</td>
                            <td class="px-4 py-3">Doe</td>
                            <td class="px-4 py-3">Johnny</td>
                            <td class="px-4 py-3">123 Main St</td>
                            <td class="px-4 py-3">Apt 4B</td>
                            <td class="px-4 py-3">City</td>
                            <td class="px-4 py-3">State</td>
                            <td class="px-4 py-3">Country</td>
                            <td class="px-4 py-3">Postal Code</td>
                            <td class="px-4 py-3">+1234567890</td>
                            <td class="px-4 py-3">Jane Doe</td>
                            <td class="px-4 py-3">+0987654321</td>
                            <td class="px-4 py-3">+1122334455</td>
                            <td class="px-4 py-3">1990-01-01</td>
                            <td class="px-4 py-3">
                                <div class="max-w-[200px] truncate">Peanuts, Shellfish</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="max-w-[200px] truncate">Vegetarian</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="max-w-[200px] truncate">None</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="max-w-[200px] truncate">Asthma</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="max-w-[200px] truncate">Inhaler</div>
                            </td>
                            <td class="px-4 py-3">
                                <img src="#" alt="Vehicle" class="w-10 h-10 object-cover rounded">
                            </td>
                            <td class="px-4 py-3">
                                <div class="max-w-[200px] truncate">Comments here...</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-1">
                                    <img src="#" alt="Image" class="w-8 h-8 object-cover rounded">
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="#" class="text-blue-600 hover:text-blue-800">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="#" class="text-green-600 hover:text-green-800">
                                        <i class="fa-solid fa-edit"></i>
                                    </a>
                                    <button class="text-red-600 hover:text-red-800">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        --}}
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

    </div>
</x-app-layout>
