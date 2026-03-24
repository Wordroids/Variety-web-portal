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
                <h1 class="text-2xl font-bold text-gray-900">Forms</h1>
                <p class="text-gray-500 text-sm">Manage external links and forms for {{ $event->title }}</p>
            </div>
            <button id="openUploadFormModal"
                class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition shadow-sm">
                <i class="fa-solid fa-plus"></i> Add Form
            </button>
        </div>

        <hr class="mb-6">

        <div id="formsList" class="space-y-3">
            @forelse($forms as $form)
                <div class="rounded-xl border border-gray-200 bg-white p-4 flex justify-between shadow-sm hover:border-red-100 transition">
                    <div class="flex-1 pr-4">
                        <h3 class="text-sm font-semibold text-gray-900">{{ $form->title }}</h3>
                        <p class="text-xs text-gray-600 mt-1 line-clamp-2">
                            {{ $form->description }}
                        </p>
                        <p class="text-[10px] text-gray-400 mt-2 uppercase tracking-wider font-medium">
                            Added {{ $form->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <div class="flex flex-col justify-between items-end min-w-[80px]">
                        <a href="{{ $form->link }}" target="_blank" rel="noopener noreferrer"
                            class="text-gray-400 hover:text-red-600 transition-colors" title="Open Link">
                            <i class="fa-solid fa-arrow-up-right-from-square fa-lg"></i>
                        </a>

                        <form action="{{ route('events.forms.destroy', [$event, $form]) }}" method="POST"
                              onsubmit="return confirm('Remove this form link?');">
                            @method("DELETE")
                            @csrf
                            <button class="text-red-600 hover:text-red-900 text-xs font-medium">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div id="noFormsMessage" class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center">
                    <i class="fa-solid fa-link-slash text-gray-300 text-3xl mb-3 block"></i>
                    <p class="text-sm text-gray-500">No forms linked yet. Click "Add Form" to share a link.</p>
                </div>
            @endforelse
        </div>

        <div id="uploadFormModal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
            <div id="uploadFormBackdrop" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

            <div class="relative flex min-h-full items-center justify-center p-4">
                <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl border border-red-100 overflow-hidden">
                    <div class="flex items-center justify-between border-b border-red-100 px-6 py-4">
                        <h2 class="text-xl font-bold text-red-600">Add New Form</h2>
                        <button id="closeUploadFormModal" type="button"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form action="{{ route('events.forms.store', $event) }}" method="POST" class="px-6 py-5 space-y-4">
                        @csrf
                        <div>
                            <label for="formTitle" class="block text-sm font-semibold text-gray-800 mb-1">Title</label>
                            <input id="formTitle" name="title" type="text" required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-200"
                                placeholder="e.g., Volunteer Sign-up Sheet" />
                        </div>

                        <div>
                            <label for="formDescription" class="block text-sm font-semibold text-gray-800 mb-1">Description</label>
                            <textarea id="formDescription" name="description" rows="2" required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-200"
                                placeholder="Briefly describe what this form is for..."></textarea>
                        </div>

                        <div>
                            <label for="formLink" class="block text-sm font-semibold text-gray-800 mb-1">URL / Link</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <i class="fa-solid fa-link text-xs"></i>
                                </span>
                                <input id="formLink" name="link" type="url" required
                                    class="w-full rounded-lg border border-gray-300 pl-8 pr-3 py-2 text-sm focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-200"
                                    placeholder="https://google.com/forms/..." />
                            </div>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-3">
                            <button id="cancelUploadForm" type="button"
                                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                class="rounded-lg bg-red-600 px-5 py-2 text-sm font-semibold text-white hover:bg-red-700 shadow-md transition">
                                Save Form
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const modal = document.getElementById('uploadFormModal');
            const openBtn = document.getElementById('openUploadFormModal');
            const closeElements = [
                document.getElementById('closeUploadFormModal'),
                document.getElementById('cancelUploadForm'),
                document.getElementById('uploadFormBackdrop')
            ];

            const toggleModal = (show) => {
                modal.classList.toggle('hidden', !show);
                document.body.classList.toggle('overflow-hidden', show);
                if (show) modal.setAttribute('aria-hidden', 'false');
                else modal.setAttribute('aria-hidden', 'true');
            };

            openBtn.addEventListener('click', () => toggleModal(true));
            closeElements.forEach(el => el.addEventListener('click', () => toggleModal(false)));

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    toggleModal(false);
                }
            });
        })();
    </script>
</x-app-layout>
