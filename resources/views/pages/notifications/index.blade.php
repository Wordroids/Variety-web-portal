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
                <a href="#"
                   class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    <i class="fa-solid fa-arrow-up-from-bracket"></i> Import
                </a>
                <button @click="showForm()"
                        class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                    <i class="fa-solid fa-plus"></i> Add Notification
                </button>
            </div>
        </div>

        <!-- Search -->
        <div class="mb-4 flex items-center gap-3">
            <div class="flex-1 relative">
                <input type="text" placeholder="Search notifications..." x-model="search"
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
                                <td class="px-4 py-3 truncate max-w-xs" :title="n.title">
                                    <span x-text="n.title"></span>
                                </td>

                                <!-- Target Type Badge -->
                                <td class="px-4 py-3">
                                    <template x-if="n.target_type === 'event'">
                                        <div class="p-1 rounded-full bg-blue-200 text-blue-800 font-semibold text-xs w-12 text-center">Event</div>
                                    </template>
                                    <template x-if="n.target_type === 'role'">
                                        <div class="p-1 rounded-full bg-purple-200 text-purple-800 font-semibold text-xs w-12 text-center">Role</div>
                                    </template>
                                    <template x-if="n.target_type === 'user'">
                                        <div class="p-1 rounded-full bg-green-200 text-green-800 font-semibold text-xs w-12 text-center">User</div>
                                    </template>
                                    <template x-if="!['event','role','user'].includes(n.target_type)">
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
                                        <button class="border border-gray-300 px-3 py-1.5 rounded-lg hover:bg-gray-50 text-xs">
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
                    <h2 class="text-lg font-semibold text-gray-900">Create Notification</h2>
                    <button @click="hideForm()"><i class="fa fa-close"></i></button>
                </div>

                <div class="max-h-[550px] overflow-y-auto p-4">
                    <form :action="'{{ route('notifications.store') }}'" method="POST" id="createNotificationForm">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Title</label>
                            <input placeholder="Enter notification title" x-model="form.title" name="title" value="{{ old('title') }}"  required class="mt-1 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" />
                            @error('title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Message</label>
                            <textarea placeholder="Enter notification message" x-model="form.message" name="message" value="{{ old('message') }}" required class="mt-1 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">
                            </textarea>
                            @error('message')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Target Type</label>
                            <select placeholder="Select" x-model="form.target_type" name="target_type" value="{{ old('target_type') }}"  required class="mt-1 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500">
                                <option value="" disabled>Select</option>
                                <option value="event">Events</option>
                                <option value="role">Roles</option>
                                <option value="user">Users</option>
                            </select>
                            @error('target_type')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Events --}}
                        <template x-if="form.target_type == 'event'">
                            <div class="mb-4">                            
                                <label class="block text-sm font-medium text-gray-700">Select Events</label>
                                <div class="max-h-56 overflow-y-auto space-y-2 mt-1 w-full border rounded-lg border-gray-300 p-3">
                                    @foreach($events as $e)
                                    <label class="flex items-center gap-2">
                                        <input
                                            class="rounded-full border border-red-600 checked:bg-red-600 focus:checked:bg-red-600 hover:checked:bg-red-600 focus:outline-none focus:ring-0"
                                            type="checkbox"
                                            name="target_events[]"
                                            :value="{{ $e->id }}"
                                            x-model="form.target_events">
                                        <span>{{ $e->title }}</span>
                                    </label>
                                    @endforeach
                                </div>
                                @error('target_events')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </template>


                        {{-- Roles --}}
                        <template x-if="form.target_type == 'role'">
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
                                            x-model="form.target_events">
                                        <span>{{ $r->name }}</span>
                                    </label>
                                    @endforeach
                                </div>
                                @error('target_roles')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </template>

                        {{-- Users --}}
                        <template x-if="form.target_type == 'user'">
                            <div class="mb-4">                            
                                <label class="block text-sm font-medium text-gray-700">Select Users</label>
                                <div class="max-h-56 overflow-y-auto space-y-2 mt-1 w-full border rounded-lg border-gray-300 p-3">
                                    @foreach($users as $u)
                                    <label class="flex items-center gap-2">
                                        <input
                                            class="rounded-full border border-red-600 checked:bg-red-600 focus:checked:bg-red-600 hover:checked:bg-red-600 focus:outline-none focus:ring-0"
                                            type="checkbox"
                                            name="target_users[]"
                                            :value="{{ $u->id }}"
                                            x-model="form.target_events">
                                        <span>{{ $u->first_name }} {{ $u->last_name }} ({{ $u->name }})</span>
                                    </label>
                                    @endforeach
                                </div>
                                @error('target_users')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
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
                            @error('status')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <template x-if="form.status == 'scheduled'">
                            <div class="mb-4 flex gap-4">
                                <div class="w-2/3">                            
                                    <label class="block text-sm font-medium text-gray-700">Schedule Date</label>
                                    <input type="date" placeholder="Select schedule date" x-model="form.schedule_date" name="schedule_date" value="{{ old('schedule_date') }}" class="mt-1 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" />
                                    @error('schedule_date')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div class="w-1/3">                            
                                    <label class="block text-sm font-medium text-gray-700">Time</label>
                                    <input type="time" placeholder="Select schedule time" x-model="form.schedule_time" name="schedule_time" value="{{ old('schedule_time') }}" class="mt-1 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" />
                                    @error('schedule_time')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </template>
                    </form>
                </div>
                <div class="flex justify-end gap-2 p-4">
                    <button type="button" @click="hideForm()" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm hover:bg-gray-50">Cancel</button>
                    <button form="createNotificationForm" type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                        <span>Create Notification</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function notificationData() {
            const rawNotifications = @json($notifications->items()); // only the current page items

            return {
                notifications: rawNotifications,
                search: '',
                baseUrl: '{{ url('/') }}',

                form: {
                    title: @js(old('title', '')),
                    message: @js(old('message', '')),
                    target_type: @js(old('target_type', '')),
                    target_events: @js(old('target_events', [])),
                    target_roles: @js(old('target_roles', [])),
                    target_users: @js(old('target_users', [])),
                    status: @js(old('status', '')),
                    schedule_date: @js(old('schedule_date', null)),
                    schedule_time: @js(old('schedule_time', null)),
                },
                isFormShown: @js($errors->any()),

                showForm() {
                    this.isFormShown = true;
                },

                hideForm() {
                    this.isFormShown = false;
                },

                targetNames(n) {
                    if (n.target_type === 'event' && n.events && n.events.length)
                        return n.events.map(e => e.title).join(', ');
                    if (n.target_type === 'role' && n.roles && n.roles.length)
                        return n.roles.map(r => r.name).join(', ');
                    if (n.target_type === 'user' && n.users && n.users.length)
                        return n.users.map(u => u.name).join(', ');
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
            }
        }
    </script>
</x-app-layout>