<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        
        <!-- Success Message -->
        @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('success') }}
        </div>
        @endif

        <!-- Error Message -->
        @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
            <ul class="list-disc pl-5 text-sm">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold text-gray-900">System Settings</h1>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white overflow-hidden">
            <form method="POST" action="{{ route('settings.store') }}" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Location Tracking API</label>
                        <input 
                            name="location_tracking_api" 
                            value="{{ old('location_tracking_api', $settings->location_tracking_api) }}" 
                            placeholder="Enter location tracking API endpoint" 
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" />
                        @error('location_tracking_api')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Enter the endpoint URL for the location tracking API service.</p>
                    </div>
                </div>

                <div class="mt-8 border-t border-gray-200 pt-6">
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                            <i class="fa-solid fa-save"></i>
                            Save Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>