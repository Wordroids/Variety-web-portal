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
        <button id="openUploadFormModal" type="button"
            class="w-full mb-6 rounded-xl border-2 border-dashed border-red-300 bg-gradient-to-r from-red-50 to-white p-5 text-center transition hover:border-red-500 hover:from-red-100 hover:to-red-50 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2">
            <h2 class="text-lg font-bold text-red-600">Upload Forms</h2>
            <p class="text-sm text-gray-600 mt-1">Upload new form document here.</p>
        </button>

        <!-- Forms Section -->
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Forms</h1>

        <div id="formsList" class="space-y-3">
            <p id="noFormsMessage" class="rounded-xl border border-gray-200 bg-white p-4 text-sm text-gray-500">
                No forms uploaded yet. Click the upload box above to add your first form.
            </p>
        </div>

        <!-- Upload Form Modal -->
        <div id="uploadFormModal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
            <div id="uploadFormBackdrop" class="absolute inset-0 bg-black/40"></div>

            <div class="relative flex min-h-full items-center justify-center p-4">
                <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl border border-red-100">
                    <div class="flex items-center justify-between border-b border-red-100 px-6 py-4">
                        <h2 class="text-xl font-bold text-red-600">Upload Forms</h2>
                        <button id="closeUploadFormModal" type="button"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600"
                            aria-label="Close upload form">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form id="uploadForm" class="px-6 py-5 space-y-4">
                        <div>
                            <label for="formTitle" class="block text-sm font-semibold text-gray-800 mb-1">Title</label>
                            <input id="formTitle" name="title" type="text" required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-200"
                                placeholder="Enter form title" />
                        </div>

                        <div>
                            <label for="formDescription" class="block text-sm font-semibold text-gray-800 mb-1">Description</label>
                            <textarea id="formDescription" name="description" rows="3" required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-200"
                                placeholder="Enter short form description"></textarea>
                        </div>

                        <div>
                            <label for="formLink" class="block text-sm font-semibold text-gray-800 mb-1">Link</label>
                            <input id="formLink" name="link" type="url" required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-200"
                                placeholder="https://example.com/form" />
                        </div>

                        <div class="pt-2 flex items-center justify-end gap-3">
                            <button id="cancelUploadForm" type="button"
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
            const openButton = document.getElementById('openUploadFormModal');
            const closeButton = document.getElementById('closeUploadFormModal');
            const cancelButton = document.getElementById('cancelUploadForm');
            const backdrop = document.getElementById('uploadFormBackdrop');
            const modal = document.getElementById('uploadFormModal');
            const uploadForm = document.getElementById('uploadForm');
            const formsList = document.getElementById('formsList');
            const noFormsMessage = document.getElementById('noFormsMessage');

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
                const description = String(formData.get('description') || '').trim();
                const link = String(formData.get('link') || '').trim();

                if (!title || !description || !link) {
                    return;
                }

                const card = document.createElement('div');
                card.className = 'rounded-xl border border-gray-200 bg-white p-4 flex items-start justify-between hover:bg-gray-50 transition';
                card.innerHTML = `
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900"></h3>
                        <p class="text-sm text-gray-500 mt-1"></p>
                    </div>
                    <a href="" target="_blank" rel="noopener noreferrer" class="ml-4 text-gray-400 hover:text-gray-600 flex-shrink-0 self-start">
                        <i class="fa-solid fa-arrow-up-right-from-square text-sm"></i>
                    </a>
                `;

                card.querySelector('h3').textContent = title;
                card.querySelector('p').textContent = description;
                card.querySelector('a').setAttribute('href', link);

                if (noFormsMessage) {
                    noFormsMessage.remove();
                }

                formsList.prepend(card);
                uploadForm.reset();
                closeModal();
            });
        })();
    </script>
</x-app-layout>
