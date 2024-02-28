<?php

namespace App\Models;

use App\Casts\Timezone;
use App\Enums\Accreditation;
use App\Enums\Role;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasUlids, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'timezone',
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
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'timezone' => Timezone::class,
        'role' => Role::class,
    ];

    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class)
            ->withTimestamps()
            ->withPivot('status')->as('attendance')
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

    public function isTeamLeader(): bool
    {
        return $this->role == Role::TeamLeader;
    }

    public function isGuest(): bool
    {
        return $this->role == Role::Guest;
    }

    public function isPermitHolder(): bool
    {
        return $this->accreditations->contains(Accreditation::PermitHolder);
    }
}
