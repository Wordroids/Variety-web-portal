<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">

        @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="items-center justify-between mb-4">
            <!-- Top: Back + Title + Actions -->
            <div class="flex items-center justify-between">
                <a href="{{ route('medical-records.show', $event) }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                    <i class="fa-solid fa-chevron-left"></i>
                    Back to {{ $event->title }} Medical Records
                </a>

                <div class="flex gap-2">
                    <a href="{{ route('events.show', $event) }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        <i class="fa-solid fa-eye"></i> View Event
                    </a>
                </div>
            </div>
            <!-- Title + subtitle -->
            <div class="mt-2">
                <h1 class="text-xl md:text-2xl font-bold text-gray-900">Medical Record</h1>
                <p class="text-gray-500 text-sm">Viewing {{ $record->participant->first_name }} {{ $record->participant->last_name }}'s Medical Record</p>
            </div>

        </div>

        <div class="mb-4 text-sm text-black flex gap-4">
            <div class="mb-1">
                <span class="font-semibold">Import Date:</span>
                <span>{{ $record->imported_at->format('d/m/Y') }}</span>
            </div>
            <div>
                <span class="font-semibold">Destroy Date:</span>
                <span>{{ $record->expires_at->format('d/m/Y') }}</span>
            </div>
        </div>

        @php
            $content = json_decode($record->content);
        @endphp

        <div class="mb-4 rounded-xl bg-white p-6 shadow">
            <h3 class="text-lg font-bold">Personal Details</h3>
            <hr class="my-4">

            {{-- Name --}}
            <h4 class="font-bold">Name</h4>
            <p class="mb-4">{{$content->first_name}} {{$content->last_name}}</p>

            {{-- Vehicle --}}
            <h4 class="font-bold">Vehicle</h4>
            <p class="mb-4">{{$content->vehicle}}</p>

            {{-- Address --}}
            <h4 class="font-bold">Address</h4>
            <p class="mb-4">
                {{$content->address1}} <br/>
                {{$content->address2}} <br/>
                {{$content->address3}} <br/>
                {{$content->address4}} <br/>
                {{$content->address5}} <br/>
                {{$content->address6}}
            </p>

            {{-- Phone --}}
            <h4 class="font-bold">Phone</h4>
            <p class="mb-4">OV13</p>
        </div>

        <div class="mb-4 rounded-xl bg-white p-6 shadow">
            <h3 class="text-lg font-bold">Next of kin</h3>
            <hr class="my-4">

            {{-- Name --}}
            <h4 class="font-bold">Name</h4>
            <p class="mb-4">{{$content->first_name}} {{$content->last_name}}</p>

            {{-- Contact Phone --}}
            <h4 class="font-bold">Contact Phone</h4>
            <p class="mb-4">{{$content->vehicle}}</p>


            {{-- Alternate Contact Phone --}}
            <h4 class="font-bold">Alternate Contact Phone</h4>
            <p class="mb-4">{{$content->vehicle}}</p>
        </div>

        <div class="mb-4 rounded-xl bg-white p-6 shadow">
            <h3 class="text-lg font-bold">Medical Details</h3>
            <hr class="my-4">

            {{-- Date of Birth --}}
            <h4 class="font-bold">Date of Birth</h4>
            <p class="mb-4">{{$content->first_name}} {{$content->last_name}}</p>

            {{-- Allergies --}}
            <h4 class="font-bold">Allergies</h4>
            <p class="mb-4">{{$content->vehicle}}</p>

            {{-- Dietary Requirements --}}
            <h4 class="font-bold">Dietary Requirements</h4>
            <p class="mb-4">{{$content->vehicle}}</p>

            {{-- Past Medical History --}}
            <h4 class="font-bold">Past Medical History</h4>
            <p class="mb-4">{{$content->vehicle}}</p>

            {{-- Past Medical Conditions --}}
            <h4 class="font-bold">Past Medical Conditions</h4>
            <p class="mb-4">{{$content->vehicle}}</p>
        </div>

        <div class="mb-4 rounded-xl bg-white p-6 shadow">
            <h3 class="text-lg font-bold">Comments</h3>
            <hr class="my-4">

        </div>

        <div class="mb-4 rounded-xl bg-white p-6 shadow">
            <h3 class="text-lg font-bold">Images</h3>
            <hr class="my-4">

        </div>
    </div>
</x-app-layout>
