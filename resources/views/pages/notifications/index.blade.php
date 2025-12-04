<x-app-layout>
    <div class="max-w-7xl mx-auto p-6" x-data="notificationData()">
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
                <button @click="showCreateNotification()"
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
                        <!-- Alpine template -->
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
    </div>

    <script>
        function notificationData() {
            const rawNotifications = @json($notifications->items()); // only the current page items

            return {
                notifications: rawNotifications,
                search: '',
                baseUrl: '{{ url('/') }}',   // for delete form action

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

                showCreateNotification() {
                    alert('Open create notification modal');
                }
            }
        }
    </script>
</x-app-layout>