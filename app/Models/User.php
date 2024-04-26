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
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;

class User extends Authenticatable implements MustVerifyEmail
{
    use Concerns\HasUid, HasApiTokens, HasFactory, HasUlids, Notifiable;

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
        'ical_token',
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
        'phone' => E164PhoneNumberCast::class.':GB',
        'emergency_phone' => E164PhoneNumberCast::class.':GB',
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

    public function keys(): HasMany
    {
        return $this->hasMany(Key::class, 'holder_id')->orderBy('name');
    }

    public function kitChecks(): HasMany
    {
        return $this->hasMany(KitCheck::class)->orderByDesc('checked_on');
    }

    public function latestKitCheck(): HasOne
    {
        return $this->hasOne(KitCheck::class)->ofMany('checked_on');
    }

    public function qualifications(): HasMany
    {
        return $this->hasMany(Qualification::class)->notExpired()->ordered();
    }

    public function allQualifications(): HasMany
    {
        return $this->hasMany(Qualification::class);
    }

    public function user_accreditations(): HasMany
    {
        return $this->hasMany(UserAccreditation::class);
    }

    public static function findByEmail(string $email): ?static
    {
        $interchangeable = [
            ['btopenworld.com', 'btinternet.com'],
            ['googlemail.com', 'gmail.com'],
        ];

        $parts = explode('@', $email, 2);

        $emails = [$email];
        foreach ($interchangeable as $domains) {
            if (in_array($parts[1], $domains)) {
                $emails = array_map(fn ($domain) => $parts[0].'@'.$domain, $domains);
            }
        }

        return static::whereIn('email', $emails)->first();
    }

    public function getAccreditationsAttribute(): Collection
    {
        return $this->user_accreditations->pluck('accreditation');
    }

    /**
     * Sync accreditations to the given list.
     *
     * @param  null|array<string|Accreditation>  $newAccreditations
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
     */
    public function isActive(): bool
    {
        return $this->password != '';
    }

    public function isTeamLeader(): bool
    {
        return $this->role == Role::TeamLeader;
    }

    public function isUnder18(): bool
    {
        return ! in_array($this->section, [Section::Parent, Section::Adult, Section::Network]);
    }

    public function isParent(): bool
    {
        return $this->section == Section::Parent;
    }

    public function isGuest(): bool
    {
        return $this->role == Role::Guest;
    }

    public function isKeyHolder(): bool
    {
        return $this->keys->isNotEmpty();
    }

    public function isKitChecker(): bool
    {
        return $this->accreditations->contains(Accreditation::KitChecker);
    }

    public function isPermitHolder(): bool
    {
        return $this->qualifications->where('detail_type', ScoutPermit::class)->count() > 0;
    }

    public function isBookingManager(): bool
    {
        return $this->accreditations->contains(Accreditation::ManageBookings);
    }

    public function isQualificationManager(): bool
    {
        return $this->accreditations->contains(Accreditation::ManageQualifications);
    }

    public function isUserManager(): bool
    {
        return $this->accreditations->contains(Accreditation::ManageUsers);
    }

    public function sendAccountSetupNotification()
    {
        $this->notify(new SetupAccount);
    }

    /**
     * Make a new ical token for a user.
     */
    public static function generateToken(): string
    {
        return hash('sha256', sprintf(
            '%s%s%s',
            config('app.token_prefix', ''),
            $tokenEntropy = Str::random(40),
            hash('crc32b', $tokenEntropy)
        ));
    }
}
