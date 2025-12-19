<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EventParticipantLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "phone" => ["required", "string"],
            "password" => ["required", "string"],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $phone = $this->string("phone");
        $password = $this->string("password");

        // Find ALL participants with this phone number
        $participants = \App\Models\EventParticipant::with(["event", "roles"])
            ->where("phone", $phone)
            ->get();

        if ($participants->isEmpty()) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                "phone" => trans("auth.failed"),
            ]);
        }

        // Check if the provided password matches any role password for any of the participant's events
        $validParticipant = null;
        $validEvent = null;

        foreach ($participants as $participant) {
            $event = $participant->event;

            if (!$event) {
                continue;
            }

            // Check each role for this participant
            foreach ($participant->roles as $role) {
                $rolePassword = \App\Models\Password::where(
                    "event_id",
                    $event->id,
                )
                    ->where("role_id", $role->id)
                    ->first();

                if (
                    $rolePassword &&
                    (string) $password === (string) $rolePassword->password
                ) {
                    $validParticipant = $participant;
                    $validEvent = $event;
                    break 2; // Break out of both loops
                }
            }
        }

        if (!$validParticipant || !$validEvent) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                "password" => trans("auth.failed"),
            ]);
        }

        // Store the authenticated participant and event in the request for later use
        $this->merge([
            "authenticated_participant" => $validParticipant,
            "authenticated_event" => $validEvent,
        ]);

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            "phone" => trans("auth.throttle", [
                "seconds" => $seconds,
                "minutes" => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->string("phone")) . "|" . $this->ip(),
        );
    }
}
