<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
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

        $user = \App\Models\User::where('username', $this->username)->first();
        if (! $user) {
            \Log::info('Failed login attempt: invalid username', [
                'attempted_username' => $this->username,
                'ip_address' => $this->ip(),
            ]);
            throw ValidationException::withMessages([
                'username' => 'Invalid username or password.',
            ]);
        }
        if (! \Hash::check($this->password, $user->password)) {
            $user->increment('login_attempts');
            \Log::info('Failed login attempt: invalid password', [
                'user_id' => $user->id,
                'username' => $user->username,
                'ip_address' => $this->ip(),
            ]);
            throw ValidationException::withMessages([
                'username' => 'Invalid username or password.',
            ]);
        }
        if ($user->status === 'suspended') {
            \Log::info('Login attempt blocked: suspended', [
                'user_id' => $user->id,
                'username' => $user->username,
                'status' => $user->status,
                'ip_address' => $this->ip(),
            ]);
            throw ValidationException::withMessages([
                'username' => 'Your account has been suspended. Please contact administrator.',
            ]);
        }
        if ($user->status === 'pending') {
            \Log::info('Login attempt blocked: pending', [
                'user_id' => $user->id,
                'username' => $user->username,
                'status' => $user->status,
                'ip_address' => $this->ip(),
            ]);
            throw ValidationException::withMessages([
                'username' => 'Your account is pending approval. Please wait for administrator approval.',
            ]);
        }
        // Reset login attempts and update last_login
        $user->login_attempts = 0;
        $user->last_login = now();
        $user->save();
        \Log::info('User logged in successfully', [
            'user_id' => $user->id,
            'username' => $user->username,
            'role' => $user->role,
            'ip_address' => $this->ip(),
        ]);
        if (! \Auth::loginUsingId($user->id, $this->boolean('remember'))) {
            throw ValidationException::withMessages([
                'username' => 'Login failed. Please try again.',
            ]);
        }
        \RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return \Illuminate\Support\Str::transliterate(\Illuminate\Support\Str::lower($this->string('username')).'|'.$this->ip());
    }
}
