<?php

namespace App\Models;

use App\Casts\Timezone;
use App\Enums\Accreditation;
use App\Enums\Role;
use App\Enums\Section;
use App\Notifications\SetupAccount;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasUlids, Notifiable, Concerns\HasUid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'emergency_name',
        'emergency_phone',
        'password',
        'timezone',
        'section',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relationships that should always be eager loaded.
     */
    protected $with = [
        'user_accreditations',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'timezone' => 'UTC',
        'role' => Role::Guest->value,
        'section' => Section::Adult->value,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'phone' => E164PhoneNumberCast::class . ':GB',
        'emergency_phone' => E164PhoneNumberCast::class . ':GB',
        'email_verified_at' => 'datetime',
        'timezone' => Timezone::class,
        'role' => Role::class,
        'section' => Section::class,
    ];

    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class)
            ->withTimestamps()
            ->withPivot('comment', 'status', 'token')->as('attendance')
            ->using(Attendance::class);
    }

    public function user_accreditations(): HasMany
    {
        return $this->hasMany(UserAccreditation::class);
    }

    public function getAccreditationsAttribute(): Collection
    {
        return $this->user_accreditations
            ->pluck('accreditation');
    }

    /**
     * Sync accreditations to the given list.
     *
     * @param null|array<string|Accreditation> $newAccreditations
     * @return void
     */
    public function setAccreditationsAttribute($newAccreditations)
    {
        $accreditations = $this->accreditations->pluck('value');
        $accreditationsToRemove = $accreditations->diff($newAccreditations);
        $accreditationsToAdd = collect($newAccreditations)->diff($accreditations);

        if ($accreditationsToRemove->count() > 0) {
            $this->user_accreditations()->whereIn('accreditation', $accreditationsToRemove)->delete();
        }
        if ($accreditationsToAdd->count() > 0) {
            $this->user_accreditations()->saveMany(
                $accreditationsToAdd->map(
                    fn ($accreditation) => new UserAccreditation(['accreditation' => $accreditation])
                )
            );
        }
    }

    /**
     * Has the user activated their account?
     *
     * When an admin creates a User the password is empty so that no one can
     * log in. When the user has set a password, the account is considered 'active'.
     *
     * @return boolean
     */
    public function isActive(): bool
    {
        return $this->password != "";
    }

    public function isTeamLeader(): bool
    {
        return $this->role == Role::TeamLeader;
    }

    public function isUnder18(): bool
    {
        return !in_array($this->section, [Section::Parent, Section::Adult, Section::Network]);
    }

    public function isParent(): bool
    {
        return $this->section == Section::Parent;
    }

    public function isGuest(): bool
    {
        return $this->role == Role::Guest;
    }

    public function isPermitHolder(): bool
    {
        return $this->accreditations->contains(Accreditation::PermitHolder);
    }

    public function isBookingManager(): bool
    {
        return $this->accreditations->contains(Accreditation::ManageBookings);
    }

    public function isUserManager(): bool
    {
        return $this->accreditations->contains(Accreditation::ManageUsers);
    }

    public function sendAccountSetupNotification()
    {
        $this->notify(new SetupAccount);
    }
}
