<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasUuids;

    protected $table = 'AspNetUsers';

    protected $primaryKey = "Id";
    public $timestamps = false;



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'Password',
        'RememberToken',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'EmailVerifiedAt' => 'datetime',
            'Password' => 'hashed',
        ];
    }

    public function clinics(): BelongsToMany
    {
        return $this->belongsToMany(Doctor::class, 'DoctorUsers', 'UserId', 'DoctorId');
    }

    public function getAuthPassword()
    {
        return $this->Password;
    }


    /**
     * 3. (Optional) If your DB uses 'EmailAddress' instead of 'Email'
     * generally Laravel just uses whatever key you pass to Auth::attempt,
     * but for notifications, define this:
     */
    public function routeNotificationForMail($notification)
    {
        return $this->Email;
    }

    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn ($attributes) => $attributes['Email'] ?? null,
        );
    }

    /**
     * Map 'name' (Filament) -> 'Name' (Database)
     * Filament uses this for the top-right user menu
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->FirstName . ' ' . $this->LastName,
        );
    }
}
