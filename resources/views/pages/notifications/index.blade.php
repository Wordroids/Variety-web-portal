<x-app-layout>
    <div class="max-w-7xl mx-auto p-6" x-data="notificationData()">

        <!-- Success Message -->
        @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('success') }}
        </div>
        @endif


        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
                <p class="text-gray-500 text-sm">Send notifications to mobile app users</p>
            </div>
            <div class="space-x-2">
                <button @click="showImport()"
                   class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    <i class="fa-solid fa-arrow-up-from-bracket"></i> Import
                </button>
                <button @click="showForm()"
                        class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                    <i class="fa-solid fa-plus"></i> Add Notification
                </button>
            </div>
        </div>

        <!-- Search -->
        <div class="mb-4 flex items-center gap-3">
            <div class="flex-1 relative">
                <input type="text" placeholder="Search notifications..."
                       class="w-full rounded-lg border-gray-300 pl-10 pr-3 py-2 text-sm focus:border-red-500 focus:ring-red-500" />
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-400"></i>
            </div>
        </div>

        <!-- Table -->
        <div class="rounded-xl border border-gray-200 bg-white overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-gray-600">
                            <th class="px-4 py-2 font-medium">Message</th>
                            <th class="px-4 py-2 font-medium">Target Type</th>
                            <th class="px-4 py-2 font-medium">Target(s)</th>
                            <th class="px-4 py-2 font-medium">Status</th>
                            <th class="px-4 py-2 font-medium">Date</th>
                            <th class="px-4 py-2 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="n in notifications" :key="n.id">
                            <tr>
                                <!-- Title / Message -->
                                <td class="px-4 py-3 truncate max-w-xs">
                                    <span x-text="n.message"></span>
                                </td>

                                <!-- Target Type Badge -->
                                <td class="px-4 py-3">
                                    <template x-if="n.target_type === 'event'">
                                        <div class="p-1 rounded-full bg-blue-200 text-blue-800 font-semibold text-xs w-12 text-center">Event</div>
                                    </template>
                                    <template x-if="n.target_type === 'role'">
                                        <div class="p-1 rounded-full bg-purple-200 text-purple-800 font-semibold text-xs w-12 text-center">Role</div>
                                    </template>
                                    <template x-if="n.target_type === 'participant'">
                                        <div class="p-1 rounded-full bg-green-200 text-green-800 font-semibold text-xs w-12 text-center">Participant</div>
                                    </template>
                                    <template x-if="!['event','role','participant'].includes(n.target_type)">
                                        <div class="p-1 rounded-full bg-gray-200 text-gray-800 font-semibold text-xs w-12 text-center">Unknown</div>
                                    </template>
                                </td>

                                <!-- Targets -->
                                <td class="px-4 py-3 truncate max-w-xs"
                                    x-text="targetNames(n)"
                                    :title="targetNames(n)"></td>

                                <!-- Status Badge -->
                                <td class="px-4 py-3">
                                    <template x-if="n.status === 'draft'">
                                        <div class="p-1 rounded-full bg-gray-200 text-gray-800 font-semibold text-xs w-20 text-center">Draft</div>
                                    </template>
                                    <template x-if="n.status === 'scheduled'">
                                        <div class="p-1 rounded-full bg-yellow-200 text-yellow-800 font-semibold text-xs w-20 text-center">Scheduled</div>
                                    </template>
                                    <template x-if="n.status === 'sent'">
                                        <div class="p-1 rounded-full bg-red-600 text-white font-semibold text-xs w-20 text-center">Sent</div>
                                    </template>
                                    <template x-if="!['draft','scheduled','sent'].includes(n.status)">
                                        <div class="p-1 rounded-full bg-gray-200 text-gray-800 font-semibold text-xs w-20 text-center">Unknown</div>
                                    </template>
                                </td>

                                <!-- Date -->
                                <td class="px-4 py-3"
                                    :class="{ 'text-yellow-600': n.status === 'scheduled' }"
                                    x-text="formatDate(n.created_at)"></td>

                                <!-- Actions -->
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="showForm(n)" class="border border-gray-300 px-3 py-1.5 rounded-lg hover:bg-gray-50 text-xs">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <form :action="`${baseUrl}/notifications/${n.id}`" method="POST"
                                              onsubmit="return confirm('Delete this notification?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="border border-gray-300 px-3 py-1.5 rounded-lg text-xs text-red-600 hover:bg-gray-50">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <!-- Empty state -->
                        <template x-if="notifications.length === 0">
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    No notifications found.
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Pagination would normally be server-rendered, keep it if you still use Laravel pagination -->
            <div class="px-4 py-3 border-t">
                {{ $notifications->links() }}
            </div>
        </div>

        <!-- Create Notification Modal -->
        <div x-show="isFormShown"  x-cloak class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center">
            <div @click.outside="hideForm()" class="bg-white rounded-2xl w-full max-w-lg p-2">
                <div class="flex justify-between items-start p-4">
                    <h2 class="text-lg font-semibold text-gray-900" x-text="form.id ? 'Edit Notification' : 'Create Notification'"></h2>
                    <button @click="hideForm()"><i class="fa fa-close"></i></button>
                </div>

                <div class="max-h-[550px] overflow-y-auto p-4">
                    <form :action="form.id ? `{{ url('notifications') }}/${form.id}` : '{{ route('notifications.store') }}'" method="POST" id="createNotificationForm">
                        @csrf
                        <template x-if="form.id"> @method('put')</template>
                        {{-- <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Title</label>
                            <input placeholder="Enter notification title" x-model="form.title" name="title" value="{{ old('title') }}"  required class="mt-1 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" />
                            @error('title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div> --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Message</label>
                            <textarea placeholder="Enter notification message" x-model="form.message" name="message" value="{{ old('message') }}" required class="mt-1 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">
                            </textarea>
                            @error('message', 'notification')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Target Type</label>
                            <select placeholder="Select" x-model="form.target_type" name="target_type" value="{{ old('target_type') }}"  required class="mt-1 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" @change="form.target_events = []">
                                <option value="" disabled>Select</option>
                                <option value="event">Events</option>
                                <option value="role">Roles</option>
                                <option value="participant">Participants</option>
                            </select>
                            @error('target_type', 'notification')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Events --}}
                        <template x-if="form.target_type != ''">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700" x-text="form.target_type == 'event' ? 'Select Events' : 'Select an Event'"></label>
                                <div class="max-h-56 overflow-y-auto space-y-2 mt-1 w-full border rounded-lg border-gray-300 p-3">
                                    @foreach($events as $e)
                                    <label class="flex items-center gap-2">
                                        <input
                                            class="rounded-full border border-red-600 checked:bg-red-600 focus:checked:bg-red-600 hover:checked:bg-red-600 focus:outline-none focus:ring-0"
                                            type="checkbox"
                                            name="target_events[]"
                                            :value="{{ $e->id }}"
                                            x-model="form.target_events"
                                            @change="handleTargetEventChange(event)">
                                        <span>{{ $e->title }}</span>
                                    </label>
                                    @endforeach
                                </div>
                                @error('target_events', 'notification')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </template>

                        {{-- Roles --}}
                        <template x-if="form.target_type == 'role' && form.target_events.length != 0">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Select Roles</label>
                                <div class="max-h-56 overflow-y-auto space-y-2 mt-1 w-full border rounded-lg border-gray-300 p-3">
                                    @foreach($roles as $r)
                                    <label class="flex items-center gap-2">
                                        <input
                                            class="rounded-full border border-red-600 checked:bg-red-600 focus:checked:bg-red-600 hover:checked:bg-red-600 focus:outline-none focus:ring-0"
                                            type="checkbox"
                                            name="target_roles[]"
                                            :value="{{ $r->id }}"
                                            x-model="form.target_roles">
                                        <span>{{ $r->name }}</span>
                                    </label>
                                    @endforeach
                                </div>
                                @error('target_roles', 'notification')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </template>

                        {{-- Participants --}}
                        <template x-if="form.target_type == 'participant' && form.target_events.length != 0">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Select Participants</label>
                                <div class="max-h-56 overflow-y-auto space-y-2 mt-1 w-full border rounded-lg border-gray-300 p-3">
                                    <template x-for="p in eventParticipants">
                                        <label class="flex items-center gap-2">
                                            <input
                                                class="rounded-full border border-red-600 checked:bg-red-600 focus:checked:bg-red-600 hover:checked:bg-red-600 focus:outline-none focus:ring-0"
                                                type="checkbox"
                                                name="target_participants[]"
                                                :value="p.id"
                                                x-model="form.target_participants">
                                            <span x-text="`${p.first_name} ${p.last_name} - (${p.phone})`"></span>
                                        </label>
                                    </template>
                                    <template x-if="eventParticipants.length < 1">
                                        <div class="text-center text-gray-500">No participants found</div>
                                    </template>
                                </div>
                                @error('target_participants', 'notification')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </template>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select placeholder="Select" x-model="form.status" name="status" value="{{ old('status') }}" required class="mt-1 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">
                                <option value="" disabled>Select</option>
                                <option value="draft">Save as draft</option>
                                <option value="scheduled">Schedule for later</option>
                                <option value="sent">Send now</option>
                            </select>
                            @error('status', 'notification')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <template x-if="form.status == 'scheduled'">
                            <div class="mb-4 flex gap-4">
                                <div class="w-2/3">
                                    <label class="block text-sm font-medium text-gray-700">Schedule Date</label>
                                    <input type="date" placeholder="Select schedule date" x-model="form.schedule_date" name="schedule_date" value="{{ old('schedule_date') }}" class="mt-1 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" />
                                    @error('schedule_date', 'notification')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div class="w-1/3">
                                    <label class="block text-sm font-medium text-gray-700">Time</label>
                                    <input type="time" placeholder="Select schedule time" x-model="form.schedule_time" name="schedule_time" value="{{ old('schedule_time') }}" class="mt-1 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" />
                                    @error('schedule_time', 'notification')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </template>
                    </form>
                </div>
                <div class="flex justify-end gap-2 p-4">
                    <button type="button" @click="hideForm()" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm hover:bg-gray-50">Cancel</button>
                    <button form="createNotificationForm" type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                        <span x-text="form.id ? 'Update Notification' : 'Create Notification'"></span>
                    </button>
                    <button @click="console.log(form.target_events)">Test</button>
                </div>
            </div>
        </div>

        <!-- Import Notifications Modal -->
        <div x-show="isImportShown"  x-cloak class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center">
            <div @click.outside="hideImport()" class="bg-white rounded-2xl w-full max-w-3xl p-2">
                <div class="flex justify-between items-start px-4 pt-4">
                    <h2 class="text-lg font-semibold text-gray-900">Import Notifications</h2>
                    <button @click="hideImport()"><i class="fa fa-close"></i></button>
                </div>

                <form
                    class="p-4"
                    id="importNotitificationsForm"
                    action="{{ route('notifications.import') }}"
                    method="post"
                    enctype="multipart/form-data"
                >
                    @csrf
                    <div class="flex justify-between p-4 mb-4 rounded-xl bg-gray-50">
                        <div>
                            <h2 class="font-bold">Download Template</h2>
                            <p class="text-sm text-gray-600">Get the Excel template with correct column format</p>
                        </div>

                        <a href="/resources/notification_import_template.csv"
                           class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-1 text-sm font-semibold text-gray-700 hover:bg-red-100 hover:text-red-600 hover:border-red-300">
                            <i class="fa-solid fa-download"></i> Template
                        </a>
                    </div>

                    <label for="file">
                        <div class="p-6 flex flex-col justify-center items-center gap-2 border-2 border-dashed rounded-xl h-64">
                                <i :class="`fa-solid ${ file ? 'fa-check' : 'fa-arrow-up-from-bracket' } text-gray-600 text-4xl`"></i>
                                <p class="text-gray-600 text-sm" x-text="file?.name || 'Upload Excel or CSV File'"></p>
                                <p
                                    class="cursor-pointer inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-red-100 hover:text-red-600 hover:border-red-300"
                                    x-text="file ? 'Change File' : 'Choose File'"
                                ></p>
                                <p x-show="file" class="cursor-pointer text-sm text-red-600" @click="() => $refs.fileInput.value = ''; file = null">Remove File</p>
                        </div>
                    </label>

                    @if ($errors->import->any())
                        <div class="mt-4 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-red-800">
                            <ul class="list-disc ml-4 text-sm">
                                @foreach ($errors->import->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <input
                        id="file"
                        type="file"
                        name="file"
                        x-ref="fileInput"
                        class="hidden"
                        accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                        @change="file = $event.target.files[0] || null"
                    />
                </form>

                <div class="flex justify-end gap-2 p-4">
                    <button type="button" @click="hideImport()" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm hover:bg-gray-50">Cancel</button>
                    <button :disabled="!file" form="importNotitificationsForm" type="submit" class="rounded-lg disabled:bg-red-200 bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                        <span>Import</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function notificationData() {
            const rawNotifications = @json($notifications->items()); // only the current page items

            return {
                // Notifications
                notifications: rawNotifications,
                baseUrl: '{{ url('/') }}',

                targetNames(n) {
                    if (n.target_type === 'event' && n.events && n.events.length)
                        return n.events.map(e => e.title).join(', ');
                    if (n.target_type === 'role' && n.roles && n.roles.length)
                        return n.roles.map(r => r.name).join(', ');
                    if (n.target_type === 'participants' && n.eventParticipants && n.eventParticipants.length)
                        return n.eventParticipants.map(ep => ep.name).join(', ');
                    return '–';
                },

                formatDate(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleDateString(undefined, {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                },

                // Create & Edit Form
                form: {
                    id: @js(old('id', null, 'notification')),
                    {{-- title: @js(old('title', '')), --}}
                    message: @js(old('message', '', 'notification')),
                    target_type: @js(old('target_type', '', 'notification')),
                    target_events: @js(old('target_events', [], 'notification')),
                    target_roles: @js(old('target_roles', [], 'notification')),
                    target_participants: @js(old('target_participants', [], 'notification')),
                    status: @js(old('status', '', 'notification')),
                    schedule_date: @js(old('schedule_date', null, 'notification')),
                    schedule_time: @js(old('schedule_time', null, 'notification')),
                },

                isFormShown: @js($errors->notification->any()),

                eventParticipants: [],

                fetchEventParticipants () {
                    const eventId = this.form.target_events[0];
                    if(eventId){
                        console.log("Getting fresh participants for event: ", eventId);
                        fetch(`/events/${eventId}/participantsAjax`)
                            .then(response => response.json())
                            .then(data => {
                                console.log(data);
                                this.eventParticipants = data.participants;
                            });
                    } else {
                        this.eventParticipants = [];
                    }

                    this.form.target_participants = [];
                },

                handleTargetEventChange(event) {
                    if (this.form.target_type !== 'event' && this.form.target_events.length > 1) {
                        this.form.target_events = [
                            this.form.target_events[this.form.target_events.length - 1]
                        ];
                        event.target.checked = true;
                    }

                    if(this.form.target_type === 'participant'){
                        this.fetchEventParticipants();
                    }
                },

                showForm(notification) {
                    if(notification){
                        const [date, time] = notification.scheduled_at?.split(' ') ?? [];
                        console.log(notification?.scheduled_at);

                        this.form.id = notification.id;
                        {{-- this.form.title = notification.title; --}}
                        this.form.message = notification.message;
                        this.form.target_type = notification.target_type;
                        this.form.target_events = notification.events?.map(item => item.id) ?? [];
                        this.form.target_roles = notification.roles?.map(item => item.id) ?? [];
                        this.form.target_participants = notification.targetParticipants?.map(item => item.id) ?? [];
                        this.form.status = notification.status;
                        this.form.schedule_date = date;
                        this.form.schedule_time = time?.split(':').slice(0,2).join(':') ?? null;
                    } else if(this.form.id) {
                        this.form.id = null;
                        {{-- this.form.title = ''; --}}
                        this.form.message = '';
                        this.form.target_type = '';
                        this.form.target_events = [];
                        this.form.target_roles = [];
                        this.form.target_participants = [];
                        this.form.status = '';
                        this.form.schedule_date = null;
                        this.form.schedule_time = null;
                    }
                    this.isFormShown = true;
                },

                hideForm() {
                    this.isFormShown = false;
                },

                // Import CSV / Excel Form
                isImportShown: @js($errors->import->any()),
                file: null,

                showImport () {
                    this.isImportShown = true
                },

                hideImport () {
                    this.isImportShown = false
                },
            }
        }
    </script>
</x-app-layout>
