<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition
                 class="mb-4 flex items-center justify-between rounded-md bg-green-50 p-3 text-green-800 border border-green-200">
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="text-green-700 hover:text-green-900">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 p-4 text-red-800">
                <div class="flex items-start gap-2">
                    <i class="fa-solid fa-circle-exclamation mt-1"></i>
                    <div>
                        <p class="font-semibold mb-1">Please correct the following errors:</p>
                        <ul class="list-disc list-inside text-sm space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form x-data="editForm({
                    initDays: @js($daysJson),
                    old: @js(old())
               })"
              x-init="init()"
              method="POST"
              action="{{ route('events.update', $event) }}"
              enctype="multipart/form-data"
              @submit="days.forEach(d => d.expanded = true); loading = true"
              :aria-busy="loading"
              class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Saving: slim top bar only (no modal) -->
            <div
                x-show="loading"
                x-cloak
                x-transition.opacity.duration.200ms
                class="pointer-events-none fixed inset-x-0 top-0 z-[200]"
                role="status"
                aria-live="polite"
            >
                <span class="sr-only">Updating event, please wait</span>
                <div class="h-1 w-full overflow-hidden bg-red-100">
                    <div class="submit-progress-indeterminate h-full w-1/3 rounded-full bg-red-600"></div>
                </div>
            </div>

            <!-- Event Info -->
            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fa-regular fa-calendar text-gray-700 text-lg"></i>
                    <h2 class="text-xl font-semibold text-gray-900">Edit Event</h2>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Event Title *</label>
                        <input name="title" type="text" value="{{ old('title', $event->title) }}" required
                               class="mt-1 w-full rounded-lg border {{ $errors->has('title') ? 'border-red-500' : 'border-gray-300' }} focus:ring-red-500 focus:border-red-500"
                               placeholder="Enter event title" />
                        @error('title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description *</label>
                        <textarea name="description" rows="5" required
                                  class="mt-1 w-full rounded-lg border {{ $errors->has('description') ? 'border-red-500' : 'border-gray-300' }} focus:ring-red-500 focus:border-red-500"
                                  placeholder="Describe the event">{{ old('description', $event->description) }}</textarea>
                        @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Start Date *</label>
                            <input name="start_date" type="date" value="{{ old('start_date', $event->start_date->format('Y-m-d')) }}" required
                                   class="mt-1 w-full rounded-lg border {{ $errors->has('start_date') ? 'border-red-500' : 'border-gray-300' }} focus:ring-red-500 focus:border-red-500" />
                            @error('start_date') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">End Date *</label>
                            <input name="end_date" type="date" value="{{ old('end_date', $event->end_date->format('Y-m-d')) }}" required
                                   class="mt-1 w-full rounded-lg border {{ $errors->has('end_date') ? 'border-red-500' : 'border-gray-300' }} focus:ring-red-500 focus:border-red-500" />
                            @error('end_date') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </section>

            <!-- Itinerary -->
            <section class="rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 pt-6">
                    <h2 class="text-xl font-semibold text-gray-900">Itinerary</h2>
                    <button type="button" @click="addDay()"
                            class="inline-flex items-center gap-2 rounded-lg bg-white border border-gray-200 px-4 py-2 text-sm font-medium hover:bg-gray-50">
                        <i class="fa-solid fa-plus"></i> Add Day
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <template x-for="(day, i) in days" :key="'itinerary-day-' + i + '-' + (day.id ?? 'new')">
                        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                            <div class="flex items-stretch gap-2 border-b border-gray-100 bg-gray-50/90 px-3 py-3 sm:px-4">
                                <button
                                    type="button"
                                    @click="day.expanded = !day.expanded"
                                    class="flex min-w-0 flex-1 items-center gap-3 rounded-lg text-left text-gray-900 hover:bg-gray-100/80"
                                    :aria-expanded="day.expanded"
                                >
                                    <i
                                        class="fa-solid fa-chevron-down shrink-0 text-gray-500 transition-transform duration-200"
                                        :class="day.expanded ? 'rotate-180' : ''"
                                        aria-hidden="true"
                                    ></i>
                                    <span class="text-base font-semibold">Day <span x-text="i + 1"></span></span>
                                    <span class="truncate text-sm font-normal text-gray-600" x-text="day.title || 'Untitled day'"></span>
                                </button>
                                <button type="button" @click.stop="removeDay(i)" class="shrink-0 self-center rounded-lg px-2 py-1 text-sm text-red-600 hover:bg-red-50 hover:underline">
                                    Remove
                                </button>
                            </div>

                            <div
                                x-show="day.expanded"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-1"
                            >
                            <div class="space-y-6 p-5 pt-4">
                            <!-- Hidden ID (existing days) -->
                            <template x-if="day.id">
                                <input type="hidden" :name="`days[${i}][id]`" x-model="day.id">
                            </template>
                            <input type="hidden" :name="`days[${i}][sort_order]`" :value="i">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Day Title *</label>
                                    <input :name="`days[${i}][title]`" x-model="day.title" required
                                           class="mt-1 w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500"/>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Date *</label>
                                    <input :name="`days[${i}][date]`" x-model="day.date" type="date" required
                                           class="mt-1 w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500"/>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Subtitle</label>
                                    <input :name="`days[${i}][subtitle]`" x-model="day.subtitle"
                                           class="mt-1 w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500"/>
                                </div>

                                <!-- Image -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Day Image</label>

                                    <template x-if="day.image_url">
                                        <div class="mb-2 flex items-center gap-3">
                                            <img :src="day.image_url" class="h-20 w-32 rounded-lg object-cover border">
                                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                                <input type="checkbox" :name="`days[${i}][remove_image]`" x-model="day.remove_image" value="1" class="rounded">
                                                Remove current image
                                            </label>
                                        </div>
                                    </template>

                                    <input :name="`days[${i}][image]`" type="file"
                                           class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:rounded-lg file:border-0 file:bg-red-600 file:px-4 file:py-2 file:font-medium file:text-white hover:file:bg-red-700"/>
                                    <p class="text-xs text-gray-500 mt-1">Optional. JPG/PNG up to 4MB.</p>
                                </div>
                            </div>

                            <!-- Locations -->
                            <div class="mt-6 rounded-xl border border-gray-100 bg-red-50/40 p-4">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-semibold text-gray-900">Key Locations</h4>
                                    <button type="button" @click="addLocation(i)"
                                            class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700">
                                        <i class="fa-solid fa-plus"></i> Add Location
                                    </button>
                                </div>
                                <div class="mt-4 space-y-4">
                                    <template x-for="(loc, j) in day.locations" :key="j">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                            <template x-if="loc.id">
                                                <input type="hidden" :name="`days[${i}][locations][${j}][id]`" x-model="loc.id">
                                            </template>
                                            <input :name="`days[${i}][locations][${j}][name]`" x-model="loc.name" placeholder="Location name"
                                                   class="rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500"/>
                                            <input :name="`days[${i}][locations][${j}][link_title]`" x-model="loc.link_title" placeholder="Link title"
                                                   class="rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500"/>
                                            <div class="flex gap-2">
                                                <input :name="`days[${i}][locations][${j}][link_url]`" x-model="loc.link_url" placeholder="Link URL"
                                                       class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500"/>
                                                <input type="hidden" :name="`days[${i}][locations][${j}][sort_order]`" :value="j">
                                                <button type="button" @click="removeLocation(i,j)"
                                                        class="rounded-lg border border-gray-200 px-3 text-sm hover:bg-gray-50">Remove</button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="mt-6 rounded-xl border border-gray-100 bg-red-50/40 p-4">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-semibold text-gray-900">Itinerary Details</h4>
                                </div>
                                <div class="mt-4 space-y-4">
                                    <div class="space-y-2">
                                        <input :name="`days[${i}][itinerary_title]`" placeholder="Section title" x-model="day.itinerary_title"
                                               class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500"/>
                                        <div class="gap-2">
                                            <div
                                                x-bind:data-name="`days[${i}][itinerary_description]`"
                                                x-bind:data-id="`days[${i}][itinerary_description]`"
                                                x-bind:data-value="day.itinerary_description"
                                            >
                                                <x-trix-input-alpine
                                                    placeholder="Section description"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Resources -->
                            <div class="mt-6 rounded-xl border border-gray-100 bg-red-50/40 p-4">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-semibold text-gray-900">Additional Resources</h4>
                                    <button type="button" @click="addResource(i)"
                                            class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700">
                                        <i class="fa-solid fa-plus"></i> Add Button
                                    </button>
                                </div>
                                <div class="mt-4 space-y-4">
                                    <template x-for="(res, r) in day.resources" :key="r">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <template x-if="res.id">
                                                <input type="hidden" :name="`days[${i}][resources][${r}][id]`" x-model="res.id">
                                            </template>
                                            <input :name="`days[${i}][resources][${r}][title]`" x-model="res.title" placeholder="Button title"
                                                   class="rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500"/>
                                            <div class="flex gap-2">
                                                <input :name="`days[${i}][resources][${r}][url]`" x-model="res.url" placeholder="Button link URL"
                                                       class="w-full rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500"/>
                                                <input type="hidden" :name="`days[${i}][resources][${r}][sort_order]`" :value="r">
                                                <button type="button" @click="removeResource(i,r)"
                                                        class="rounded-lg border border-gray-200 px-3 text-sm hover:bg-gray-50">Remove</button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="button" @click="moveDayUp(i)" class="mr-2 text-sm text-gray-600 hover:underline">Move Up</button>
                                <button type="button" @click="moveDayDown(i)" class="text-sm text-gray-600 hover:underline">Move Down</button>
                            </div>
                            </div>
                            </div>
                        </div>
                    </template>
                </div>
            </section>

            <!-- Cover Image and Sponsors -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cover Image -->
                <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Event Cover Image</h2>
                    <div class="w-full justify-center" x-data="{
                        coverImageFile: null,
                        defaultPreview: '{{$event->cover_image_path ? '/storage/' . $event->cover_image_path : null}}',
                    }">
                        <label for="cover_image">
                            {{-- Preview Section --}}
                            <div class="cursor-pointer bg-gray-100 rounded-lg aspect-video relative">
                                <button
                                    x-show="coverImageFile"
                                    type="button"
                                    class="w-8 h-8 bg-red-600 rounded-full text-white absolute -right-2 -top-2"
                                    @click="$refs.coverImageInput.value = ''; coverImageFile = null"
                                >X</button>
                                <img
                                    class="object-cover w-full h-full rounded-lg"
                                    x-bind:src="coverImageFile ? URL.createObjectURL(coverImageFile) : defaultPreview"
                                    x-show="coverImageFile ?? defaultPreview"
                                />
                                <div x-show="!coverImageFile && !defaultPreview" class="w-full h-full flex flex-col items-center justify-center">
                                    <img src="/images/icons/icons8-image-64.png" alt="">
                                    <p class="mt-6">Drag and drop or <span class="font-bold">browse</span> files</p>
                                    <p class="text-gray-500 text-sm">PNG, JPEG or JPG</p>
                                </div>
                            </div>

                            {{-- Button --}}
                            <div type="button" class="mt-2 text-center cursor-pointer rounded-lg bg-white border border-gray-200 px-4 py-2 text-sm font-medium hover:bg-gray-50">
                                Upload Cover Image
                            </div>
                        </label>
                        {{-- Cover Image Input --}}
                        <input
                            type="file"
                            x-ref="coverImageInput"
                            name="cover_image"
                            id="cover_image"
                            class="hidden"
                            accept="image/*"
                            @change="coverImageFile = $event.target.files[0];"
                        />
                    </div>
                </section>

                <!-- Sponsors -->
                <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Event Sponsors</h2>
                    <div class="w-full justify-center" x-data="{
                        sponsorImageFile: null,
                        defaultPreview: '{{$event->sponsor_image_path ? '/storage/' . $event->sponsor_image_path : null}}',
                    }">
                        <label for="sponsor_image">
                            {{-- Preview Section --}}
                            <div class="cursor-pointer bg-gray-100 rounded-lg aspect-video relative">
                                <button
                                    x-show="sponsorImageFile"
                                    type="button"
                                    class="w-8 h-8 bg-red-600 rounded-full text-white absolute -right-2 -top-2"
                                    @click="$refs.sponsorImageInput.value = ''; sponsorImageFile = null"
                                >X</button>
                                <img
                                    class="object-contain w-full h-full rounded-lg"
                                    x-bind:src="sponsorImageFile ? URL.createObjectURL(sponsorImageFile) : defaultPreview"
                                    x-show="sponsorImageFile ?? defaultPreview"
                                />
                                <div x-show="!sponsorImageFile && !defaultPreview" class="w-full h-full flex flex-col items-center justify-center">
                                    <img src="/images/icons/icons8-image-64.png" alt="">
                                    <p class="mt-6">Drag and drop or <span class="font-bold">browse</span> files</p>
                                    <p class="text-gray-500 text-sm">PNG, JPEG or JPG</p>
                                </div>
                            </div>

                            {{-- Button --}}
                            <div type="button" class="mt-2 text-center cursor-pointer rounded-lg bg-white border border-gray-200 px-4 py-2 text-sm font-medium hover:bg-gray-50">
                                Upload Sponsor Image
                            </div>
                        </label>
                        {{-- Sponsor Image Input --}}
                        <input
                            type="file"
                            x-ref="sponsorImageInput"
                            name="sponsor_image"
                            id="sponsor_image"
                            class="hidden"
                            accept="image/*"
                            @change="sponsorImageFile = $event.target.files[0];"
                        />
                    </div>
                </section>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('events.show', $event) }}"
                   class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium hover:bg-gray-50">
                    Cancel
                </a>
                <button
                    type="submit"
                    :disabled="loading"
                    class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-5 py-2 text-sm font-semibold text-white hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed">
                    <template x-if="loading">
                        <span class="inline-block h-4 w-4 shrink-0 rounded-full border-2 border-white/35 border-t-white animate-spin" aria-hidden="true"></span>
                    </template>
                    <template x-if="!loading">
                        <i class="fa-solid fa-floppy-disk"></i>
                    </template>
                    <span x-text="loading ? 'Updating...' : 'Update Event'"></span>
                </button>
            </div>
        </form>
    </div>

    <script>
        function editForm({ initDays, initSponsors, old }) {
            return {
                days: [],
                sponsors: [],
                loading: false,
                init() {
                    window.addEventListener('pageshow', (e) => {
                        if (e.persisted) {
                            this.loading = false;
                        }
                    });
                    // If validation failed, prefer old() snapshot (normalize to array — PHP/old() can be object-shaped)
                    let raw = [];
                    if (old && old.days != null) {
                        raw = old.days;
                        if (typeof raw === 'string') {
                            try {
                                raw = JSON.parse(raw);
                            } catch {
                                raw = [];
                            }
                        }
                    } else if (Array.isArray(initDays)) {
                        raw = initDays;
                    } else if (typeof initDays === 'string') {
                        try {
                            raw = JSON.parse(initDays || '[]');
                        } catch {
                            raw = [];
                        }
                    } else if (initDays && typeof initDays === 'object') {
                        raw = Object.values(initDays);
                    }
                    const list = Array.isArray(raw) ? raw : Object.values(raw || {});
                    this.days = list.map((d, i) => ({
                        ...d,
                        expanded: d.expanded ?? i === 0,
                    }));
                },

                // Days
                addDay() {
                    if (!Array.isArray(this.days)) {
                        this.days = [];
                    }
                    this.days.forEach((d) => {
                        d.expanded = false;
                    });
                    this.days.push({
                        expanded: true,
                        id: null, title: '', date: '', subtitle: '',
                        image_url: null, remove_image: false,
                        locations: [], details: [], resources: []
                    });
                },
                removeDay(i) { this.days.splice(i, 1); },
                moveDayUp(i)   { if (i > 0) { const d = this.days.splice(i, 1)[0]; this.days.splice(i - 1, 0, d); } },
                moveDayDown(i) { if (i < this.days.length - 1) { const d = this.days.splice(i, 1)[0]; this.days.splice(i + 1, 0, d); } },

                // Locations
                addLocation(i) { this.days[i].locations.push({ id: null, name: '', link_title: '', link_url: '' }); },
                removeLocation(i, j) { this.days[i].locations.splice(j, 1); },

                // Details
                addDetail(i) { this.days[i].details.push({ id: null, title: '', description: '' }); },
                removeDetail(i, k) { this.days[i].details.splice(k, 1); },

                // Resources
                addResource(i) { this.days[i].resources.push({ id: null, title: '', url: '' }); },
                removeResource(i, r) { this.days[i].resources.splice(r, 1); },
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
        @keyframes submit-progress-indeterminate {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(400%);
            }
        }
        .submit-progress-indeterminate {
            animation: submit-progress-indeterminate 1.15s ease-in-out infinite;
        }
    </style>
</x-app-layout>
