<?php

namespace App\Models;

use App\Enums\Incident\Gender;
use App\Enums\Incident\Injury;
use App\Enums\Incident\MembershipType;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Propaganistas\LaravelPhone\PhoneNumber;

class Incident
{
    public Carbon $dateTime;

    public string $locationName;

    public string $locationDescription;

    public bool $injured;

    public ?string $injuredName = null;

    public ?Carbon $injuredDateOfBirth = null;

    public ?Gender $injuredGender = null;

    public MembershipType $membershipType;

    public ?string $groupName = null;

    public string $contactName;

    public ?PhoneNumber $contactPhone = null;

    public ?string $contactAddress = null;

    /**
     * @var Collection<Injury>
     */
    public ?Collection $injuries;

    public bool $emergencyServices;

    public bool $hospital;

    public bool $damaged;

    public string $details;

    public ?string $firstAid = null;

    public string $reporterName;

    public string $reporterEmail;

    public PhoneNumber $reporterPhone;

    public function __construct(array $attributes = [])
    {
        $this->dateTime = Carbon::now();
        $this->fill($attributes);
    }

    public function fill(array $attributes): static
    {
        foreach ($attributes as $name => $value) {
            $attributeName = match ($name) {
                'injured_dob' => 'injuredDateOfBirth',
                'date', 'time' => 'dateTime',
                default => Str::camel($name),
            };
            $attributeValue = match ($name) {
                'date' => $this->dateTime->setDateFrom(Carbon::parse($value)),
                'time' => $this->dateTime->setTimeFromTimeString($value),
                'injured_dob' => Carbon::parse($value),
                'injured', 'emergency_services', 'hospital', 'damaged' => $value == 'yes',
                'injured_gender' => Gender::tryFrom($value),
                'injuries' => collect($value)->map(fn ($injury) => Injury::tryFrom($injury)),
                'membership_type' => MembershipType::tryFrom($value),
                'contact_phone', 'reporter_phone' => new PhoneNumber($value, 'GB'),
                default => $value,
            };

            $this->$attributeName = $attributeValue;
        }

        return $this;
    }
}
