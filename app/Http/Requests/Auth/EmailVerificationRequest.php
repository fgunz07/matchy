<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class EmailVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Since there is no 'auth', we find the user by the route ID.
     */
    public function authorize(): bool
    {
        // 1. Find the user by the ID in the route
        $user = User::findOrFail($this->route('id'));

        // 2. Validate the hash against this specific user's email
        if (! hash_equals(sha1($user->getEmailForVerification()), (string) $this->route('hash'))) {
            return false;
        }

        return true;
    }

    public function rules(): array
    {
        return [];
    }

    /**
     * Fulfill the email verification request using the user from the route.
     */
    public function fulfill(): void
    {
        $user = User::findOrFail($this->route('id'));

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            event(new Verified($user));
        }
    }

    public function withValidator(Validator $validator): Validator
    {
        return $validator;
    }
}
