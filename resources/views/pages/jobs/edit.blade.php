<x-app-layout>

    <div class="max-w-4xl mx-auto p-6">

        <h1 class="text-2xl font-bold mb-6">Edit Job</h1>

        <form action="{{ route('jobs.update', $job) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">

                <div>
                    <label>Event Day</label>
                    <input type="number" name="event_day" value="{{ $job->event_day }}" class="w-full border rounded p-2">
                </div>

                <div>
                    <label>Vehicle</label>
                    <input type="text" name="vehicle" value="{{ $job->vehicle }}" class="w-full border rounded p-2">
                </div>

                <div>
                    <label>Duty Code</label>
                    <input type="text" name="duty_code" value="{{ $job->duty_code }}"
                        class="w-full border rounded p-2">
                </div>

                <div>
                    <label>Duty Description</label>
                    <input type="text" name="duty_description" value="{{ $job->duty_description }}"
                        class="w-full border rounded p-2">
                </div>

                <div>
                    <label>Location</label>
                    <input type="text" name="location" value="{{ $job->location }}"
                        class="w-full border rounded p-2">
                </div>

                <div>
                    <label>Period</label>
                    <select name="period" class="w-full border rounded p-2">
                        <option value="AM" {{ $job->period == 'AM' ? 'selected' : '' }}>AM</option>
                        <option value="PM" {{ $job->period == 'PM' ? 'selected' : '' }}>PM</option>
                    </select>
                </div>

                <div>
                    <label>KM</label>
                    <input type="number" step="0.01" name="km" value="{{ $job->km }}"
                        class="w-full border rounded p-2">
                </div>

                <div>
                    <label>OV Arrive</label>
                    <input type="time" name="ov_arrive" value="{{ $job->ov_arrive }}"
                        class="w-full border rounded p-2">
                </div>

                <div>
                    <label>Field Arrive</label>
                    <input type="time" name="field_arrive" value="{{ $job->field_arrive }}"
                        class="w-full border rounded p-2">
                </div>

                <div>
                    <label>OV Departure</label>
                    <input type="time" name="ov_departure" value="{{ $job->ov_departure }}"
                        class="w-full border rounded p-2">
                </div>

            </div>

            <div class="mt-4">
                <label>Comment</label>
                <textarea name="comment" class="w-full border rounded p-2">{{ $job->comment }}</textarea>
            </div>

            <div class="mt-6 flex gap-3">

                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">
                    Update Job
                </button>

                <a href="{{ route('jobs.view', $job->event_id) }}" class="border px-4 py-2 rounded">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</x-app-layout>
