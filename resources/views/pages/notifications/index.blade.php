<x-app-layout>
    <div class="max-w-7xl mx-auto p-6" x-data="notificationIndex()">
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

                <a href="{{ route('notifications.create') }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                    <i class="fa-solid fa-plus"></i>
                    Add Notification
                </a>
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

        <div class="rounded-xl border border-gray-200 bg-white overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-gray-600">
                            <th class="px-4 py-2 font-medium">Message</th>
                            <th class="px-4 py-2 font-medium">Target Type</th>
                            <th class="px-4 py-2 font-medium">Target</th>
                            <th class="px-4 py-2 font-medium">Status</th>
                            <th class="px-4 py-2 font-medium">Date</th>
                            <th class="px-4 py-2 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($notifications as $n)
                        <tr>
                            {{-- Title --}}
                            <td class="px-4 py-3 text-ellipsis">
                                {{ $n->title }}
                            </td>

                            {{-- Target Type --}}
                            <td class="px-4 py-3">
                                @if($n->target_type == 'event')
                                    <div class="p-1 rounded-full bg-blue-200 text-blue-800 font-semibold text-xs w-12 text-center">Event</div>
                                @elseif($n->target_type == 'role')
                                    <div class="p-1 rounded-full bg-purple-200 text-purple-800 font-semibold text-xs w-12 text-center">Role</div>
                                @elseif($n->target_type == 'user')
                                    <div class="p-1 rounded-full bg-green-200 text-green-800 font-semibold text-xs w-12 text-center">User</div>
                                @else
                                    <div class="p-1 rounded-full bg-gray-200 text-gray-800 font-semibold text-xs w-12 text-center">Unknown</div>
                                @endif
                            </td>

                            {{-- Target --}}
                            <td class="px-4 py-3 text-ellipsis">
                                @if($n->target_type == 'event')
                                    {{$n->event->title}}
                                @elseif($n->target_type == 'role')
                                    {{$n->role->name}}
                                @elseif($n->target_type == 'user')
                                    {{$n->user->name}}
                                @else
                                    Not Set
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-3">
                                @if($n->status == 'draft')
                                    <div class="p-1 rounded-full bg-gray-200 text-gray-800 font-semibold text-xs w-20 text-center">Draft</div>
                                @elseif($n->status == 'scheduled')
                                    <div class="p-1 rounded-full bg-yellow-200 text-yellow-800 font-semibold text-xs w-20 text-center">Scheduled</div>
                                @elseif($n->status == 'sent')
                                    <div class="p-1 rounded-full bg-red-600 text-white font-semibold text-xs w-20 text-center">Sent</div>
                                @else
                                    <div class="p-1 rounded-full bg-gray-200 text-gray-800 font-semibold text-xs w-20 text-center">Unknown</div>
                                @endif
                            </td>


                            {{-- Date --}}
                            <td class="px-4 py-3 {{ $n->status == 'scheduled' ? 'text-yellow-600' : ''}}">{{ $n->created_at }}</td>

                            <td class="px-4 py-3 text-right flex justify-end gap-2">
                                <button
                                    class="border border-gray-300 px-3 py-1.5 rounded-lg hover:bg-gray-50 text-xs">
                                    <i class="fa-solid fa-paper-plane"></i>
                                </button>
                                <button
                                    class="border border-gray-300 px-3 py-1.5 rounded-lg hover:bg-gray-50 text-xs">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form method="POST" action="{{ route('notifications.destroy', $n) }}" onsubmit="return confirm('Delete this notification?')">
                                    @csrf @method('DELETE')
                                    <button class="border border-gray-300 px-3 py-1.5 rounded-lg text-xs text-red-600 hover:bg-gray-50">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">No notifications found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t">
                {{ $notifications->links() }}
            </div>
        </div>

    </div>

    <script>
        function notificationIndex() {
            return {
                search: '',
                tab: 'active',
                statuses: ['All','Active','Draft','Completed','Cancelled'],

                matches(title, desc, status) {
                    const term = this.search.toLowerCase();
                    const textMatch = title.includes(term) || desc.includes(term);
                    return textMatch;
                }
            }
        }
    </script>
</x-app-layout>
