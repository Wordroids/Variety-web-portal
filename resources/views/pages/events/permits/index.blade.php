<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">

        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('events.show', $event) }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                <i class="fa-solid fa-chevron-left"></i>
                Back to Event
            </a>
        </div>

        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Permits</h1>
                <p class="text-gray-500 text-sm">Manage permits for {{ $event->title }}</p>
            </div>
            <button id="openUploadPermitModal"
                class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition">
                <i class="fa-solid fa-file-medical"></i> Upload Permit
            </button>
        </div>

        <hr class="mb-6">

        <div id="permitsList" class="space-y-3">
            @forelse($permits as $permit)
                <div class="rounded-xl border border-gray-200 bg-white p-4 flex justify-between shadow-sm">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">{{ $permit->title }}</h3>
                        <p class="text-xs text-gray-600 mt-1 italic">
                            <i class="fa-solid fa-paperclip mr-1"></i> {{ $permit->filename }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Uploaded on {{ $permit->created_at->format('M d, Y') }} at {{ $permit->created_at->format('h:i A') }}
                        </p>
                    </div>
                    <div class="items-end justify-between flex flex-col">
                        <a href="{{ Storage::url($permit->path) }}" target="_blank"
                        class="text-gray-400 hover:text-red-600 transition-colors" title="View PDF">
                            <i class="fa-solid fa-file-pdf fa-lg"></i>
                        </a>
                        <form action="{{ route("events.permits.destroy", [$event, $permit]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this permit? This action cannot be undone.');">
                            @method("DELETE")
                            @csrf
                            <button class="mt-auto text-red-600 hover:text-red-900 text-sm">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div id="noPermitsMessage" class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center">
                    <i class="fa-solid fa-folder-open text-gray-300 text-3xl mb-3 block"></i>
                    <p class="text-sm text-gray-500">No permits uploaded yet. Click "Upload Permit" to get started.</p>
                </div>
            @endforelse
        </div>

        <div id="uploadPermitModal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
            <div id="uploadPermitBackdrop" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

            <div class="relative flex min-h-full items-center justify-center p-4">
                <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl border border-red-100">
                    <div class="flex items-center justify-between border-b border-red-100 px-6 py-4">
                        <h2 class="text-xl font-bold text-red-600">Upload Permit</h2>
                        <button id="closeUploadPermitModal" type="button"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form action="{{ route('events.permits.store', $event) }}" method="POST" enctype="multipart/form-data" class="px-6 py-5 space-y-4">
                        @csrf
                        <div>
                            <label for="permitTitle" class="block text-sm font-semibold text-gray-800 mb-1">Permit Title</label>
                            <input id="permitTitle" name="title" type="text" required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-200"
                                placeholder="e.g., Liquor License, Safety Clearance" />
                        </div>

                        <div>
                            <label for="permitFile" class="block text-sm font-semibold text-gray-800 mb-1">Select PDF</label>
                            <input id="permitFile" name="file" type="file" accept=".pdf" required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100" />
                            <p class="text-xs text-gray-500 mt-2">Maximum file size: 5MB (PDF only)</p>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-3">
                            <button id="cancelUploadPermit" type="button"
                                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                class="rounded-lg bg-red-600 px-5 py-2 text-sm font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 shadow-md transition">
                                <i class="fa-solid fa-cloud-arrow-up mr-2"></i>Upload Now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const openBtn = document.getElementById('openUploadPermitModal');
            const closeBtn = document.getElementById('closeUploadPermitModal');
            const cancelBtn = document.getElementById('cancelUploadPermit');
            const backdrop = document.getElementById('uploadPermitBackdrop');
            const modal = document.getElementById('uploadPermitModal');

            const toggleModal = (show) => {
                if (show) {
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                } else {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            };

            openBtn.addEventListener('click', () => toggleModal(true));
            [closeBtn, cancelBtn, backdrop].forEach(el => {
                el.addEventListener('click', () => toggleModal(false));
            });

            // Close on ESC key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    toggleModal(false);
                }
            });
        })();
    </script>
</x-app-layout>
