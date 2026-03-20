<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">

        <!-- Top: Back + Actions -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('events.show', $event) }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                <i class="fa-solid fa-chevron-left"></i>
                Back to Event
            </a>
        </div>

        <!-- Event Header Card -->
        <div class="rounded-xl border border-gray-200 bg-white p-4 mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-gray-900">{{ $event->title }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    {{ $event->start_date->format('M d, Y') }} - {{ $event->end_date->format('M d, Y') }}
                </p>
            </div>
            <a href="{{ route('events.edit', $event) }}"
                class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                Change
            </a>
        </div>

        <!-- Upload Box -->
        <button id="openUploadPermitModal" type="button"
            class="w-full mb-6 rounded-xl border-2 border-dashed border-red-300 bg-gradient-to-r from-red-50 to-white p-5 text-center transition hover:border-red-500 hover:from-red-100 hover:to-red-50 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2">
            <h2 class="text-lg font-bold text-red-600">Upload Permits</h2>
            <p class="text-sm text-gray-600 mt-1">Upload new permit document here.</p>
        </button>

        <!-- Permits Section -->
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Permits</h1>

        <div id="permitsList" class="space-y-3">
            <p id="noPermitsMessage" class="rounded-xl border border-gray-200 bg-white p-4 text-sm text-gray-500">
                No permits uploaded yet. Click the upload box above to add your first permit.
            </p>
        </div>

        <!-- Upload Permit Modal -->
        <div id="uploadPermitModal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
            <div id="uploadPermitBackdrop" class="absolute inset-0 bg-black/40"></div>

            <div class="relative flex min-h-full items-center justify-center p-4">
                <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl border border-red-100">
                    <div class="flex items-center justify-between border-b border-red-100 px-6 py-4">
                        <h2 class="text-xl font-bold text-red-600">Upload Permits</h2>
                        <button id="closeUploadPermitModal" type="button"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600"
                            aria-label="Close upload permit">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form id="uploadPermitForm" class="px-6 py-5 space-y-4">
                        <div>
                            <label for="permitTitle" class="block text-sm font-semibold text-gray-800 mb-1">Title</label>
                            <input id="permitTitle" name="title" type="text" required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-200"
                                placeholder="Enter permit title" />
                        </div>

                        <div>
                            <label for="permitFile" class="block text-sm font-semibold text-gray-800 mb-1">Upload PDF</label>
                            <input id="permitFile" name="file" type="file" accept=".pdf" required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-200"
                                placeholder="Select PDF file" />
                            <p class="text-xs text-gray-500 mt-1">Only PDF files are supported</p>
                        </div>

                        <div class="pt-2 flex items-center justify-end gap-3">
                            <button id="cancelUploadPermit" type="button"
                                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                class="rounded-lg bg-red-600 px-5 py-2 text-sm font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2">
                                Upload
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        (function() {
            const openButton = document.getElementById('openUploadPermitModal');
            const closeButton = document.getElementById('closeUploadPermitModal');
            const cancelButton = document.getElementById('cancelUploadPermit');
            const backdrop = document.getElementById('uploadPermitBackdrop');
            const modal = document.getElementById('uploadPermitModal');
            const uploadForm = document.getElementById('uploadPermitForm');
            const permitsList = document.getElementById('permitsList');
            const noPermitsMessage = document.getElementById('noPermitsMessage');

            const openModal = () => {
                modal.classList.remove('hidden');
                modal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('overflow-hidden');
            };

            const closeModal = () => {
                modal.classList.add('hidden');
                modal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('overflow-hidden');
            };

            openButton.addEventListener('click', openModal);
            closeButton.addEventListener('click', closeModal);
            cancelButton.addEventListener('click', closeModal);
            backdrop.addEventListener('click', closeModal);

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });

            uploadForm.addEventListener('submit', (event) => {
                event.preventDefault();

                const formData = new FormData(uploadForm);
                const title = String(formData.get('title') || '').trim();
                const file = formData.get('file');

                if (!title || !file) {
                    return;
                }

                // Get current date and time
                const now = new Date();
                const dateStr = now.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
                const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                const fileName = file.name;

                const card = document.createElement('div');
                card.className = 'rounded-xl border border-gray-200 bg-white p-4 flex items-start justify-between hover:bg-gray-50 transition';
                card.innerHTML = `
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-gray-900">${title}</h3>
                        <p class="text-xs text-gray-600 mt-1">${fileName}</p>
                        <p class="text-xs text-gray-500 mt-1">${dateStr} at ${timeStr}</p>
                    </div>
                    <div class="ml-4 text-gray-400 flex-shrink-0 self-start">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                `;

                if (noPermitsMessage) {
                    noPermitsMessage.remove();
                }

                permitsList.appendChild(card);
                uploadForm.reset();
                closeModal();
            });
        })();
    </script>

</x-app-layout>
