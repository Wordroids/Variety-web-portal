<x-app-layout>
    <div class="max-w-7xl mx-auto p-4">

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

        <div class="flex gap-4">
            <div class="w-1/2">
                <div class="mb-4 rounded-xl bg-white p-4 shadow">
                    <h3 class="text-lg font-bold">Personal Details</h3>
                    <hr class="my-4">

                    {{-- Name --}}
                    <h4 class="font-bold">Name</h4>
                    <p class="mb-4">{{$content->first_name ?? ''}} {{$content->last_name ?? ''}} @if(!empty($content->nickname)) ({{ $content->nickname }}) @endif</p>

                    {{-- Vehicle --}}
                    <h4 class="font-bold">Vehicle</h4>
                    <p class="mb-4">{{$content->vehicle ?? '—'}}</p>

                    {{-- Address --}}
                    <h4 class="font-bold">Address</h4>
                    <p class="mb-4">
                        {{$content->address1 ?? ''}} <br/>
                        {{$content->address2 ?? ''}} <br/>
                        {{$content->address3 ?? ''}} <br/>
                        {{$content->address4 ?? ''}} <br/>
                        {{$content->address5 ?? ''}} <br/>
                        {{$content->address6 ?? ''}}
                    </p>

                    {{-- Phone --}}
                    <h4 class="font-bold">Phone</h4>
                    <p class="mb-4">{{ $content->mobile ?? '—' }}</p>
                </div>

                <div class="mb-4 rounded-xl bg-white p-4 shadow">
                    <h3 class="text-lg font-bold">Next of kin</h3>
                    <hr class="my-4">

                    {{-- Name --}}
                    <h4 class="font-bold">Name</h4>
                    <p class="mb-4">{{$content->next_of_kin ?? '—'}}</p>

                    {{-- Contact Phone --}}
                    <h4 class="font-bold">Contact Phone</h4>
                    <p class="mb-4">{{$content->nok_phone ?? '—'}}</p>


                    {{-- Alternate Contact Phone --}}
                    <h4 class="font-bold">Alternate Contact Phone</h4>
                    <p class="mb-4">{{$content->nok_alt_phone ?? '—'}}</p>
                </div>


                <div class="mb-4 rounded-xl bg-white p-4 shadow">
                    <h3 class="text-lg font-bold">Medical Details</h3>
                    <hr class="my-4">

                    {{-- Date of Birth --}}
                    <h4 class="font-bold">Date of Birth</h4>
                    <p class="mb-4">{{$content->dob ?? '—'}}</p>

                    {{-- Allergies --}}
                    <h4 class="font-bold">Allergies</h4>
                    <p class="mb-4">{{$content->allergies ?? 'None'}}</p>

                    {{-- Dietary Requirements --}}
                    <h4 class="font-bold">Dietary Requirements</h4>
                    <p class="mb-4">{{$content->dietary_requirement ?? 'None'}}</p>

                    {{-- Past Medical History --}}
                    <h4 class="font-bold">Past Medical History</h4>
                    <p class="mb-4">{{$content->past_medical_history ?? 'None'}}</p>

                    {{-- Current Medical History --}}
                    <h4 class="font-bold">Current Medical History</h4>
                    <p class="mb-4">{{$content->current_medical_history ?? 'None'}}</p>

                    {{-- Current Medications --}}
                    <h4 class="font-bold">Current Medications</h4>
                    <p class="mb-4">{{$content->current_medications ?? 'None'}}</p>
                </div>

            </div>
            <div class="w-1/2">
                <div class="mb-4 rounded-xl bg-white p-4 shadow">
                    <h3 class="text-lg font-bold">Comments</h3>
                    <hr class="my-4">
                    <div class="mb-4">
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Mollitia debitis voluptas officia?</p>
                        <div class="flex justify-between">
                            <p class="text-neutral-800 text-sm">
                                13/02/2026, 7:53:10
                            </p>
                            <a href="#" class="text-red-600 text-sm">Remove</a>
                        </div>

                    </div>
                    <textarea class="w-full rounded-lg mb-2"></textarea>
                    <div class="flex justify-end">
                        <button class="bg-red-600 p-3 text-white rounded-lg">Add Comment</button>
                    </div>
                </div>

                <div class="mb-4 rounded-xl bg-white p-4 shadow">
                    <h3 class="text-lg font-bold">Images</h3>
                    <hr class="my-4">

                    <div class="mb-4">
                        <img src="/storage/events/covers/frYCKWDF0wW1Wki3jkmQqkzI1x2tVBY8LPlxxckm.jpg" alt="" class="rounded-lg w-full mb-2">
                        <div class="flex justify-between">
                            <p class="text-neutral-800 text-sm">
                                13/02/2026, 7:53:10
                            </p>
                            <a href="#" class="text-red-600 text-sm">Remove</a>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button class="bg-red-600 p-3 text-white rounded-lg">Add Image</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
