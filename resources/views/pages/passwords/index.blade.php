<x-app-layout>
    <div class="max-w-4xl mx-auto p-6" x-data="{ open:false, pass:{} }">
        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-5">
            <h1 class="text-2xl font-bold text-gray-900">Passwords</h1>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium">Role</th>
                        <th class="px-4 py-2 text-left font-medium">Password</th>
                        <th class="px-4 py-2 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($roles as $role)
                        @if($role->name == "Super Admin" || $role->name == "Administrator")
                            {{-- Skip --}}
                        @else
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-900">{{ $role->name }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-{{$role?->password?->password ? '900' : '300'}}">{{ $role?->password?->password ?? 'No Password Set'}}</td>
                            <td class="px-4 py-3 text-right flex justify-end gap-2">
                                <button @click="open=true; edit=true; pass={role_id:'{{ $role->id }}', role_name:'{{ $role->name }}', password:'{{ $role?->password?->password ?? '' }}'}"
                                    class="border border-gray-300 px-3 py-1.5 rounded-lg hover:bg-gray-50 text-xs">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                            </td>
                        </tr>
                        @endif
                    @empty
                        <tr><td colspan="2" class="px-4 py-6 text-center text-gray-500">No roles found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $roles->links() }}
        </div>

        <!-- Modal -->
        <div x-show="open" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div @click.outside="open=false" class="bg-white rounded-2xl w-full max-w-md p-6">
                <form :action="'{{ url('passwords') }}/' + pass.role_id" method="POST">
                    @csrf
                    @method('put')
                    <h2 class="text-lg font-semibold text-gray-900 mb-4" x-text="'Set Password for ' + pass.role_name"></h2>

                    <label class="mt-6 block text-sm font-medium text-gray-700">Password</label>
                    <input name="password" x-model="pass.password" required placeholder="Enter a password" 
                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" />

                    <div class="mt-6 flex justify-end gap-2">
                        <button type="button" @click="open=false" class="border border-gray-300 bg-white rounded-lg px-4 py-2 text-sm hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="bg-red-600 rounded-lg px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                            <span>Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
