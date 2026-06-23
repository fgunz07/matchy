<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Gender;
use App\Notifications\QueuedResetPasswordNotification;
use App\Notifications\QueuedVerifyEmailNotification;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable([
    'slug',
    'avatar_url',
    'name',
    'email',
    'gender',
    'birthday',
    'bio',
    'password',
    'google_id',
    'email_verified_at',
])]
#[Hidden([
    'password',
    'remember_token',
    'google_id',
])]
#[Appends('age')]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gender' => Gender::class,
            'birthday' => 'date:F j, Y',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function age(): Attribute
    {
        return Attribute::make(
            get: fn () => round($this->birthday?->diffInYears(now())),
        );
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'sender_id');
    }

    public function likedUsers(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'likeable', 'likes')
            ->withTimestamps();
    }

    public function likedMessages(): MorphToMany
    {
        return $this->morphedByMany(Message::class, 'likeable', 'likes')
            ->withTimestamps();
    }

    public function likers(): MorphToMany
    {
        return $this->morphToMany(User::class, 'likeable', 'likes')
            ->withTimestamps();
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new QueuedVerifyEmailNotification);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new QueuedResetPasswordNotification($token));
    }
}
