<x-app-layout>
    <div class="max-w-4xl mx-auto p-6" x-data="{ open:false }">
        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-5">
            <h1 class="text-2xl font-bold text-gray-900">Admins of {{$event->title}}</h1>
            <div>                
                <a href="{{ route('events.show', $event) }}" 
                    class="cursor-pointer inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    <i class="fa-solid fa-chevron-left"></i> Back to Event
                </a>
                <button @click="open=true; edit=false; perm={name:''}"
                    class="ms-1 inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                    <i class="fa-solid fa-plus"></i> Add Admin
                </button>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium">Name</th>
                        <th class="px-4 py-2 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($admins as $admin)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-900">{{ $admin->name }}</td>
                            <td class="px-4 py-3 text-right flex justify-end gap-2">
                                <form method="POST" action="{{ route('events.admins.destroy', compact('admin', 'event')) }}" onsubmit="return confirm('Remove this admin?')">
                                    @csrf @method('DELETE')
                                    <button class="border border-gray-300 px-3 py-1.5 rounded-lg text-xs text-red-600 hover:bg-gray-50">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="px-4 py-6 text-center text-gray-500">No admins yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $admins->links() }}
        </div>

        <!-- Modal -->
        <div x-show="open" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div @click.outside="open=false" class="bg-white rounded-2xl w-full max-w-md p-6">
                <form action="{{ route('events.admins.store', $event) }}" method="POST">
                    @csrf
                    <label class="block text-sm font-medium text-gray-700">Select User</label>
                    <select
                        name="user_id"
                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500"
                        required
                    >
                        <option value="">Select</option>
                        @forelse($users as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option>
                        @empty
                        <option value="" disabled>No users found</option>
                        @endforelse
                    </select>

                    <div class="mt-6 flex justify-end gap-2">
                        <button type="button" @click="open=false" class="border border-gray-300 bg-white rounded-lg px-4 py-2 text-sm hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="bg-red-600 rounded-lg px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                            <span>Add</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
